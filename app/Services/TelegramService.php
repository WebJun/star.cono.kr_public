<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    private $telegramToken;
    private $telegramChatId;

    public function __construct()
    {
        $this->telegramToken = getenv('TELEGRAM_TOKEN');
        $this->telegramChatId = getenv('TELEGRAM_CHATID');
    }

    public function send(string $content = 'blank'): int
    {
        $statusCode = 500;
        try {
            $url = "https://api.telegram.org/bot{$this->telegramToken}/sendMessage";
            $client = new Client();
            $response = $client->post($url, [
                'json' => [
                    'chat_id' => $this->telegramChatId,
                    'text' => $content,
                ],
            ]);
            $statusCode = $response->getStatusCode();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        return $statusCode;
    }
}
