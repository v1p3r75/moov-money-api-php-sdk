<?php

namespace MoovMoney\Security;

use MoovMoney\Interfaces\ConfigurationInterface;

class Encryption
{
    private ?string $token = null;

    private bool $isFailed = false;


    public function __construct(private ConfigurationInterface $config)
    {

        $this->generateToken();
    }


    private function generateToken(): void
    {

        $plaintext = sprintf("0:%s:%s", $this->config->getUsername(), $this->config->getPassword());
        $key = $this->config->getEncryptionKey();

        if (!$this->isKeyLengthValid($key)) {

            throw new \InvalidArgumentException("Secret key's length must be 128, 192 or 256 bits");
        }

        $cipher = "AES-256-CBC";
        $iv = substr(hash('sha256', $key), 0, 16);
        $encrypted = openssl_encrypt($plaintext, $cipher, $key, 0, $iv);

        if (!$encrypted) {

            $this->isFailed = true;
            return;
        }

        $this->token = base64_encode($encrypted);
    }

    public function getToken(): string|null
    {

        return $this->token;
    }

    public function hasError(): bool
    {

        return $this->isFailed;
    }

    private function isKeyLengthValid(string $key): bool
    {

        $len = strlen($key);
        return $len === 16 || $len === 24 || $len === 32;
    }
}
