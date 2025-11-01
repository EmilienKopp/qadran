<script lang="ts">
  import { type Page, xPost } from '$lib/inertia';
  import { onMount, tick, onDestroy } from 'svelte';

  let isRecording = $state(false);
  let isProcessing = $state(false);
  let transcript = $state('');
  let error = $state('');
  let mediaRecorder: MediaRecorder | null = null;
  let audioChunks: Blob[] = [];
  let isMinimized = $state(true);
  let voiceActivationEnabled = $state(false);
  let isListening = $state(false);
  let volumeThreshold = $state(30); // 0-100 scale
  let currentVolume = $state(0);
  let audioContext: AudioContext | null = null;
  let analyser: AnalyserNode | null = null;
  let microphone: MediaStreamAudioSourceNode | null = null;
  let animationFrameId: number | null = null;
  let listeningStream: MediaStream | null = null;
  let showSettings = $state(false);

  onMount(() => {
    // Check if browser supports MediaRecorder
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
      error = 'Your browser does not support audio recording';
    }

		// Trigger open and start recording on Ctrl/Cmd + Shift + V
		window.addEventListener('keydown', handleKeydown);
  });

  onDestroy(() => {
    stopListening();
    window.removeEventListener('keydown', handleKeydown);
  });

  async function startRecording() {
    try {
      error = '';
      transcript = '';
      audioChunks = [];

      // Use existing stream if listening, otherwise get new stream
      const stream = listeningStream || await navigator.mediaDevices.getUserMedia({ audio: true });
      mediaRecorder = new MediaRecorder(stream);

      mediaRecorder.ondataavailable = (event) => {
        if (event.data.size > 0) {
          audioChunks.push(event.data);
        }
      };

      mediaRecorder.onstop = async () => {
        const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
        await sendAudioToServer(audioBlob);

        // Only stop tracks if not in voice activation mode
        if (!voiceActivationEnabled) {
          stream.getTracks().forEach((track) => track.stop());
        }
      };

      mediaRecorder.start();
      isRecording = true;
    } catch (err) {
      error = `Failed to start recording: ${err instanceof Error ? err.message : 'Unknown error'}`;
      console.error('Recording error:', err);
    }
  }

  function stopRecording() {
    if (mediaRecorder && isRecording) {
      mediaRecorder.stop();
      isRecording = false;
    }
  }

  function handleSuccess(event: Page) {
    isProcessing = false;
    const data = event.props.flash?.data;
    if (data?.transcript) {
      transcript = data.transcript;
    }
    if (data?.audioError) {
      error = data.audioError;
    }
  }

	function handleError(errors: any) {
		isProcessing = false;
		error = `Failed to process audio: ${errors.audio || Object.values(errors)[0] || 'Unknown error'}`;
		console.error('Processing error:', errors);
	}

  function sendAudioToServer(audioBlob: Blob) {
    isProcessing = true;
    error = '';

    const formData = new FormData();
    formData.append('audio', audioBlob, 'recording.webm');

    xPost(route('audio.transcribe'), formData, {
      forceFormData: true,
      onSuccess: handleSuccess,
      onError: handleError,
      onFinish: () => {
        isProcessing = false;
      },
    });
  }

  function toggleMinimize() {
    isMinimized = !isMinimized;
  }

  function clearTranscript() {
    transcript = '';
    error = '';
  }

	async function handleKeydown(event: KeyboardEvent) {
			if ((event.ctrlKey || event.metaKey) && event.shiftKey && event.key.toLowerCase() === 'v') {
				event.preventDefault();
				if (isMinimized) {
					isMinimized = false;
				}
				await tick();
				if (!isRecording && !isProcessing) {
					startRecording();
				} else if (isRecording) {
					stopRecording();
				}
			}
		}

  async function startListening() {
    try {
      error = '';
      const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
      listeningStream = stream;

      // Create audio context and analyser for volume detection
      audioContext = new AudioContext();
      analyser = audioContext.createAnalyser();
      analyser.fftSize = 256;

      microphone = audioContext.createMediaStreamSource(stream);
      microphone.connect(analyser);

      isListening = true;
      monitorAudioLevel();
    } catch (err) {
      error = `Failed to access microphone: ${err instanceof Error ? err.message : 'Unknown error'}`;
      console.error('Listening error:', err);
    }
  }

  function monitorAudioLevel() {
    if (!analyser || !isListening) return;

    const bufferLength = analyser.frequencyBinCount;
    const dataArray = new Uint8Array(bufferLength);

    const checkLevel = () => {
      if (!isListening || !analyser) return;

      analyser.getByteFrequencyData(dataArray);

      // Calculate average volume (0-255 scale)
      const average = dataArray.reduce((sum, value) => sum + value, 0) / bufferLength;

      // Convert to 0-100 scale
      currentVolume = Math.min(100, Math.round((average / 255) * 100));

      // Start recording if volume exceeds threshold and not already recording
      if (currentVolume >= volumeThreshold && !isRecording && !isProcessing && voiceActivationEnabled) {
        startRecording();
      }

      animationFrameId = requestAnimationFrame(checkLevel);
    };

    checkLevel();
  }

  function stopListening() {
    isListening = false;

    if (animationFrameId !== null) {
      cancelAnimationFrame(animationFrameId);
      animationFrameId = null;
    }

    if (microphone) {
      microphone.disconnect();
      microphone = null;
    }

    if (analyser) {
      analyser.disconnect();
      analyser = null;
    }

    if (audioContext && audioContext.state !== 'closed') {
      audioContext.close();
      audioContext = null;
    }

    if (listeningStream) {
      listeningStream.getTracks().forEach((track) => track.stop());
      listeningStream = null;
    }

    currentVolume = 0;
  }

  async function toggleVoiceActivation() {
    voiceActivationEnabled = !voiceActivationEnabled;

    if (voiceActivationEnabled) {
      await startListening();
    } else {
      stopListening();
    }
  }

  function toggleSettings() {
    showSettings = !showSettings;
  }
