<?php

namespace App\Support;

use RuntimeException;

class EnvironmentFile
{
    /**
     * @param  array<string, string>  $values
     */
    public function update(string $path, array $values): void
    {
        if (! is_file($path) || ! is_readable($path) || ! is_writable($path)) {
            throw new RuntimeException("Environment file is not readable and writable: {$path}");
        }

        $contents = file_get_contents($path);

        if ($contents === false) {
            throw new RuntimeException("Unable to read environment file: {$path}");
        }

        foreach ($values as $key => $value) {
            $line = $key.'='.$this->encode($value);
            $pattern = '/^'.preg_quote($key, '/').'\s*=.*$/m';

            if (preg_match($pattern, $contents) === 1) {
                $contents = preg_replace($pattern, $line, $contents, 1);
            } else {
                $contents = rtrim($contents).PHP_EOL.$line.PHP_EOL;
            }
        }

        if (file_put_contents($path, $contents, LOCK_EX) === false) {
            throw new RuntimeException("Unable to update environment file: {$path}");
        }
    }

    private function encode(string $value): string
    {
        if ($value !== '' && preg_match('/^[A-Za-z0-9._-]+$/', $value) === 1) {
            return $value;
        }

        return '"'.str_replace(['\\', '"'], ['\\\\', '\\"'], $value).'"';
    }
}
