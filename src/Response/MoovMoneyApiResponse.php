<?php

namespace MoovMoney\Response;

use MoovMoney\Common\ApiStatus;

/**
 * MoovMoneyApiResponse encapsulates the response data from the Moov Money API.
 * Provides methods to access status, reference ID, transaction data, and descriptions for easy handling of API responses.
 */
final class MoovMoneyApiResponse
{
    private ApiStatus $apiStatus;

    /**
     * Constructs the MoovMoneyApiResponse instance.
     *
     * @param array<string> $result The response data from the API, provided as an associative array.
     */
    public function __construct(private array $result)
    {
        $this->apiStatus = new ApiStatus();
    }

    /**
     * Gets the status code of the API response.
     *
     * @return int The status code as an integer.
     */
    public function getStatusCode(): int
    {
        return (int) $this->get('status');
    }

    /**
     * Gets the unique reference ID of the transaction.
     *
     * @return string|null The reference ID or null if not present.
     */
    public function getReferenceId(): string | null
    {
        return $this->get('referenceid');
    }

    /**
     * Gets a brief description of the response or error.
     *
     * @return string|null The description or null if not available.
     */
    public function getDescription(): string | null
    {
        return $this->get('description');
    }

    /**
     * Gets the transaction identifier, typically provided by the API.
     *
     * @return string|null The transaction ID or null if not present.
     */
    public function getTransactionData(): string | null
    {
        return $this->get('transid');
    }

    /**
     * Gets a detailed description of the status code.
     *
     * @return string A longer, more descriptive message based on the status code.
     */
    public function getLongDescription(): string
    {
        return $this->apiStatus->getLongDescription((string) $this->getStatusCode());
    }

    /**
     * Converts the API response data to an associative array.
     *
     * @return array<string> The response data as an array.
     */
    public function toArray(): array
    {
        return $this->result;
    }

    /**
     * Gets a specific value from the response data by key.
     *
     * @param string $key The key to retrieve from the response data.
     * @return string|null The value associated with the key, or null if the key does not exist.
     */
    public function get(string $key): string | null
    {
        return isset($this->result[$key]) ? (string) $this->result[$key] : null;
    }
}
