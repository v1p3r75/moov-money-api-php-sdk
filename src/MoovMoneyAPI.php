<?php

namespace MoovMoney;

use GuzzleHttp\Client;
use MoovMoney\Interfaces\ConfigurationInterface;
use MoovMoney\Interfaces\HttpClientInterface;
use Psr\Http\Client\ClientInterface;

class MoovMoneyAPI
{

    public function __construct(
        private ConfigurationInterface $config,
        private ?ClientInterface $client = null
    ) {

        $this->client = $client ?? new Client([
            'base_uri' => $config->getBaseUrl(),
        ]);
    }

    public function pushTransaction(
        string $telephone,
        int $amount,
        string $externalData1 = "",
        string $externalData2 = "") {

    }
}