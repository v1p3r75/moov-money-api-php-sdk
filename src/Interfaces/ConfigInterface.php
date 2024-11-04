<?php

namespace MoovMoney\Interfaces;

interface ConfigInterface
{
    public function getUsername(): string;

    public function getPassword(): string;

    public function getBaseUrl(): string;
}
