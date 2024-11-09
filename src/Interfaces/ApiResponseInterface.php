<?php

namespace MoovMoney\Interfaces;

interface ApiResponseInterface
{
    public function get(string $key): ?string;

    public function toArray(): array;
}
