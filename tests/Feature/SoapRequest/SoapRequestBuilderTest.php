<?php

use MoovMoney\SoapRequest\SoapRequestBuilder;

it('should build the push transaction request', function () {

    $builder = new SoapRequestBuilder();

    $body = $builder->buildPushTransactionRequest('token', 2000, "97889900", 'message');

    $result = <<<XML
        <api:Push>
            <token>token</token>
            <msisdn>97889900</msisdn>
            <message>message</message>
            <amount>2000</amount>
            <externaldata1></externaldata1>
            <externaldata2></externaldata2>
            <fee>0</fee>
        </api:Push>
    XML;

    expect($body)->_toBeBody($result);

});

it('should build the push with pending request', function () {

    $builder = new SoapRequestBuilder();

    $body = $builder->buildPushWithPendingRequest('token', 2000, "97889900", 'message');

    $result = <<<XML
        <api:PushWithPending>
            <token>token</token>
            <msisdn>97889900</msisdn>
            <message>message</message>
            <amount>2000</amount>
            <externaldata1></externaldata1>
            <externaldata2></externaldata2>
            <fee>0</fee>
        </api:PushWithPending>
    XML;

    expect($body)->_toBeBody($result);

});

it('should build the transaction status request', function () {

    $builder = new SoapRequestBuilder();

    $body = $builder->buildTransactionStatusRequest('token', "reference");

    $result = <<<XML
        <api:getTransactionStatus>
            <token>token</token>
            <request>
                <transid>reference</transid>
            </request>
        </api:getTransactionStatus>
    XML;

    expect($body)->_toBeBody($result);

});
