<?php

namespace MoovMoney\Response;

use MoovMoney\Interfaces\ApiResponseInterface;

class GetBalanceResponse implements ApiResponseInterface
{
    /**
     * Constructs the GetBalanceResponse instance.
     *
     * @param array<string> $response The response data from the API, provided as an associative array.
     */
    public function __construct(private array $response)
    {

    }

    /**
     * Gets the balance of the API response.
     *
     * @return int The balance as an integer.
     */
    public function getBalance(): int
    {
        return (int) $this->get('balance');
    }

    /**
     * Converts the API response data to an associative array.
     *
     * @return array<string> The response data as an array.
     */
    public function toArray(): array
    {
        return $this->response;
    }

    /**
     * Gets a specific value from the response data by key.
     *
     * @param string $key The key to retrieve from the response data.
     * @return string|null The value associated with the key, or null if the key does not exist.
     */
    public function get(string $key): ?string
    {
        return isset($this->response[$key]) ? (string) $this->response[$key] : null;
    }
}
