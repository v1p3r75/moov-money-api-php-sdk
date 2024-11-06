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

it("should send push with pending transaction request", function() {

    $config = new MoovMoneyAPIConfig();
    $config->setUsername('username');
    $config->setPassword('password');
    $config->setBaseUrl('http://localhost:8080');

    $responseFakeSuccess = new Response(status: 200, body: getPushWithPendingSuccessResponse());
    $responseFakePending = new Response(status: 200, body: getPushWithPendingResponse());

    $client = Mockery::mock(Client::class);
    $client->shouldReceive("sendRequest")->twice()->andReturn($responseFakeSuccess, $responseFakePending);

    
    $sdk = new MoovMoneyAPI($config, $client);

    $response = $sdk->pushWithPendingTransaction("90457142", 2000, "message");
    expect($response)->toBeInstanceOf(MoovMoneyApiResponse::class);
    expect($response->getStatusCode())->toBe(111);
    expect($response->getDescription())->toBe('description');
    expect($response->getReferenceId())->toBe('12345678');
    expect($response->getTransactionData())->toBe('tag');

    $response = $sdk->pushWithPendingTransaction("90457142", 2000, "message");
    expect($response)->toBeInstanceOf(MoovMoneyApiResponse::class);
    expect($response->getStatusCode())->toBe(100);
    expect($response->getDescription())->toBe('pending');
    expect($response->getLongDescription())->toBe('In pending state');
    expect($response->getReferenceId())->toBeNull(); // no reference id if request is pending
    expect($response->getTransactionData())->toBeNull(); // no transaction data if request is pending

});


it("should check a transaction status", function() {

    $config = new MoovMoneyAPIConfig();
    $config->setUsername('username');
    $config->setPassword('password');
    $config->setBaseUrl('http://localhost:8080');

    $responseFakeSuccess = new Response(status: 200, body: getTransactionStatusResponse());

    $client = Mockery::mock(Client::class);
    $client->shouldReceive("sendRequest")->once()->andReturn($responseFakeSuccess);

    
    $sdk = new MoovMoneyAPI($config, $client);

    $response = $sdk->getTransactionStatus("12345678");
    expect($response)->toBeInstanceOf(MoovMoneyApiResponse::class);
    expect($response->getStatusCode())->toBe(0);
    expect($response->getDescription())->toBe('SUCCESS');
    expect($response->getReferenceId())->toBe('12345678');
});