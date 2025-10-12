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
        $body = $e->getResponse() ? $e->getResponse()->getBody()->getContents() : '';
        $decodedBody = json_decode($body, true);

        if ($statusCode === 401) {
            throw new UnauthorizedException($decodedBody['detail'] ?? 'Informations d\'authentification non fournies.');
        }

        if ($statusCode === 429) {
            throw new TooManyRequestsException($decodedBody['detail'] ?? 'Rate limit exceeded');
        }

        if ($statusCode >= 500) {
            throw new ServerException($decodedBody['detail'] ?? 'Internal server error');
        }

        throw new NimbaSmsException($decodedBody['detail'] ?? $e->getMessage());
    }
}
