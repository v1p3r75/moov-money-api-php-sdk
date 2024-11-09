<?php

namespace MoovMoney\Interfaces;

interface ConfigurationInterface
{
    public function getUsername(): string;

    public function getPassword(): string;

    public function getBaseUrl(): string;

    public function getEncryptionKey(): string;

    public function getRequestTimeout(): float;

    public function isValid(): bool;
}
