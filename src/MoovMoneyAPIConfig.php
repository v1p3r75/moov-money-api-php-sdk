<?php

namespace MoovMoney;

use MoovMoney\Interfaces\ConfigurationInterface;

/**
 * MoovMoneyAPIConfig provides configuration settings for the Moov Money API.
 * This class includes credentials and settings required to establish a secure connection with the API.
 */
final class MoovMoneyAPIConfig implements ConfigurationInterface
{
    private string $username;

    private string $password;

    private string $baseUrl;

    private string $encryptionKey = "tlc12345tlc12345tlc12345tlc12345";

    private float $requestTimeout = 60;

    /**
     * Gets the configured username for API authentication.
     *
     * @return string The username.
     */
    public function getUsername(): string
    {

        return $this->username;
    }

    /**
     * Sets the username for API authentication.
     *
     * @param string $username The username provided by Moov Money.
     * @return self
     */
    public function setUsername(string $username): self
    {

        $this->username = $username;
        return $this;
    }

    /**
     * Gets the configured password for API authentication.
     *
     * @return string The password.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
    * Sets the password for API authentication.
    *
    * @param string $password The password provided by Moov Money.
    * @return self
    */
    public function setPassword(string $password): self
    {

        $this->password = $password;
        return $this;
    }

    /**
    * Gets the base URL for the Moov Money API.
    *
    * @return string The base URL.
    */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Sets the base URL for the Moov Money API.
     *
     * @param string $baseUrl The base URL of the API.
     * @return self
     */
    public function setBaseUrl(string $baseUrl): self
    {

        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * Gets the encryption key used for generating tokens.
     *
     * @return string The encryption key.
     */
    public function getEncryptionKey(): string
    {
        return $this->encryptionKey;
    }

    /**
     * Sets the encryption key for generating tokens.
     *
     * @param string $encryptionKey The encryption key (32 characters for AES-256).
     * @return self
     */
    public function setEncryptionKey(string $encryptionKey): self
    {
        $this->encryptionKey = $encryptionKey;
        return $this;
    }

    /**
     * Gets the request timeout duration.
     *
     * @return float The timeout in seconds.
     */
    public function getRequestTimeout(): float
    {
        return $this->requestTimeout;
    }

    /**
     * Sets the request timeout duration.
     *
     * @param float $requestTimeout Timeout in seconds for each API request.
     * @return self
     */
    public function setRequestTimeout(float $requestTimeout): self
    {
        $this->requestTimeout = $requestTimeout;
        return $this;
    }
}
