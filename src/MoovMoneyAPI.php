<?php

namespace MoovMoney;

use MoovMoney\Interfaces\ConfigurationInterface;

class MoovMoneyAPI
{

    public function __construct(private ConfigurationInterface $config) {}

    public function pushTransaction(
        string $telephone,
        int $amount,
        string $externalData1 = "",
        string $externalData2 = "") {

    }
}