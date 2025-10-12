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
        $data = [
            'sender_name' => $senderName ?? $this->defaultSenderName,
            'to' => [$recipient],
            'message' => $message,
        ];

        return $this->client->post('/v1/sms/send', $data);
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
}
