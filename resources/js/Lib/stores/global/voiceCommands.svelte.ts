import { router } from '@inertiajs/svelte';
import { voiceAssistant } from './voiceAssistant.svelte';

// Type for Web Speech Recognition API
interface SpeechRecognitionEvent extends Event {
  results: SpeechRecognitionResultList;
  resultIndex: number;
}

interface SpeechRecognitionErrorEvent extends Event {
  error: string;
  message: string;
}

interface SpeechRecognition extends EventTarget {
  continuous: boolean;
  interimResults: boolean;
  lang: string;
  start(): void;
  stop(): void;
  abort(): void;
  onresult: ((event: SpeechRecognitionEvent) => void) | null;
  onerror: ((event: SpeechRecognitionErrorEvent) => void) | null;
  onend: (() => void) | null;
  onstart: (() => void) | null;
}

// Command type definition
type CommandAction = () => void | Promise<void>;

interface VoiceCommand {
  patterns: string[];
  action: CommandAction;
  description: string;
}

class VoiceCommandsService {
  // Reactive state
  #isListening = $state(false);
  #lastCommand = $state('');
  #error = $state('');
  #isSupported = $state(false);

  // Private state
  #recognition: SpeechRecognition | null = null;
  #commands: Map<string, VoiceCommand> = new Map();

  constructor() {
    this.#isSupported = this.checkBrowserSupport();
    if (this.#isSupported) {
      this.initializeRecognition();
      this.registerDefaultCommands();
    }
  }

  private checkBrowserSupport(): boolean {
    return 'webkitSpeechRecognition' in window || 'SpeechRecognition' in window;
  }

  private initializeRecognition() {
    const SpeechRecognitionConstructor = 
      (window as any).SpeechRecognition || (window as any).webkitSpeechRecognition;
    
    if (!SpeechRecognitionConstructor) return;

    this.#recognition = new SpeechRecognitionConstructor() as SpeechRecognition;
    this.#recognition.continuous = false; // Stop after one command
    this.#recognition.interimResults = false;
    this.#recognition.lang = 'en-US';

    this.#recognition.onresult = (event: SpeechRecognitionEvent) => {
      const transcript = event.results[0][0].transcript.toLowerCase().trim();
      console.log('Voice command heard:', transcript);
      this.#lastCommand = transcript;
      this.processCommand(transcript);
    };

