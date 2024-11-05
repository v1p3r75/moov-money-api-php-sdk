<?php

use MoovMoney\MoovMoneyAPIConfig;

it('should return the username', function () {

    $config = new MoovMoneyAPIConfig();
    $config->setUsername('username');

    expect($config->getUsername())->toBe('username');
});

it('should return the password', function () {

    $config = new MoovMoneyAPIConfig();
    $config->setUsername('password');

    expect($config->getUsername())->toBe('password');
});

it('should return the base url', function () {

    $config = new MoovMoneyAPIConfig();
    $config->setUsername('http://localhost:8080');

    expect($config->getUsername())->toBe('http://localhost:8080');
});

it('should return the encryption key', function () {

    $config = new MoovMoneyAPIConfig();
    $config->setEncryptionKey('key');

    expect($config->getEncryptionKey())->toBe('key');
});
