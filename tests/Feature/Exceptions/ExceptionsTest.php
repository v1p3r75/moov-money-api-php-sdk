<?php

use MoovMoney\Exceptions\BadConfigurationException;
use MoovMoney\Exceptions\ServerErrorException;

it ('should throw ServerErrorException', function () {
    throw new ServerErrorException('Server Error');
})->throws(ServerErrorException::class);

it ('should throw BadConfigurationException', function () {
    throw new BadConfigurationException('Config Error');
})->throws(BadConfigurationException::class);