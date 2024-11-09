<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

// pest()->extend(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('_toBeBody', function ($body) {

    $base = <<<XML
        <?xml version="1.0" encoding="utf-16"?>
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:api="http://api.merchant.tlc.com/">
            <soapenv:Header/>
            <soapenv:Body>
                {$body}
            </soapenv:Body>
        </soapenv:Envelope>
        XML;

    return $this->toBe($base);

});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function buildFakeResponse($body): string {

    $data = <<<XML
        <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Body>
            {$body}
        </soap:Body>
    </soap:Envelope>
    XML;

    return $data;
}

// ------- Push Transaction --------------------------

function getPushTransactionResponse(): string
{
    $data = <<<XML
        <ns2:Push xmlns:ns2="http://api.merchant.tlc.com/">
            <result>
                <description>description</description>
                <referenceid>12345678</referenceid>
                <status>111</status>
                <transid>tag</transid>
            </result>
        </ns2:Push>
    XML;

    return buildFakeResponse($data);
}
// ------- End - Push Transaction --------------------------


// ------- Push With PendingTransaction --------------------------

function getPushWithPendingSuccessResponse(): string
{
    $data = <<<XML
        <ns2:PushWithPendingResponse xmlns:ns2="http://api.merchant.tlc.com/">
            <result>
                <description>description</description>
                <referenceid>12345678</referenceid>
                <status>111</status>
                <transid>tag</transid>
            </result>
        </ns2:PushWithPendingResponse>
    XML;

    return buildFakeResponse($data);
}

function getPushWithPendingResponse(): string
{
    $data = <<<XML
        <ns2:PushWithPendingResponse xmlns:ns2="http://api.merchant.tlc.com/">
            <result>
                <description>pending</description>
                <status>100</status>
            </result>
        </ns2:PushWithPendingResponse>
    XML;

    return buildFakeResponse($data);
}

// ------- End Push With PendingTransaction --------------------------


// ------- Transaction Status  --------------------------

function getTransactionStatusResponse(): string {

    $data = <<<XML
        <ns2:getTransactionStatusResponse xmlns:ns2="http://api.merchant.tlc.com/">
            <response>
                <description>SUCCESS</description>
                <referenceid>12345678</referenceid>
                <status>0</status>
            </response>
        </ns2:getTransactionStatusResponse>
    XML;
    
    return buildFakeResponse($data);

}

// ------- End Transaction Status  --------------------------

// ------- GetBalance  --------------------------

function getGetBalanceResponse(): string {

    $data = <<<XML
        <ns2:getBalanceResponse xmlns:ns2="http://api.merchant.tlc.com/">
            <return>
                <balance>382222</balance>
                <message>message</message>
                <status>0</status>
            </return>
        </ns2:getBalanceResponse>
    XML;
    
    return buildFakeResponse($data);

}

// ------- End GetBalane  --------------------------


// ------- TransferFlooz  --------------------------

function getTransferFloozResponse(): string {

    $data = <<<XML
        <ns2:transferFloozResponse xmlns:ns2="http://api.merchant.tlc.com/">
            <return>
                <transactionid>1234567890</transactionid>
                <status>0</status>
                <message>message</message>
                <referenceid>920190616000000</referenceid>
                <senderkeycost/>
                <senderbonus/>
                <senderbalancebefore>16819</senderbalancebefore>
                <senderbalanceafter>16809</senderbalanceafter>
            </return>
        </ns2:transferFloozResponse>
    XML;
    
    return buildFakeResponse($data);

}

// ------- End TransferFlooz  --------------------------

function getResponseError(): string {
    $data = <<<XML
        <soap:Fault>
            <faultcode>soap:Server</faultcode>
            <faultstring>For input string</faultstring>
            <detail>
                <ns1:Exception xmlns:ns1="http://api.merchant.tlc.com/"/>
            </detail>
        </soap:Fault>
    XML;

    return buildFakeResponse($data);

}