<?php

namespace MoovMoney\Response;

use MoovMoney\Interfaces\ApiResponseInterface;

class GetMobileStatusResponse implements ApiResponseInterface
{
    /**
     * Constructs the GetMobileStatusResponse instance.
     *
     * @param array<string> $response The response data from the API, provided as an associative array.
     */
    public function __construct(private array $response)
    {

    }


    /**
     * Get the account type.
     *
     * @return string|null The account type, or null if not set.
     */
    public function getAccountType(): string|null
    {
        return $this->get('accounttype');
    }

    /**
     * Get the allowed transfer value.
     *
     * @return int The allowed transfer as an integer.
     */
    public function getAllowedTransfer(): int
    {
        return (int) $this->get('allowedtransfer');
    }

    /**
     * Get the city.
     *
     * @return string|null The city, or null if not set.
     */
    public function getCity(): string|null
    {
        return $this->get('city');
    }

    /**
     * Get the date of birth.
     *
     * @return string|null The date of birth in 'Y-m-d H:i:s' format, or null if not set.
     */
    public function getDateOfBirth(): string|null
    {
        return $this->get('dateofbirth');
    }

    /**
     * Get the first name.
     *
     * @return string|null The first name, or null if not set.
     */
    public function getFirstName(): string|null
    {
        return $this->get('firstname');
    }

    /**
     * Get the last name.
     *
     * @return string|null The last name, or null if not set.
     */
    public function getLastName(): string|null
    {
        return $this->get('lastname');
    }

    /**
     * Get the second name.
     *
     * @return string|null The second name, or null if not set.
     */
    public function getSecondName(): string|null
    {
        return $this->get('secondname');
    }

    /**
     * Get the telephone (MSISDN).
     *
     * @return string|null The MSISDN (telephone number), or null if not set.
     */
    public function getTelephone(): string|null
    {
        return $this->get('msisdn');
    }

    /**
     * Get the region.
     *
     * @return string|null The region, or null if not set.
     */
    public function getRegion(): string|null
    {
        return $this->get('region');
    }

    /**
     * Get the street address.
     *
     * @return string|null The street address, or null if not set.
     */
    public function getStreet(): string|null
    {
        return $this->get('street');
    }

    /**
     * Get the subscriber status.
     *
     * @return string|null The subscriber status, or null if not set.
     */
    public function getSubscriberStatus(): string|null
    {
        return $this->get('subscriberstatus');
    }



    /**
     * Converts the API response data to an associative array.
     *
     * @return array<string> The response data as an array.
     */
    public function toArray(): array
    {
        return $this->response;
    }

    /**
     * Gets a specific value from the response data by key.
     *
     * @param string $key The key to retrieve from the response data.
     * @return string|null The value associated with the key, or null if the key does not exist.
     */
    public function get(string $key): ?string
    {
        return isset($this->response[$key]) ? (string) $this->response[$key] : null;
    }
}
