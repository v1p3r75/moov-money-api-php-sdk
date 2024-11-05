<?php

namespace MoovMoney\SoapRequest;

class SoapRequestBuilder
{

    private function buildRequest(string $body): string {

        return <<<XML
        <?xml version="1.0" encoding="utf-16"?>
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:api="http://api.merchant.tlc.com/">
            <soapenv:Header/>
            <soapenv:Body>
                {$body}
            </soapenv:Body>
        </soapenv:Envelope>
        XML;
    }

    public function buildPushTransactionRequest(string $token, int $amount, string $phone, string $message, string $data1 = "", string $data2 = "", int $fee = 0): string
    {
        $data = <<<XML
            <api:Push>
                <token>{$token}</token>
                <msisdn>{$phone}</msisdn>
                <message>{$message}</message>
                <amount>{$amount}</amount>
                <externaldata1>{$data1}</externaldata1>
                <externaldata2>{$data2}</externaldata2>
                <fee>{$fee}</fee>
            </api:Push>
        XML;

        return $this->buildRequest($data);

    }

    public function buildPushWithPendingRequest(string $token, int $amount, string $phone, string $message, string $data1 = "", string $data2 = "", int $fee = 0): string
    {
        $data = <<<XML
            <api:PushWithPending>
                <token>{$token}</token>
                <msisdn>{$phone}</msisdn>
                <message>{$message}</message>
                <amount>{$amount}</amount>
                <externaldata1>{$data1}</externaldata1>
                <externaldata2>{$data2}</externaldata2>
                <fee>{$fee}</fee>
            </api:PushWithPendi>
        XML;

        return $this->buildRequest($data);

    }

    public function buildTransactionStatusRequest(string $token ,string $referenceId): string {

        $data = <<<XML
            <api:getTransactionStatus>
                <token>{$token}</token>
                <request>
                    <transid>{$referenceId}</transid>
                </request>
            </api:getTransactionStatus>
        XML;

        return $this->buildRequest($data);

    }
}
