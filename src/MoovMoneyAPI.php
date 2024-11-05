<?php

namespace MoovMoney;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use MoovMoney\Exceptions\ServerErrorException;
use MoovMoney\Interfaces\ConfigurationInterface;
use MoovMoney\Response\MoovMoneyApiResponse;
use MoovMoney\Security\Encryption;
use MoovMoney\SoapRequest\SoapRequestBuilder;
use MoovMoney\SoapRequest\SoapResponseParser;
use Psr\Http\Client\ClientInterface;

final class MoovMoneyAPI
{
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

        return $this->sendRequest($body);

    }

    private function sendRequest(string $body): MoovMoneyApiResponse
    {

        $request = new Request(
            method: "POST",
            uri: "",
            headers: [
                'Content-Type' => 'application/soap+xml; charset=utf-8'
            ],
            body: $body,
        );

        $response = $this->client->sendRequest($request);
        $responseBody = $response->getBody()->getContents();

        if ($response->getStatusCode() >= 400) { // if request failed

            $message = SoapResponseParser::parseError($responseBody);
            throw new ServerErrorException($message);
        }

        $data = SoapResponseParser::parse($responseBody);

        return $data;

    }
}
