<?php
declare(strict_types=1);

namespace App\Adapter\In\Web;

final readonly class Response
{
    /** @param array<string, string> $headers */
    public function __construct(public string $body = '', public int $status = 200, public array $headers = []) {}
    public static function redirect(string $location): self { return new self('', 303, ['Location' => $location]); }
    public function send(): void
    {
        http_response_code($this->status);
        header('Content-Type: text/html; charset=UTF-8');
        foreach ($this->headers as $name => $value) { header($name . ': ' . $value); }
        echo $this->body;
    }
}
