<?php

namespace App\Jobs;

use App\Models\VoiceCommand;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Multitenancy\Jobs\TenantAware;

class StoreVoiceCommandJob implements ShouldQueue, TenantAware
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $userId,
        public string $transcript,
        public ?array $command = null,
        public ?array $metadata = null,
    ) {
        // You can specify a queue if needed
        // $this->onQueue('voice-commands');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        VoiceCommand::create([
            'user_id' => $this->userId,
            'transcript' => $this->transcript,
            'parsed_command' => $this->command,
            'metadata' => $this->metadata,
        ]);
    }

    /**
     * Get the tags for the job (useful for Horizon)
     */
    public function tags(): array
    {
        return ['voice-command', 'user:'.$this->userId];
    }
}
