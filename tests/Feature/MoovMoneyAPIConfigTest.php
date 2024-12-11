<?php

use MoovMoney\MoovMoneyAPIConfig;

it('should return the configuration values (username, password, etc)', function () {

    $config = new MoovMoneyAPIConfig();
    $config->setUsername('username');
    $config->setPassword('password');
    $config->setBaseUrl('http://localhost:8080');
    $config->setRequestTimeout(10);

    expect($config->getEncryptionKey())->toBe('tlc12345tlc12345tlc12345tlc12345');

    $config->setEncryptionKey('key');

    expect($config->getUsername())->toBe('username');
    expect($config->getPassword())->toBe('password');
    expect($config->getBaseUrl())->toBe('http://localhost:8080');
    expect($config->getEncryptionKey())->toBe('key');
    expect($config->getRequestTimeout())->ToBe((float)10);
    
});

it('should check if configuration is valid or not', function () {

    $config = new MoovMoneyAPIConfig();

    expect($config->isValid())->toBeFalse();
    
    $config = new MoovMoneyAPIConfig();
    $config->setUsername('username');
    $config->setPassword('password');
    $config->setBaseUrl('http://localhost:8080');

    expect($config->isValid())->toBeTrue();
    
});

it('should manage environment mode', function() {
    
    $config = new MoovMoneyAPIConfig();
    expect($config->isSandbox())->toBeTrue();
    expect($config->getBaseUrl())->toBe($config::API_SANDBOX_URL);


    $config->useSandbox(false);
    expect($config->isSandbox())->toBeFalse();
    expect($config->getBaseUrl())->toBe($config::API_PRODUCTION_URL);

    $newConfig = new MoovMoneyAPIConfig();
    $newConfig->setBaseUrl('base_url');
    expect($newConfig->getBaseUrl())->toBe("base_url");
});