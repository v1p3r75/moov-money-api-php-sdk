<?php

use MoovMoney\Response\MoovMoneyApiResponse;
use MoovMoney\SoapRequest\SoapResponseParser;

it ('should correctly parse the response body and return a MoovMoneyApiResponse class', function() {
    
    $data = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
    <soap:Body>
        <ns2:PushWithPendingResponse xmlns:ns2="http://api.merchant.tlc.com/">
            <return>
                <description>UNAUTHORISED_ERROR</description>
                <referenceid>72024103000000009</referenceid>
                <status>4</status>
                <transid>pi_NyM_1642619082990</transid>
            </return>
        </ns2:PushWithPendingResponse>
    </soap:Body>
</soap:Envelope>';

    $response = SoapResponseParser::parse($data);

    expect($response)->toBeInstanceOf(MoovMoneyApiResponse::class);
    expect($response->getStatusCode())->toBeInt()->toBe(4);
    expect($response->getReferenceId())->toBe("72024103000000009");
    expect($response->getDescription())->toBeString()->toBe("UNAUTHORISED_ERROR");
    expect($response->getTransactionData())->toBeString()->toBe('pi_NyM_1642619082990');

    $data = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
        <soap:Body>
            <ns2:PushWithPendingResponse xmlns:ns2="http://api.merchant.tlc.com/">
                <return>
                </return>
            </ns2:PushWithPendingResponse>
        </soap:Body>
    </soap:Envelope>';

    $response = SoapResponseParser::parse($data);

    expect($response)->toBeInstanceOf(MoovMoneyApiResponse::class);
    expect($response->getReferenceId())->toBeNull();
    expect($response->getDescription())->toBeNull();
    expect($response->getTransactionData())->toBeNull();


});

it ('should return the server error code and message', function() {
    
    $data = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
    <soap:Body>
        <soap:Fault>
            <faultcode>soap:Server</faultcode>
            <faultstring>For input string</faultstring>
            <detail>
                <ns1:Exception xmlns:ns1="http://api.merchant.tlc.com/"/>
            </detail>
        </soap:Fault>
    </soap:Body>
</soap:Envelope>';

    $data2 = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
    <soap:Body>
        <soap:Fault>
        </soap:Fault>
    </soap:Body>
</soap:Envelope>';

    $response = SoapResponseParser::parseError($data2);

    expect($response)->toBeString->toBe('[Error] : "An error has occurred"');

});