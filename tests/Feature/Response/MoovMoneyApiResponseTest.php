<?php

use MoovMoney\Common\ApiStatus;
use MoovMoney\Response\MoovMoneyApiResponse;


it('should return the right values (status, message, referenceId, etc)', function () {

    $data = [
        'status' => 0,
        'referenceid' => '12345678',
        'description' => 'description',
        'transid' => 'tag',

        // GetBalance

        'message' => 'message',
        'balance' => 123000,

        // TransferFlooz

        'transactionid' => '12345678',
        'senderkeycost' => 0,
        'senderbonus' => 1,
        'senderbalancebefore' => 1234,
        'senderbalanceafter' => 1200,
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

    // for getBalance response

    expect($response->getMessage())->toBe("message");
    expect($response->GetBalance->getBalance())->toBe(123000);


    // for TransferFlooz response

    expect($response->getMessage())->toBe("message");
    expect($response->TransferFlooz->getSenderBalanceBefore())->toBe(1234);
    expect($response->TransferFlooz->getSenderBalanceAfter())->toBe(1200);
    expect($response->TransferFlooz->getSenderBonus())->toBe(1);
    expect($response->TransferFlooz->getSenderKeyCost())->toBe(0);
    expect($response->TransferFlooz->getTransactionID())->toBe('12345678');

});