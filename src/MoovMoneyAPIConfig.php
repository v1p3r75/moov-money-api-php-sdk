<?php

namespace MoovMoney;

use MoovMoney\Interfaces\ConfigurationInterface;

final class MoovMoneyAPIConfig implements ConfigurationInterface
{
    private string $username;

    private string $password;

    private string $baseUrl;

    private string $encryptionKey = "tlc12345tlc12345tlc12345tlc12345";

    private float $requestTimeout = 60;

    public function getUsername(): string
    {

        return $this->username;
    }

    public function setUsername(string $username): self
    {

        $this->username = $username;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {

        $this->password = $password;
        return $this;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function setBaseUrl(string $baseUrl): self
    {

        $this->baseUrl = $baseUrl;
        return $this;
    }

    public function getEncryptionKey(): string
    {

        return $this->encryptionKey;
    }

    public function setEncryptionKey(string $encryptionKey): self
    {

        $this->encryptionKey = $encryptionKey;
        return $this;
    }

    public function getRequestTimeout(): float
    {

        return $this->requestTimeout;
    }

    public function setRequestTimeout(float $requestTimeout): self
    {

        $this->requestTimeout = $requestTimeout;
        return $this;
    }
}
