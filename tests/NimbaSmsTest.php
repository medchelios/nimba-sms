<?php

use Tmoh\NimbaSms\Exceptions\ServerException;
use Tmoh\NimbaSms\Exceptions\TooManyRequestsException;
use Tmoh\NimbaSms\Exceptions\UnauthorizedException;
use Tmoh\NimbaSms\NimbaSmsClient;
use Tmoh\NimbaSms\NimbaSmsService;

const TEST_BASE_URL = 'https://api.nimbasms.com';
const TEST_TOKEN = 'Basic test-token';

describe('NimbaSmsClient', function () {
    it('can be instantiated with parameters', function () {
        $client = new NimbaSmsClient(TEST_BASE_URL, TEST_TOKEN, 30);

        expect($client)->toBeInstanceOf(NimbaSmsClient::class);
    });
});

describe('NimbaSmsService', function () {
    it('can be instantiated with client and sender name', function () {
        $client = new NimbaSmsClient(TEST_BASE_URL, TEST_TOKEN, 30);
        $service = new NimbaSmsService($client, 'TEST');

        expect($service)->toBeInstanceOf(NimbaSmsService::class);
    });

    it('uses default sender name when none provided', function () {
        $client = new NimbaSmsClient(TEST_BASE_URL, TEST_TOKEN, 30);
        $service = new NimbaSmsService($client, 'DEFAULT');

        expect($service)->toBeInstanceOf(NimbaSmsService::class);
    });

    it('has getPurchases method', function () {
        $client = new NimbaSmsClient(TEST_BASE_URL, TEST_TOKEN, 30);
        $service = new NimbaSmsService($client, 'TEST');

        expect(method_exists($service, 'getPurchases'))->toBeTrue();
    });
});

describe('Exceptions', function () {
    it('can throw UnauthorizedException', function () {
        expect(fn () => throw new UnauthorizedException('Test error'))
            ->toThrow(UnauthorizedException::class, 'Test error');
    });

    it('can throw TooManyRequestsException', function () {
        expect(fn () => throw new TooManyRequestsException('Rate limit'))
            ->toThrow(TooManyRequestsException::class, 'Rate limit');
    });

    it('can throw ServerException', function () {
        expect(fn () => throw new ServerException('Server error'))
            ->toThrow(ServerException::class, 'Server error');
    });
});
