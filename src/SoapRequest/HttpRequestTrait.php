<?php

namespace MoovMoney\SoapRequest;

use GuzzleHttp\Psr7\Request;
use MoovMoney\Exceptions\ServerErrorException;
use MoovMoney\Response\MoovMoneyApiResponse;
use Psr\Http\Client\ClientInterface;

trait HttpRequestTrait
{
    private function sendRequest(ClientInterface $client, string $body): MoovMoneyApiResponse
    {

        $request = new Request(
            method: "POST",
            uri: "",
            headers: [
                'Content-Type' => 'application/soap+xml; charset=utf-8'
            ],
            body: $body,
        );

        $response = $client->sendRequest($request);
        $responseBody = $response->getBody()->getContents();

        if ($response->getStatusCode() >= 400) { // if request failed

            $message = SoapResponseParser::parseError($responseBody);
            throw new ServerErrorException($message);
        }

        $data = SoapResponseParser::parse($responseBody);

        return $data;
    }
}
