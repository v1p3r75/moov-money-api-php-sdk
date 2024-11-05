<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use MoovMoney\Exceptions\ServerErrorException;
use MoovMoney\MoovMoneyAPI;
use MoovMoney\MoovMoneyAPIConfig;
use MoovMoney\Response\MoovMoneyApiResponse;

it("should send push transaction request", function() {

    $config = new MoovMoneyAPIConfig();
    $config->setUsername('username');
    $config->setPassword('password');
    $config->setBaseUrl('http://localhost:8080');

    $responseFakeSuccess = new Response(status: 200, body: getPushTransactionResponse());
    $responseFakeFailure = new Response(status: 400, body: getResponseError());

    $client = Mockery::mock(Client::class);
    $client->shouldReceive("sendRequest")->twice()->andReturn($responseFakeSuccess, $responseFakeFailure);

    
    $sdk = new MoovMoneyAPI($config, $client);

    $response = $sdk->pushTransaction("90457142", 2000, "message");
    expect($response)->toBeInstanceOf(MoovMoneyApiResponse::class);
    expect($response->getStatusCode())->toBe(111);
    expect($response->getDescription())->toBe('description');
    expect($response->getReferenceId())->toBe('12345678');
    expect($response->getTransactionData())->toBe('tag');

    $sdk->pushTransaction("90457142", 2000, "message");

})->throws(
    ServerErrorException::class,
    '[soap:Server] : "For input string"'
);