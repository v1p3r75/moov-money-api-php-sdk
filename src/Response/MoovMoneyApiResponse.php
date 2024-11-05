<?php

namespace MoovMoney\Response;

final class MoovMoneyApiResponse
{
    /**
     * @param array<string> $result
     */
    public function __construct(private array $result)
    {
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
