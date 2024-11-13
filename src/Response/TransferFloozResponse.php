<?php

namespace MoovMoney\Response;

use MoovMoney\Interfaces\ApiResponseInterface;

class TransferFloozResponse implements ApiResponseInterface
{
    /**
     * Constructs the TransferFloozResponse instance.
     *
     * @param array<string> $response The response data from the API, provided as an associative array.
     */
    public function __construct(private array $response)
    {
    }


    /**
     * Gets the transaction identifier, typically provided by Merchant (REFID).
     *
     * @return string|null The transaction ID or null if not present.
     */
    public function getTransactionID(): string | null
    {
        return $this->get('transactionid');
    }


    /**
     * Gets the sender key cost of the API response.
     *
     * @return int The sender key cost as an integer.
     */
    public function getSenderKeyCost(): int
    {
        return (int) $this->get('senderkeycost');
    }

    /**
     * Gets the sender bonus of the API response.
     *
     * @return int The sender bonus as an integer.
     */
    public function getSenderBonus(): int
    {
        return (int) $this->get('senderbonus');
    }

    /**
     * Gets the sender balance before of the API response.
     *
     * @return int The sender balance before as an integer.
     */
    public function getSenderBalanceBefore(): int
    {
        return (int) $this->get('senderbalancebefore');
    }

    /**
     * Gets the sender balance after of the API response.
     *
     * @return int The sender balance after as an integer.
     */
    public function getSenderBalanceAfter(): int
    {
        return (int) $this->get('senderbalanceafter');
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
