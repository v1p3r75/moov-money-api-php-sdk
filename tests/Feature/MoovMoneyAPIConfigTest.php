<?php

use MoovMoney\MoovMoneyAPIConfig;

it('should return the configuration values (username, password, etc)', function () {

    $config = new MoovMoneyAPIConfig();
    $config->setUsername('username');
    $config->setPassword('password');
    $config->setBaseUrl('http://localhost:8080');
    $config->setEncryptionKey('key');

    expect($config->getUsername())->toBe('username');
    expect($config->getPassword())->toBe('password');
    expect($config->getBaseUrl())->toBe('http://localhost:8080');
    expect($config->getEncryptionKey())->toBe('key');
    
});