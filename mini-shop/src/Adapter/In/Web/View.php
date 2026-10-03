<?php
declare(strict_types=1);

namespace App\Adapter\In\Web;

use App\Domain\Money;
final readonly class View
{
    public function __construct(private string $directory) {}
    public static function escape(string $value): string { return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
    public static function money(Money $money): string { return number_format($money->cents / 100, 2, ',', ' ') . ' €'; }
    /** @param array<string, mixed> $data */
    public function render(string $template, array $data = []): Response
    {
        extract($data, EXTR_SKIP);
        ob_start();
        try {
            require $this->directory . '/' . $template . '.php';
            $content = ob_get_clean();
        } catch (\Throwable $error) { ob_end_clean(); throw $error; }
        ob_start();
        try {
            require $this->directory . '/layout.php';
            return new Response(ob_get_clean());
        } catch (\Throwable $error) { ob_end_clean(); throw $error; }
    }
}
