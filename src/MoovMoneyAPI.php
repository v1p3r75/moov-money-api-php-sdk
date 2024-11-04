<?php

namespace MoovMoney;

use MoovMoney\Interfaces\ConfigInterface;

class MoovMoneyAPI
{

    public function __construct(private ConfigInterface $config) {}

    public function pushTransaction(
        string $telephone,
        int $amount,
        string $externalData1 = "",
        string $externalData2 = "") {

    }
}