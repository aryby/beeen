<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class M3UExtractorService
{
    /**
     * Extract M3U credentials from a given URL
     */
    public function extractFromUrl(string $m3uUrl): array
    {
        try {
            $response = Http::timeout(30)->get($m3uUrl);
            
            if (!$response->successful()) {
                throw new \Exception('Failed to fetch M3U file: ' . $response->status());
            }

            $m3uContent = $response->body();
            return $this->parseM3UContent($m3uContent);
            
        } catch (\Exception $e) {
            Log::error('M3U extraction failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Parse M3U content to extract credentials
     */
    public function parseM3UContent(string $content): array
    {
        $lines = explode("\n", $content);
        $credentials = [
            'server_url' => null,
            'username' => null,
            'password' => null,
            'port' => null,
            'protocol' => 'http',
        ];

        foreach ($lines as $line) {
            $line = trim($line);
            
            // Look for #EXTM3U line with server info
            if (strpos($line, '#EXTM3U') === 0) {
                // Extract server URL from the line
                if (preg_match('/http[s]?:\/\/[^\s]+/', $line, $matches)) {
                    $credentials['server_url'] = $matches[0];
                }
            }
            
            // Look for stream URLs
            if (strpos($line, 'http') === 0) {
                $parsedUrl = parse_url($line);
                
                if (isset($parsedUrl['host'])) {
                    $credentials['server_url'] = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
                    if (isset($parsedUrl['port'])) {
                        $credentials['port'] = $parsedUrl['port'];
                        $credentials['server_url'] .= ':' . $parsedUrl['port'];
                    }
                }
                
                // Extract username and password from URL
                if (isset($parsedUrl['user'])) {
                    $credentials['username'] = $parsedUrl['user'];
                }
                if (isset($parsedUrl['pass'])) {
                    $credentials['password'] = $parsedUrl['pass'];
                }
                
                // Extract from query parameters
                if (isset($parsedUrl['query'])) {
                    parse_str($parsedUrl['query'], $queryParams);
                    if (isset($queryParams['username'])) {
                        $credentials['username'] = $queryParams['username'];
                    }
                    if (isset($queryParams['password'])) {
                        $credentials['password'] = $queryParams['password'];
                    }
                }
            }
        }

        return $credentials;
    }

    /**
     * Generate M3U credentials for an order
     */
    public function generateForOrder(Order $order): Order
    {
        if (!$order->iptv_code) {
            $order->generateIptvCode();
        }

        // Generate M3U credentials
        $order->m3u_username = $order->iptv_code;
        $order->m3u_password = $order->iptv_code;
        $order->m3u_server_url = 'http://portal.iptv-pro.com:8080';
        $order->m3u_url = "http://portal.iptv-pro.com/get.php?username={$order->iptv_code}&password={$order->iptv_code}&type=m3u_plus";
        $order->m3u_generated = true;
        $order->m3u_generated_at = now();
        $order->save();

        return $order;
    }

    /**
     * Extract credentials from uploaded M3U file
     */
    public function extractFromFile(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new \Exception('M3U file not found');
        }

        $content = file_get_contents($filePath);
        return $this->parseM3UContent($content);
    }

    /**
     * Validate M3U credentials
     */
    public function validateCredentials(array $credentials): bool
    {
        return !empty($credentials['server_url']) && 
               !empty($credentials['username']) && 
               !empty($credentials['password']);
    }

    /**
     * Test M3U connection
     */
    public function testConnection(array $credentials): array
    {
        try {
            $testUrl = $credentials['server_url'] . '/get.php?' . http_build_query([
                'username' => $credentials['username'],
                'password' => $credentials['password'],
                'type' => 'm3u_plus'
            ]);

            $response = Http::timeout(10)->get($testUrl);
            
            return [
                'success' => $response->successful(),
                'status_code' => $response->status(),
                'test_url' => $testUrl,
                'message' => $response->successful() ? 'Connection successful' : 'Connection failed'
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'status_code' => 0,
                'test_url' => $testUrl ?? null,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }
}
