<?php

namespace MoovMoney;

use GuzzleHttp\Client;
use MoovMoney\Interfaces\ConfigurationInterface;
use MoovMoney\Security\Encryption;
use MoovMoney\SoapRequest\SoapRequestBuilder;
use Psr\Http\Client\ClientInterface;

final class MoovMoneyAPI
{

    private SoapRequestBuilder $builder;

    private Encryption $encryption;

    public function __construct(private ConfigurationInterface $config, private ?ClientInterface $client = null)
    {

        $this->client = $client ?? new Client([
            'base_uri' => $config->getBaseUrl(),
        ]);

        $this->encryption = new Encryption($config);

        $this->builder = new SoapRequestBuilder();

    }

    public function pushTransaction(
        string $telephone,
        int $amount,
        string $message,
        string $data1 = "",
        string $data2 = "",
        int $fee = 0,
    ) 
    {
        $body = $this->builder->buildPushTransactionRequest(
$this->encryption->getToken(),
            $amount,
            $telephone,
            $message,
            $data1,
            $data2,
            $fee
        );

        return $body;

    }
}