    this.#recognition.onerror = (event: SpeechRecognitionErrorEvent) => {
      console.error('Speech recognition error:', event.error);
      this.#error = `Speech recognition error: ${event.error}`;
      this.#isListening = false;
    };

    this.#recognition.onend = () => {
      this.#isListening = false;
    };

    this.#recognition.onstart = () => {
      this.#isListening = true;
      this.#error = '';
    };
  }

  private registerDefaultCommands() {
    // Navigation commands
    this.registerCommand('projects', {
      patterns: ['go to projects', 'open projects', 'show projects', 'projects'],
      action: () => {
        router.visit(route('project.index'));
      },
      description: 'Navigate to projects'
    });

    this.registerCommand('organizations', {
      patterns: ['go to organizations', 'open organizations', 'show organizations', 'organizations'],
      action: () => {
        router.visit(route('organization.index'));
      },
      description: 'Navigate to organizations'
    });

    this.registerCommand('rates', {
      patterns: ['go to rates', 'open rates', 'show rates', 'rates'],
      action: () => {
        router.visit(route('rate.index'));
      },
      description: 'Navigate to rates'
    });

    this.registerCommand('reports', {
      patterns: ['go to reports', 'open reports', 'show reports', 'reports'],
      action: () => {
        router.visit(route('report.index'));
      },
      description: 'Navigate to reports'
    });

    this.registerCommand('settings', {
      patterns: ['go to settings', 'open settings', 'show settings', 'settings'],
      action: () => {
        router.visit(route('profile.edit'));
      },
      description: 'Navigate to settings'
    });

    this.registerCommand('profile', {
      patterns: ['go to profile', 'open profile', 'show profile', 'my profile'],
      action: () => {
        router.visit(route('profile.edit'));
      },
      description: 'Navigate to profile'
    });

    // Browser navigation commands
    this.registerCommand('back', {
      patterns: ['go back', 'back', 'previous page'],
      action: () => {
        window.history.back();
      },
      description: 'Go back to previous page'
    });

    this.registerCommand('forward', {
      patterns: ['go forward', 'forward', 'next page'],
      action: () => {
        window.history.forward();
      },
      description: 'Go forward'
    });

    this.registerCommand('refresh', {
      patterns: ['refresh', 'reload', 'refresh page', 'reload page'],
      action: () => {
        window.location.reload();
      },
      description: 'Refresh current page'
    });

    // Scroll commands
    this.registerCommand('scroll-down', {
      patterns: ['scroll down', 'page down', 'down'],
      action: () => {
        window.scrollBy({ top: 300, behavior: 'smooth' });
      },
      description: 'Scroll down'
    });

    this.registerCommand('scroll-up', {
      patterns: ['scroll up', 'page up', 'up'],
      action: () => {
        window.scrollBy({ top: -300, behavior: 'smooth' });
      },
      description: 'Scroll up'
    });

    this.registerCommand('scroll-top', {
      patterns: ['scroll to top', 'top of page', 'go to top'],
      action: () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      },
      description: 'Scroll to top of page'
    });

    this.registerCommand('scroll-bottom', {
      patterns: ['scroll to bottom', 'bottom of page', 'go to bottom'],
      action: () => {
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
      },
      description: 'Scroll to bottom of page'
    });

    // Voice control commands
    this.registerCommand('start-voice', {
      patterns: ['start voice listening', 'enable voice', 'voice on', 'start listening'],
      action: async () => {
        if (!voiceAssistant.voiceActivationEnabled) {
          voiceAssistant.voiceActivationEnabled = true;
          await voiceAssistant.startListening();
        }
      },
      description: 'Enable voice activation for voice assistant'
    });

    this.registerCommand('stop-voice', {
      patterns: ['stop voice listening', 'disable voice', 'voice off', 'stop listening'],
      action: async () => {
        if (voiceAssistant.voiceActivationEnabled) {
          voiceAssistant.voiceActivationEnabled = false;
          voiceAssistant.stopListening();
        }
      },
      description: 'Disable voice activation for voice assistant'
    });

    // Help command
    this.registerCommand('help', {
      patterns: ['help', 'show commands', 'what can you do', 'available commands'],
      action: () => {
        this.showAvailableCommands();
      },
      description: 'Show available voice commands'
    });
  }

  private processCommand(transcript: string) {
    let commandFound = false;

    // Try exact match first
    for (const [_, command] of this.#commands) {
      for (const pattern of command.patterns) {
        if (transcript === pattern) {
          command.action();
          commandFound = true;
          return;
        }
      }
    }

    // Try fuzzy matching (partial match)
    if (!commandFound) {
      for (const [_, command] of this.#commands) {
        for (const pattern of command.patterns) {
          if (transcript.includes(pattern) || pattern.includes(transcript)) {
            command.action();
            commandFound = true;
            return;
          }
        }
      }
    }

    if (!commandFound) {
      console.log('No matching command found for:', transcript);
      this.#error = `Command not recognized: "${transcript}". Say "help" to see available commands.`;
    }
  }

  private showAvailableCommands() {
    const commandList = Array.from(this.#commands.values())
      .map(cmd => `• ${cmd.patterns[0]}: ${cmd.description}`)
      .join('\n');
    
    console.log('Available voice commands:\n', commandList);
    // Store commands in error state to display in UI
    this.#error = 'Voice commands available. Check console for full list.';
  }

  // Public methods
  registerCommand(id: string, command: VoiceCommand) {
    this.#commands.set(id, command);
  }

  unregisterCommand(id: string) {
    this.#commands.delete(id);
  }

  startListening = () => {
    if (!this.#isSupported) {
      this.#error = 'Speech recognition is not supported in your browser';
      return;
    }

    if (!this.#recognition) {
      this.#error = 'Speech recognition not initialized';
      return;
    }

    if (this.#isListening) {
      console.log('Already listening');
      return;
    }

    try {
      this.#recognition.start();
    } catch (err) {
      console.error('Failed to start voice commands:', err);
      this.#error = 'Failed to start voice commands: ' + (err as Error).message;
    }
  };

  stopListening = () => {
    if (this.#recognition && this.#isListening) {
      this.#recognition.stop();
    }
  };

  clearError = () => {
    this.#error = '';
  };

  clearLastCommand = () => {
    this.#lastCommand = '';
  };

  // Getters
  get isListening() {
    return this.#isListening;
  }

  get lastCommand() {
    return this.#lastCommand;
  }

  get error() {
    return this.#error;
  }

  get isSupported() {
    return this.#isSupported;
  }

  get commands() {
    return Array.from(this.#commands.entries()).map(([id, cmd]) => ({
      id,
      ...cmd
    }));
  }
}

export const voiceCommands = new VoiceCommandsService();
export default voiceCommands;
