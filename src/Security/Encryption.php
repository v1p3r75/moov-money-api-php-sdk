<?php

namespace MoovMoney\Security;

use InvalidArgumentException;
use MoovMoney\Interfaces\ConfigurationInterface;

/**
 * The Encryption class handles token generation and encryption for secure communication with the Moov Money API.
 * It uses AES-256-CBC encryption to generate a secure token based on the provided credentials.
 */
final class Encryption
{
    private string $token = "";

    private bool $isFailed = false;

    private const DEFAULT_IV_LENGTH = 16;

    /**
     * Constructs the Encryption instance and immediately generates the token.
     *
     * @param ConfigurationInterface $config Configuration instance containing credentials and encryption key.
     */
    public function __construct(private ConfigurationInterface $config)
    {
        $this->generateToken();
    }

    /**
     * Generates a token using the configured username, password, and encryption key.
     * Uses AES-256-CBC encryption mode with a 32-character encryption key.
     *
     * @throws InvalidArgumentException if the encryption key length is invalid.
     */
    private function generateToken(): void
    {
        $plaintext = sprintf("0:%s:%s", $this->config->getUsername(), $this->config->getPassword());
        $key = $this->config->getEncryptionKey();

        if (!$this->isKeyLengthValid($key)) {
            throw new InvalidArgumentException("Secret key's length must be 128, 192, or 256 bits");
        }

        $cipher = "AES-256-CBC";
        $ivLength = openssl_cipher_iv_length($cipher);
        $iv = str_repeat("\0", $ivLength ?: self::DEFAULT_IV_LENGTH);

        $encrypted = openssl_encrypt($plaintext, $cipher, $key, OPENSSL_RAW_DATA, $iv);

        if (!$encrypted) {
            $this->isFailed = true;
            return;
        }

        $this->token = base64_encode($encrypted);
    }

    /**
     * Returns the generated token for authenticating API requests.
     *
     * @return string The generated token.
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Indicates if there was an error during token generation.
     *
     * @return bool True if token generation failed, false otherwise.
     */
    public function hasError(): bool
    {
        return $this->isFailed;
    }

    /**
     * Validates the length of the encryption key.
     *
     * @param string $key The encryption key to validate.
     * @return bool True if the key length is valid, false otherwise.
     */
    private function isKeyLengthValid(string $key): bool
    {
        $len = strlen($key);
        return $len === 16 || $len === 24 || $len === 32;
    }
}
