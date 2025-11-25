<?php

namespace App\Support\Database;

use Illuminate\Database\Connectors\MySqlConnector;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use PDOException;

class FallbackMySqlConnector extends MySqlConnector
{
    /**
     * Attempt to connect to MySQL with graceful host fallbacks.
     *
     * @param  array<string, mixed>  $config
     */
    public function connect(array $config)
    {
        $hosts = $this->buildHostFallbacks($config);

        $lastException = null;

        foreach ($hosts as $host) {
            $activeConfig = $config;
            $activeConfig['host'] = $host;

            try {
                $connection = parent::connect($activeConfig);

                if ($host !== Arr::first($hosts)) {
                    Log::notice('Database host fallback engaged for MayClass.', [
                        'attempted_hosts' => $hosts,
                        'selected_host' => $host,
                    ]);
                }

                return $connection;
            } catch (PDOException $exception) {
                if ($this->shouldRetryWithoutPassword($activeConfig, $exception)) {
                    try {
                        $retryConfig = $activeConfig;
                        $retryConfig['password'] = '';

                        $connection = parent::connect($retryConfig);

                        Log::notice('Retried MySQL connection with empty password for local root user.', [
                            'hosts' => $hosts,
                            'selected_host' => $host,
                        ]);

                        return $connection;
                    } catch (PDOException $retryException) {
                        $exception = $retryException;
                    }
                }

                if (! $this->isConnectionRefused($exception)) {
                    throw $exception;
                }

                $lastException = $exception;
            }
        }

        if ($lastException instanceof PDOException) {
            Log::warning('Unable to establish database connection after trying all fallback hosts.', [
                'hosts' => $hosts,
                'message' => $lastException->getMessage(),
            ]);

            throw $lastException;
        }

        return parent::connect($config);
    }

    /**
     * @param  array<string, mixed>  $config
     * @return array<int, string>
     */
    private function buildHostFallbacks(array $config): array
    {
        $originalHost = $config['host'] ?? null;

        $fallbacks = array_values(array_filter([
            $originalHost,
            $config['failover_host'] ?? null,
            Arr::get($config, 'options.fallback_host'),
            env('DB_FAILOVER_HOST'),
            $originalHost === '127.0.0.1' ? 'localhost' : '127.0.0.1',
        ]));

        return array_values(array_unique($fallbacks));
    }

    private function isConnectionRefused(PDOException $exception): bool
    {
        if ((int) $exception->getCode() === 2002) {
            return true;
        }

        $message = strtolower($exception->getMessage());

        return str_contains($message, 'connection refused')
            || str_contains($message, 'actively refused');
    }

    private function shouldRetryWithoutPassword(array $config, PDOException $exception): bool
    {
        if ($this->isInvalidCredentials($exception) === false) {
            return false;
        }

        if (! app()->environment('local', 'development')) {
            return false;
        }

        if (($config['username'] ?? null) !== 'root') {
            return false;
        }

        $password = $config['password'] ?? null;

        if ($password === null || $password === '') {
            return false;
        }

        return true;
    }

    private function isInvalidCredentials(PDOException $exception): bool
    {
        if ((int) $exception->getCode() === 1045) {
            return true;
        }

        return str_contains(strtolower($exception->getMessage()), 'access denied');
    }
}

