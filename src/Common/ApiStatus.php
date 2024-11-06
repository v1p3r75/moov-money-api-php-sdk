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

    public function getLongDescription(string $status): string
    {

        return $this->status[$status] ?? "";

    }

}
