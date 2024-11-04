<?php

namespace MoovMoney;

use MoovMoney\Interfaces\ConfigInterface;

class MoovMoneyAPIConfig implements ConfigInterface
{

    private string $username;
    
    private string $password;


    private string $baseUrl;

    public function getUsername(): string {

        return $this->username;
    }

    public function setUsername(string $username): self {

        $this->username = $username;
        return $this;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password): self {

        $this->password = $password;
        return $this;
    }

    public function getBaseUrl(): string {
        return $this->baseUrl;
    }

    public function setBaseUrl(string $baseUrl): self {

        $this->baseUrl = $baseUrl;
        return $this;
    }
}
