<?php

namespace App\Console\Commands;

use App\Models\Landlord\Tenant;
use Illuminate\Console\Command;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;

class TestStreamCommand extends Command
{
  use TenantAware;

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'test:stream {--tenant=*} {--userId=*}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Test command that outputs something every second for 10 seconds';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $this->info('🚀 Starting test stream command...');
    $this->newLine();

    for ($i = 1; $i <= 10; $i++) {
      $messages = [
        "⚡ Processing task {$i}/10...",
        "🔄 Analyzing data chunk {$i}...",
        "📊 Computing metrics for batch {$i}...",
        "🔍 Scanning database records {$i}...",
        "⚙️ Running background job {$i}...",
        "🌟 Generating report section {$i}...",
        "🎯 Optimizing query {$i}...",
        "🔧 Updating cache layer {$i}...",
        "📡 Syncing with external API {$i}...",
        "✨ Finalizing operation {$i}...",
      ];

      $this->line($messages[$i - 1]);

      if ($i < 10) {
        sleep(1);
      }
    }

    $this->newLine();
    $this->info('✅ Test stream completed successfully!');
    $this->comment('📈 All 10 operations finished in 10 seconds');

    return Command::SUCCESS;
  }
}