<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class VedaTraceDemoController extends Controller
{
    /**
     * Show the VedaTrace Demo Dashboard.
     */
    public function index()
    {
        return view('vedatrace-demo');
    }

    /**
     * Send a log to VedaTrace.
     */
    public function sendLog(Request $request)
    {
        $level = $request->input('level', 'info');
        $message = $request->input('message', 'Demo log message');
        $metadata = $request->input('metadata', []);

        if (is_string($metadata)) {
            $metadata = json_decode($metadata, true) ?: [];
        }

        // Use standard Laravel Log facade which is configured to use VedaTrace
        Log::log($level, $message, $metadata);

        return response()->json([
            'status' => 'success',
            'message' => 'Log sent to VedaTrace',
            'data' => [
                'level' => $level,
                'message' => $message,
                'metadata' => $metadata
            ]
        ]);
    }

    /**
     * Simulate an exception to show stack trace capture.
     */
    public function simulateError()
    {
        try {
            $this->riskyBusiness();
        } catch (Throwable $e) {
            Log::critical('CRITICAL SYSTEM FAILURE: Unhandled exception detected', [
                'exception' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => 'admin_system',
                'trace' => true,
                'severity' => 'FATAL'
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Simulated exception captured and logged.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * A helper method to create a stack trace.
     */
    private function riskyBusiness()
    {
        throw new \RuntimeException('Something went wrong in the risky business logic!');
    }
}
