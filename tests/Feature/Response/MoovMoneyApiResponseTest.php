<?php

use MoovMoney\Common\ApiStatus;
use MoovMoney\Response\MoovMoneyApiResponse;


it('should return the correct response values (main)', function () {

    $data = [
        'status' => 0,
        'referenceid' => '12345678',
        'description' => 'description',
        'transid' => 'tag',
    ];

    $response = new MoovMoneyApiResponse($data);
    $apiStatus = new ApiStatus();

    expect($response->getStatusCode())->toBe(0);
    expect($response->getReferenceId())->toBe("12345678");
    expect($response->getDescription())->toBe("description");
    expect($response->getTransactionData())->toBe("tag");
    expect($response->get("transid"))->toBe("tag");
    expect($response->get("other"))->toBeNull();
    expect($response->getLongDescription())->toBe($apiStatus->getLongDescription(0));
    expect($response->isSuccess())->toBeTrue();
    expect($response->toArray())->toBeArray()->toBe($data);

});

it('should return the correct response values (GetBalance)', function() {

    $data = [
        'message' => 'message',
        'balance' => 123000,
    ];

    $response = new MoovMoneyApiResponse($data);

    expect($response->getMessage())->toBe("message");
    expect($response->GetBalance->getBalance())->toBe(123000);
    expect($response->GetBalance->toArray())->toBe($data);

});

it('should return the correct response values (TransferFlooz)', function() {

    $data = [
        'transactionid' => '12345678',
        'senderkeycost' => 0,
        'senderbonus' => 1,
        'senderbalancebefore' => 1234,
        'senderbalanceafter' => 1200,
    ];

    $response = new MoovMoneyApiResponse($data);
    
    expect($response->TransferFlooz->getSenderBalanceBefore())->toBe(1234);
    expect($response->TransferFlooz->getSenderBalanceAfter())->toBe(1200);
    expect($response->TransferFlooz->getSenderBonus())->toBe(1);
    expect($response->TransferFlooz->getSenderKeyCost())->toBe(0);
    expect($response->TransferFlooz->getTransactionID())->toBe('12345678');
    expect($response->TransferFlooz->toArray())->toBe($data);
});

it('should return the correct response values (GetMobileStatus)', function() {

    $data = [
        "accounttype" => "MCOM",
        "allowedtransfer" => "0",
        "city" => "COTONOU",
        "dateofbirth" => "1987-11-01 00:00:00.0",
        "firstname" => "REUGIE",
        "lastname" => "ARIZALA",
        "message" => "SUCCESS",
        "msisdn" => "22994512412",
        "region" => "ATLANTIQUE",
        "secondname" => "",
        "status" => "0",
        "street" => "ST JEAN I (MINFFONGOU)",
        "subscriberstatus" => "ACTIVE"
    ];
    

    $response = new MoovMoneyApiResponse($data);
    
    expect($response->GetMobileStatus->getAccountType())->toBe("MCOM");
    expect($response->GetMobileStatus->getAllowedTransfer())->toBe(0);
    expect($response->GetMobileStatus->getCity())->toBe("COTONOU");
    expect($response->GetMobileStatus->getRegion())->toBe("ATLANTIQUE");
    expect($response->GetMobileStatus->getDateOfBirth())->toBe("1987-11-01 00:00:00.0");
    expect($response->GetMobileStatus->getLastName())->toBe("ARIZALA");
    expect($response->GetMobileStatus->getFirstName())->toBe("REUGIE");
    expect($response->GetMobileStatus->getSecondName())->toBe("");
    expect($response->GetMobileStatus->getStreet())->toBe("ST JEAN I (MINFFONGOU)");
    expect($response->GetMobileStatus->getSubscriberStatus())->toBe("ACTIVE");
    expect($response->GetMobileStatus->getTelephone())->toBe("22994512412");
});