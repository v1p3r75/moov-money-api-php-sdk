<?php

use MoovMoney\MoovMoneyAPIConfig;
use MoovMoney\Security\Encryption;

it('should throw exception for encryption key length', function () {

    $config = new MoovMoneyAPIConfig();
    $config->setUsername('username');
    $config->setPassword('password');
    $config->setEncryptionKey('encryption_key');

    $encryption = new Encryption($config);

})->throws(InvalidArgumentException::class);


it('should return the encryption token', function () {

    $config = new MoovMoneyAPIConfig();
    $config->setUsername('username');
    $config->setPassword('password');
    $config->setEncryptionKey('tlc12345tlc12345tlc12345tlc12345');

    $encryption = new Encryption($config);

    $token = "K05kbDVZOU5IdlNvbXc3akxubFJHNEdIamYvYU8wL1BIL0h6dm94WjJUaz0=";

    expect($encryption->getToken())->toBe($token);
    expect($encryption->hasError())->toBeFalse();

});
