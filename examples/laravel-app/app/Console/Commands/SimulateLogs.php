<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use VedaTrace\Laravel\Facades\VedaTrace;

class SimulateLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vedatrace:simulate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulate logs to test VedaTrace integration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Simulating logs for VedaTrace...");

        // 1. Sending logs through standard Laravel Log facade (via Monolog handler)
        Log::info('This is an info level log sent via standard Laravel Log facade.', [
            'user_id' => 123,
            'action' => 'login_attempt'
        ]);
        
        Log::warning('This is a warning log from the Laravel application.', [
            'memory_usage' => memory_get_usage(true)
        ]);

        // 2. Sending logs through directly via the VedaTrace facade
        VedaTrace::info('Direct info log using VedaTrace facade', [
            'module' => 'billing',
            'transaction_id' => 'tx_987654321'
        ]);

        // Testing Redaction
        VedaTrace::error('Payment failed due to invalid token', [
            'error_code' => 500,
            'token' => 'sk_live_1234567890abcdef', // Should be redacted!
            'customer_email' => 'test@example.com'
        ]);

        $this->info("Logs dispatched successfully! Check your VedaTrace dashboard.");
    }
}
