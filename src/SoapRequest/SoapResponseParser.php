<?php

namespace MoovMoney\SoapRequest;

use MoovMoney\Response\MoovMoneyApiResponse;

final class SoapResponseParser
{
    public static function parse(string $body): MoovMoneyApiResponse
    {
        $xml = new \SimpleXMLElement($body);

        $response = $xml->children("soap", true)->Body->children("ns2", true)->children();

        /**
         * @var array<string> $response
         */
        $response = isset($response[0]) ? (array) $response[0] : [];

        return new MoovMoneyApiResponse($response);
    }

    public static function parseError(string $body): string
    {
        $xml = new \SimpleXMLElement($body);

        $response = $xml->children("soap", true)->Body->children("soap", true)->children();

        /**
         * @var array<string, string> response
         */
        $response = (array) $response;
        $faultcode = $response["faultcode"] ?? "Error";
        $faultstring = $response["faultstring"] ?? "An error has occurred";

        return sprintf("[%s] : %s", $faultcode, json_encode($faultstring));
    }
}
