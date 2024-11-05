<?php

use MoovMoney\Common\ApiStatus;

it("should return the correct long description", function() {

    $apiStatus = new ApiStatus();
    expect($apiStatus->getLongDescription(0))->toBe("Transaction Completed");

});