<?php

namespace MoovMoney\Interfaces;

interface ApiResponseInterface
{
    public function get(string $key): ?string;

    /**
     * @return array<string, string>
     */
    public function toArray(): array;
}
