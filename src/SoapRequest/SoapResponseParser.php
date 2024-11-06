<?php

namespace MoovMoney\SoapRequest;

use Exception;
use MoovMoney\Exceptions\ServerErrorException;
use MoovMoney\Response\MoovMoneyApiResponse;

final class SoapResponseParser
{
    public static function parse(string $body): MoovMoneyApiResponse
    {
        try {

            $xml = new \SimpleXMLElement($body);

            $response = $xml->children("soap", true)?->Body?->children("ns2", true)?->children();

            if (is_null($response)) {
                throw new Exception();
            }

            /**
             * @var array<string> $response
             */
            $response = isset($response[0]) ? (array) $response[0] : [];

            return new MoovMoneyApiResponse($response);

        } catch (Exception $e) {

            throw new ServerErrorException("Failed to read server response. Response : " . json_encode($body));
        }
    }

    public static function parseError(string $body): string
    {

        try {

            $xml = new \SimpleXMLElement($body);

            $response = $xml->children("soap", true)?->Body?->children("soap", true)?->children();

            if (is_null($response)) {
                throw new Exception();
            }

            /**
             * @var array<string, string> response
             */
            $response = (array) $response;
            $faultcode = $response["faultcode"] ?? "Error";
            $faultstring = $response["faultstring"] ?? "An error has occurred";

            return sprintf("[%s] : %s", $faultcode, json_encode($faultstring));

        } catch (Exception $e) {

            throw new ServerErrorException("Failed to read server response. Response : " . json_encode($body));
        }
    }
}
