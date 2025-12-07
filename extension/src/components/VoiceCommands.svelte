<script>
  import { apiService } from '../services/api.js';

  let isRecording = $state(false);
  let transcript = $state('');
  let response = $state('');
  let message = $state('');
  let mediaRecorder = null;
  let audioChunks = [];

  async function startRecording() {
    try {
      const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
      
      mediaRecorder = new MediaRecorder(stream);
      audioChunks = [];

      mediaRecorder.ondataavailable = (event) => {
        audioChunks.push(event.data);
      };

      mediaRecorder.onstop = async () => {
        const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
        await sendVoiceCommand(audioBlob);
        
        // Stop all tracks
        stream.getTracks().forEach(track => track.stop());
      };

      mediaRecorder.start();
      isRecording = true;
      message = 'Recording... Click "Stop" when done';
      transcript = '';
      response = '';
    } catch (error) {
      message = 'Error accessing microphone: ' + error.message;
      console.error('Microphone error:', error);
    }
  }

  function stopRecording() {
    if (mediaRecorder && isRecording) {
      mediaRecorder.stop();
      isRecording = false;
      message = 'Processing voice command...';
    }
  }

  async function sendVoiceCommand(audioBlob) {
    try {
      const result = await apiService.sendVoiceCommand(audioBlob);
      
      transcript = result.transcript || 'Could not transcribe audio';
      response = result.assistantResponse || result.response || 'No response from assistant';
      message = 'Voice command processed successfully!';
    } catch (error) {
      message = 'Error processing voice command: ' + error.message;
      transcript = '';
      response = '';
    }
  }

  function sendTextCommand() {
    if (!transcript) {
      message = 'Please enter a command';
      return;
    }

    // For text commands, we'll just display them
    // In a real implementation, this would send to the text-to-assistant endpoint
    message = 'Text commands coming soon!';
  }
</script>

<div class="voice-commands">
  <h2>Voice Commands</h2>

  <div class="recorder">
    {#if !isRecording}
      <button 
        class="btn btn-primary btn-large"
        onclick={startRecording}
      >
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path>
          <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
          <line x1="12" y1="19" x2="12" y2="23"></line>
          <line x1="8" y1="23" x2="16" y2="23"></line>
        </svg>
        Start Voice Recording
      </button>
    {:else}
      <button 
        class="btn btn-danger btn-large recording"
        onclick={stopRecording}
      >
        <span class="recording-indicator"></span>
        Stop Recording
      </button>
    {/if}
  </div>

  {#if message}
    <div class="message" class:error={message.includes('Error')}>
      {message}
    </div>
  {/if}

  {#if transcript}
    <div class="result-section">
      <h3>Transcript:</h3>
      <div class="result-box transcript-box">
        {transcript}
      </div>
    </div>
  {/if}

  {#if response}
    <div class="result-section">
      <h3>Assistant Response:</h3>
      <div class="result-box response-box">
        {response}
      </div>
    </div>
  {/if}

  <div class="divider">
    <span>OR</span>
  </div>

  <div class="text-input-section">
    <h3>Type a Command:</h3>
    <div class="form-group">
      <textarea
        bind:value={transcript}
        placeholder="Enter your command here..."
        rows="3"
        class="textarea"
      ></textarea>
    </div>
    <button 
      class="btn btn-secondary"
      onclick={sendTextCommand}
      disabled={!transcript}
    >
      Send Command
    </button>
  </div>

  <div class="info">
    <h3>Example Commands:</h3>
    <ul>
      <li>"Clock in to Project Alpha"</li>
      <li>"Clock out"</li>
      <li>"Show my time for today"</li>
      <li>"What projects am I working on?"</li>
    </ul>
  </div>
</div>

<style>
  .voice-commands {
    max-width: 100%;
  }

  h2 {
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 20px 0;
    color: #1f2937;
  }

  h3 {
    font-size: 14px;
    font-weight: 600;
    margin: 0 0 8px 0;
    color: #374151;
  }

  .recorder {
    margin-bottom: 16px;
  }

  .btn {
    width: 100%;
    padding: 12px 16px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }

  .btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }

  .btn-large {
    padding: 16px 20px;
    font-size: 15px;
  }

  .btn-primary {
    background: #667eea;
    color: white;
  }

  .btn-primary:hover:not(:disabled) {
    background: #5568d3;
  }

  .btn-danger {
    background: #ef4444;
    color: white;
  }

  .btn-danger:hover:not(:disabled) {
    background: #dc2626;
  }

  .btn-secondary {
    background: #e5e7eb;
    color: #374151;
  }

  .btn-secondary:hover:not(:disabled) {
    background: #d1d5db;
  }

  .recording {
    animation: pulse-button 1.5s ease-in-out infinite;
  }

  @keyframes pulse-button {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
  }

  .recording-indicator {
    width: 12px;
    height: 12px;
    background: white;
    border-radius: 50%;
    animation: pulse 1s ease-in-out infinite;
  }

  @keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.6; transform: scale(0.9); }
  }

  .message {
    padding: 10px 12px;
    margin: 16px 0;
    border-radius: 6px;
    font-size: 13px;
    background: #dbeafe;
    color: #1e40af;
  }

  .message.error {
    background: #fee2e2;
    color: #991b1b;
  }

  .result-section {
    margin: 16px 0;
  }

  .result-box {
    padding: 12px;
    border-radius: 6px;
    font-size: 13px;
    line-height: 1.6;
    white-space: pre-wrap;
  }

  .transcript-box {
    background: #f3f4f6;
    color: #1f2937;
    border: 1px solid #d1d5db;
  }

  .response-box {
    background: #ede9fe;
    color: #5b21b6;
    border: 1px solid #c4b5fd;
  }

  .divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 24px 0;
    color: #9ca3af;
    font-size: 12px;
    font-weight: 500;
  }

  .divider::before,
  .divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e5e7eb;
  }

  .text-input-section {
    margin: 20px 0;
  }

  .form-group {
    margin-bottom: 12px;
  }

  .textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 13px;
    font-family: inherit;
    resize: vertical;
    box-sizing: border-box;
  }

  .textarea:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }

  .info {
    margin-top: 24px;
    padding: 16px;
    background: white;
    border-radius: 6px;
    border: 1px solid #e5e7eb;
  }

  ul {
    margin: 8px 0 0 0;
    padding-left: 20px;
    font-size: 12px;
    color: #6b7280;
  }

  ul li {
    margin-bottom: 4px;
  }
</style>