</script>

<div class="fixed bottom-4 right-4 z-50 w-80">
  <div class="card bg-base-200 shadow-xl border border-base-300">
    <!-- Header -->
    <div class="card-body p-3">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"
            />
          </svg>
          <h3 class="font-semibold text-sm">Voice Assistant</h3>
          {#if isRecording}
            <span class="badge badge-error badge-xs animate-pulse">REC</span>
          {:else if isListening}
            <span class="badge badge-info badge-xs">LISTENING</span>
          {/if}
        </div>
        <button
          onclick={toggleMinimize}
          class="btn btn-ghost btn-xs btn-circle"
          aria-label="Toggle minimize"
        >
          {#if isMinimized}
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-4 w-4"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M5 15l7-7 7 7"
              />
            </svg>
          {:else}
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-4 w-4"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7"
              />
            </svg>
          {/if}
        </button>
      </div>

      {#if !isMinimized}
        <div class="divider my-1"></div>

        <!-- Voice Activation Toggle -->
        <div class="flex items-center justify-between mb-3">
          <div class="flex items-center gap-2">
            <span class="text-xs font-medium">Voice Activation</span>
            {#if isListening && currentVolume > 0}
              <div class="flex items-center gap-1">
                <div
                  class="h-2 rounded-full bg-success transition-all duration-75"
                  style="width: {Math.max(20, currentVolume)}px"
                ></div>
                <span class="text-xs opacity-60">{currentVolume}%</span>
              </div>
            {/if}
          </div>
          <div class="flex items-center gap-1">
            <button
              onclick={toggleSettings}
              class="btn btn-ghost btn-xs btn-circle"
              aria-label="Settings"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-3 w-3"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                />
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                />
              </svg>
            </button>
            <input
              type="checkbox"
              class="toggle toggle-sm toggle-success"
              checked={voiceActivationEnabled}
              onchange={toggleVoiceActivation}
            />
          </div>
        </div>

        <!-- Settings Panel -->
        {#if showSettings}
          <div class="bg-base-100 p-3 rounded-lg mb-3">
            <label class="form-control">
              <div class="label">
                <span class="label-text text-xs">Volume Threshold: {volumeThreshold}%</span>
              </div>
              <input
                type="range"
                min="10"
                max="80"
                bind:value={volumeThreshold}
                class="range range-xs range-success"
              />
              <div class="label">
                <span class="label-text-alt text-xs opacity-60">
                  Recording starts when volume exceeds this level
                </span>
              </div>
            </label>
          </div>
        {/if}

        <!-- Error Display -->
        {#if error}
          <div class="alert alert-error py-2 px-3 text-xs">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="stroke-current shrink-0 h-4 w-4"
              fill="none"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
              />
            </svg>
            <span>{error}</span>
          </div>
        {/if}

        <!-- Transcript Display -->
        {#if transcript}
          <div class="bg-base-100 p-3 rounded-lg text-sm">
            <div class="flex items-start justify-between gap-2">
              <p class="flex-1">{transcript}</p>
              <button
                onclick={clearTranscript}
                class="btn btn-ghost btn-xs btn-circle"
                aria-label="Clear transcript"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                  />
                </svg>
              </button>
            </div>
          </div>
        {/if}

        <!-- Controls -->
        <div class="flex gap-2 justify-center mt-2">
          {#if !isRecording && !isProcessing}
            <button
              onclick={startRecording}
              class="btn btn-primary btn-sm gap-2"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"
                />
              </svg>
              Start Recording
            </button>
          {:else if isRecording}
            <button onclick={stopRecording} class="btn btn-error btn-sm gap-2">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
              Stop Recording
            </button>
          {:else if isProcessing}
            <button class="btn btn-sm gap-2" disabled>
              <span class="loading loading-spinner loading-sm"></span>
              Processing...
            </button>
          {/if}
        </div>
      {/if}
    </div>
  </div>
</div>
