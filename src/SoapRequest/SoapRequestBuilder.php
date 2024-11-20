<?php

namespace MoovMoney\SoapRequest;

final class SoapRequestBuilder
{
    private function buildRequest(string $body): string
    {

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

    public function buildPushTransactionRequest(
        string $token,
        int $amount,
        string $phone,
        string $message,
        string $data1 = "",
        string $data2 = "",
        int $fee = 0
    ): string {

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

    public function buildPushWithPendingRequest(
        string $token,
        int $amount,
        string $phone,
        string $message,
        string $data1 = "",
        string $data2 = "",
        int $fee = 0
    ): string {

        $data = <<<XML
            <api:PushWithPending>
                <token>{$token}</token>
                <msisdn>{$phone}</msisdn>
                <message>{$message}</message>
                <amount>{$amount}</amount>
                <externaldata1>{$data1}</externaldata1>
                <externaldata2>{$data2}</externaldata2>
                <fee>{$fee}</fee>
            </api:PushWithPending>
        XML;

        return $this->buildRequest($data);
    }

    public function buildTransactionStatusRequest(string $token, string $referenceId): string
    {

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

    public function buildTransfertFloozRequest(
        string $token,
        string $destination,
        int $amount,
        string $referenceId,
        string $walletId = "0",
        string $data = ""
    ): string {

        $data = <<<XML
                <api:transferFlooz>
                    <token>{$token}</token>
                    <request>
                        <destination>{$destination}</destination>
                        <amount>{$amount}</amount>
                        <referenceid>{$referenceId}</referenceid>
                        <walletid>{$walletId}</walletid>
                        <extendeddata>{$data}</extendeddata>
                    </request>
                </api:transferFlooz>
            XML;

        return $this->buildRequest($data);
    }

    public function buildGetBalanceRequest(string $token, string $telephone): string
    {

        $data = <<<XML
            <api:getBalance>
                <token>{$token}</token>
                <request>
                    <msisdn>{$telephone}</msisdn>
                </request>
            </api:getBalance>
        XML;

        return $this->buildRequest($data);
    }

    public function buildGetMobileStatusRequest(string $token, string $telephone): string
    {

        $data = <<<XML
            <api:getMobileAccountStatus>
                <token>{$token}</token>
                <request>
                    <msisdn>{$telephone}</msisdn>
                </request>
            </api:getMobileAccountStatus>
        XML;

        return $this->buildRequest($data);
    }

    public function buildCashInRequest(
        string $token,
        string $destination,
        int $amount,
        string $referenceId,
        string $remarks = ""
    ): string {

        $data = <<<XML
            <api:cashintrans>
                <token>{$token}</token>
                <request>
                    <amount>{$amount}</amount>
                    <destination>{$destination}</destination>
                    <referenceid>{$referenceId}</referenceid>
                    <remarks>{$remarks}</remarks>
                </request>
            </api:cashintrans>
        XML;

        return $this->buildRequest($data);
    }

    public function buildAirtimeRequest(
        string $token,
        string $destination,
        int $amount,
        string $referenceId,
        string $remarks = ""
    ): string {

        $data = <<<XML
            <api:airtimetrans>
                <token>{$token}</token>
                <request>
                    <amount>{$amount}</amount>
                    <destination>{$destination}</destination>
                    <referenceid>{$referenceId}</referenceid>
                    <remarks>{$remarks}</remarks>
                </request>
            </api:airtimetrans>
        XML;

        return $this->buildRequest($data);
    }
}
