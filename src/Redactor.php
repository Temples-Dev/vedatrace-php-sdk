<?php

namespace VedaTrace;

class Redactor
{
    private array $paths;
    private string $mask;

    public function __construct(array $config)
    {
        $this->paths = $config['paths'] ?? [];
        $this->mask = $config['mask'] ?? '[REDACTED]';
    }

    public function redact(array $data): array
    {
        if (empty($this->paths)) {
            return $data;
        }

        foreach ($this->paths as $path) {
            $data = $this->applyRedactionToPath($data, $path);
        }

        return $data;
    }

    private function applyRedactionToPath(array $data, string $path): array
    {
        $keys = explode('.', $path);
        return $this->traverseAndRedact($data, $keys);
    }

    private function traverseAndRedact(array $data, array $keys): array
    {
        $key = array_shift($keys);

        if (!isset($data[$key])) {
            return $data;
        }

        if (empty($keys)) {
            $data[$key] = $this->mask;
        } elseif (is_array($data[$key])) {
            $data[$key] = $this->traverseAndRedact($data[$key], $keys);
        }

        return $data;
    }
}
