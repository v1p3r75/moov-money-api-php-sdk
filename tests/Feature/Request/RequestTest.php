<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MoovMoney\Request\HttpRequest;
use Psr\Http\Message\ResponseInterface;

it('should send a http request post', function() {

    $mock = new MockHandler([
        new Response(status: 200, body: "response body")
    ]);
    
    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);

    $request = new HttpRequest($client);
    $response = $request->post("body");

    expect($response)->toBeInstanceOf(ResponseInterface::class);
    expect($response->getBody()->getContents())->toBe("response body");

});