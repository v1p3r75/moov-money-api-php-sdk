<?php

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use MoovMoney\MoovMoneyAPI;
use MoovMoney\MoovMoneyAPIConfig;

it("should send push transaction request", function() {

    $config = new MoovMoneyAPIConfig();
    $config->setUsername('username');
    $config->setPassword('password');
    $config->setBaseUrl('http://localhost:8080');
    $config->setEncryptionKey('tlc12345tlc12345tlc12345tlc12345');

    $client = Mockery::mock(Client::class);

    $client->shouldReceive("sendRequest")->once()->andReturn("");

    $sdk = new MoovMoneyAPI($config, $client);

    $return = $sdk->pushTransaction("90457142", 2000, "message");

    var_dump($return);
});