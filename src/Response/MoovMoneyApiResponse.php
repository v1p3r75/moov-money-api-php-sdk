<?php

namespace MoovMoney\Response;

use MoovMoney\Common\ApiStatus;

final class MoovMoneyApiResponse
{
    private ApiStatus $apiStatus;


    /**
     * @param array<string> $result
     */
    public function __construct(private array $result)
    {
        $this->apiStatus = new ApiStatus();
    }

    public function getStatusCode(): int
    {

        return (int) $this->get('status');
    }

    public function getReferenceId(): string | null
    {

        return $this->get('referenceid');
    }

    public function getDescription(): string | null
    {

        return $this->get('description');
    }

    public function getTransactionData(): string | null
    {

        return $this->get('transid');
    }

    public function getLongDescription(): string
    {

        return $this->apiStatus->getLongDescription((string) $this->getStatusCode());

    }


    /**
     * @return array<string>
     */
    public function toArray(): array
    {

        return $this->result;
    }

    public function get(string $key): string | null
    {

        return isset($this->result[$key]) ? (string) $this->result[$key] : null;
    }
}
