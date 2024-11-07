<?php

namespace MoovMoney\Common;

class ApiStatus
{
    /**
     * @var array<string, string> $status
     */
    private array $status = [];

    public function __construct()
    {

        $this->status = require __DIR__ . "/data/status.php";

    }

    /**
     * Gets a detailed description of the status code.
     *
     * @return string A longer, more descriptive message based on the status code.
     */
    public function getLongDescription(string $status): string
    {

        return $this->status[$status] ?? "";

    }

}
