<?php

namespace Tmoh\NimbaSms;

class NimbaSmsService
{
    private NimbaSmsClient $client;
    private string $defaultSenderName;

    public function __construct(NimbaSmsClient $client, string $defaultSenderName)
    {
        $this->client = $client;
        $this->defaultSenderName = $defaultSenderName;
    }

    public function sendSms(string $recipient, string $message, ?string $senderName = null): array
    {
        $finalSenderName = $senderName ?? $this->defaultSenderName;
        
        $data = [
            'sender_name' => $finalSenderName,
            'to' => [$recipient],
            'message' => $message,
        ];

        return $this->client->post('/v1/messages', $data);
    }

    public function getAccounts(): array
    {
        return $this->client->get('/v1/accounts');
    }

    public function getSenderNames(): array
    {
        return $this->client->get('/v1/sendernames');
    }

    public function getWebhooks(): array
    {
        return $this->client->get('/v1/webhooks');
    }

    public function getPurchases(): array
    {
        return $this->client->get('/v1/purchases');
    }
}
