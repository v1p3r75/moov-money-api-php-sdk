<?php

namespace MoovMoney\Request;

use GuzzleHttp\Psr7\Request;
use MoovMoney\Exceptions\ServerErrorException;
use MoovMoney\Response\MoovMoneyApiResponse;
use MoovMoney\SoapRequest\SoapResponseParser;
use Psr\Http\Client\ClientInterface;

final class HttpRequest
{

    private const HTTP_CODE_ERROR = 400;

    public function __construct(private ClientInterface $client)
    {
    }

    /**
    * Sends a prepared SOAP request to the Moov Money API.
    *
    * @param string $body The SOAP request body.
    * @return MoovMoneyApiResponse The response object containing transaction or error details.
    */
    public function post(string $body): MoovMoneyApiResponse
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

        return $this->parseResponse(
            $response->getStatusCode(),
            $responseBody
        );
    }

    /**
    * Parse http response body.
    *
    * @param int $statusCode The http status code.
    * @param int $responseBody The SOAP request body.
    * @return MoovMoneyApiResponse The response object containing transaction or error details.
    */
    private function parseResponse(int $statusCode, string $responseBody): MoovMoneyApiResponse
    {

        if ($statusCode >= self::HTTP_CODE_ERROR) { // if request failed

            $message = SoapResponseParser::parseError($responseBody);
            throw new ServerErrorException($message);
        }

        $data = SoapResponseParser::parse($responseBody);

        return $data;
    }
}