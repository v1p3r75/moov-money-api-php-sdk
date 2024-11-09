<?php

use MoovMoney\Common\ApiStatus;
use MoovMoney\Response\MoovMoneyApiResponse;


it('should return the right values (status, message, referenceId, etc)', function () {

    $data = [
        'status' => 2,
        'referenceid' => '12345678',
        'description' => 'description',
        'transid' => 'tag',
        'message' => 'message',
        'balance' => 123000
    ];

    $response = new MoovMoneyApiResponse($data);
    $apiStatus = new ApiStatus();

    expect($response->getStatusCode())->toBe(2);
    expect($response->getReferenceId())->toBe("12345678");
    expect($response->getDescription())->toBe("description");
    expect($response->getTransactionData())->toBe("tag");
    expect($response->get("transid"))->toBe("tag");
    expect($response->get("other"))->toBeNull();
    expect($response->getLongDescription())->toBe($apiStatus->getLongDescription(2));

    // for getBalance response

    expect($response->GetBalance->getMessage())->toBe("message");
    expect($response->GetBalance->getBalance())->toBe(123000);

    expect($response->toArray())->toBeArray()->toBe($data);
});