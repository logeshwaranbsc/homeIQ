<?php
declare(strict_types=1);

namespace App\Core;

class Response
{
    private int $statusCode = 200;
    private array $headers = [];
    private string $content = '';

    public function setStatusCode(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);

        // Security headers
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        echo $this->content;
        exit;
    }

    public static function json(mixed $data, int $status = 200): never
    {
        $response = new self();
        $response->setStatusCode($status)
                 ->setHeader('Content-Type', 'application/json; charset=UTF-8')
                 ->setContent(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
                 ->send();
        exit;
    }

    public static function redirect(string $path, int $status = 302): never
    {
        $target = str_starts_with($path, 'http') ? $path : url($path);
        $response = new self();
        $response->setStatusCode($status)
                 ->setHeader('Location', $target)
                 ->send();
        exit;
    }
}

