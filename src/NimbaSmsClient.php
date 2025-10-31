<?php

namespace Tmoh\NimbaSms;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Tmoh\NimbaSms\Exceptions\NimbaSmsException;
use Tmoh\NimbaSms\Exceptions\ServerException;
use Tmoh\NimbaSms\Exceptions\TooManyRequestsException;
use Tmoh\NimbaSms\Exceptions\UnauthorizedException;

class NimbaSmsClient
{
    private const CONTENT_TYPE = 'application/json';

    private Client $client;
    private string $baseUrl;
    private string $token;

    public function __construct(string $baseUrl, string $token, int $timeout = 30)
    {
        $this->client = new Client(['timeout' => $timeout]);
        $this->baseUrl = $baseUrl;
        $this->token = $token;
    }

    public function get(string $endpoint): array
    {
        try {
            $response = $this->client->get($this->baseUrl . $endpoint, [
                'headers' => [
                    'Authorization' => $this->token,
                    'Accept' => self::CONTENT_TYPE,
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (RequestException $e) {
            $this->handleError($e);

            return [];
        }
    }

    public function post(string $endpoint, array $data): array
    {
        try {
            $response = $this->client->post($this->baseUrl . $endpoint, [
                'headers' => [
                    'Authorization' => $this->token,
                    'Content-Type' => self::CONTENT_TYPE,
                    'Accept' => self::CONTENT_TYPE,
                ],
                'json' => $data,
            ]);

            $statusCode = $response->getStatusCode();
            $body = json_decode($response->getBody()->getContents(), true);

            if ($statusCode === 201) {
                return $body;
            }

            throw new NimbaSmsException('Unexpected response status: ' . $statusCode);

        } catch (RequestException $e) {
            $this->handleError($e);

            return [];
        }
    }

    private function handleError(RequestException $e): void
    {
        $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 0;
        $body = '';
        $decodedBody = [];

        if ($e->getResponse()) {
            $responseBody = $e->getResponse()->getBody();
            if ($responseBody->isSeekable()) {
                $responseBody->rewind();
            }
            $body = $responseBody->getContents();
            $decodedBody = json_decode($body, true) ?? [];
        }

        $errorMessage = $decodedBody['detail']
            ?? $decodedBody['message']
            ?? $decodedBody['sender_name']
            ?? (is_string($decodedBody) ? $decodedBody : null)
            ?? $e->getMessage();

        if ($statusCode === 401) {
            throw new UnauthorizedException($errorMessage);
        }

        if ($statusCode === 429) {
            throw new TooManyRequestsException($errorMessage);
        }

        if ($statusCode >= 500) {
            throw new ServerException($errorMessage);
        }

        throw new NimbaSmsException($errorMessage);
    }
}
