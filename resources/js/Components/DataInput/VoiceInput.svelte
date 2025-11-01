<script lang="ts">
  import { type Page, xPost } from '$lib/inertia';
  import { onMount, tick } from 'svelte';

  let isRecording = $state(false);
  let isProcessing = $state(false);
  let transcript = $state('');
  let error = $state('');
  let mediaRecorder: MediaRecorder | null = null;
  let audioChunks: Blob[] = [];
  let isMinimized = $state(true);

  onMount(() => {
    // Check if browser supports MediaRecorder
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
      error = 'Your browser does not support audio recording';
    }

		// Trigger open and start recording on Ctrl/Cmd + Shift + V
		window.addEventListener('keydown', handleKeydown);
  });

  async function startRecording() {
    try {
      error = '';
      transcript = '';
      audioChunks = [];

      const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
      mediaRecorder = new MediaRecorder(stream);

      mediaRecorder.ondataavailable = (event) => {
        if (event.data.size > 0) {
          audioChunks.push(event.data);
        }
      };

      mediaRecorder.onstop = async () => {
        const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
        await sendAudioToServer(audioBlob);

        // Stop all tracks to release the microphone
        stream.getTracks().forEach((track) => track.stop());
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
          {/if}
        </div>
        <button
          onclick={toggleMinimize}
          class="btn btn-ghost btn-xs btn-circle"
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
