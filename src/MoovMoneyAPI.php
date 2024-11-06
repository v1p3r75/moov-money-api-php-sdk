<?php

namespace MoovMoney;

use GuzzleHttp\Client;
use MoovMoney\Interfaces\ConfigurationInterface;
use MoovMoney\Response\MoovMoneyApiResponse;
use MoovMoney\Security\Encryption;
use MoovMoney\SoapRequest\HttpRequestTrait;
use MoovMoney\SoapRequest\SoapRequestBuilder;
use Psr\Http\Client\ClientInterface;

final class MoovMoneyAPI
{
    use HttpRequestTrait;

    private SoapRequestBuilder $builder;

    private Encryption $encryption;

    public function __construct(private ConfigurationInterface $config, private ?ClientInterface $client = null)
    {

        $this->client = $client ?? new Client([
            'base_uri' => $config->getBaseUrl(),
            'timeout' => $config->getRequestTimeout(),
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
        int $fee = 0
    ): MoovMoneyApiResponse {

        $body = $this->builder->buildPushTransactionRequest(
            token: $this->encryption->getToken(),
            amount: $amount,
            phone: $telephone,
            message: $message,
            data1: $data1,
            data2: $data2,
            fee: $fee
        );

        return $this->request($body);

    }

    public function pushWithPendingTransaction(
        string $telephone,
        int $amount,
        string $message,
        string $data1 = "",
        string $data2 = "",
        int $fee = 0
    ): MoovMoneyApiResponse {

        $body = $this->builder->buildPushWithPendingRequest(
            token: $this->encryption->getToken(),
            amount: $amount,
            phone: $telephone,
            message: $message,
            data1: $data1,
            data2: $data2,
            fee: $fee
        );

        return $this->request($body);

    }

    private function request(string $body): MoovMoneyApiResponse
    {

        return $this->sendRequest($this->client, $body);

    }
}
