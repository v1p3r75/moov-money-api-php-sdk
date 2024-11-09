<?php

namespace MoovMoney;

use GuzzleHttp\Client;
use MoovMoney\Exceptions\BadConfigurationException;
use MoovMoney\Interfaces\ConfigurationInterface;
use MoovMoney\Response\MoovMoneyApiResponse;
use MoovMoney\Security\Encryption;
use MoovMoney\SoapRequest\HttpRequestTrait;
use MoovMoney\SoapRequest\SoapRequestBuilder;
use Psr\Http\Client\ClientInterface;

/**
 * MoovMoneyAPI provides methods to interact with the Moov Money API.
 * This class allows sending push transactions, push transactions with pending status checking transaction status, etc.
 */
final class MoovMoneyAPI
{
    use HttpRequestTrait;

    private SoapRequestBuilder $builder;

    private Encryption $encryption;

    /**
    * Constructs the MoovMoneyAPI instance.
    *
    * @param ConfigurationInterface $config Configuration instance containing API credentials and other settings.
    * @param ClientInterface|null $client Optional HTTP client, defaults to Guzzle if not provided.
    * @throws BadConfigurationException if configuration is invalid
    */
    public function __construct(private ConfigurationInterface $config, private ?ClientInterface $client = null)
    {

        if (!$config->isValid()) {

            throw new BadConfigurationException("Check if you have provided the correct information like: username, password, baseUrl");
        }

        $this->client = $client ?? new Client([
            'base_uri' => $config->getBaseUrl(),
            'timeout' => $config->getRequestTimeout(),
        ]);

        $this->encryption = new Encryption($config);

        $this->builder = new SoapRequestBuilder();

    }

    /**
     * Sends a push transaction request to Moov Money API.
     *
     * @param string $telephone The recipient's phone number.
     * @param int $amount The amount to be transacted.
     * @param string $message The message to be sent along with the transaction.
     * @param string $data1 Optional extra data for the transaction.
     * @param string $data2 Optional extra data for the transaction.
     * @param int $fee Optional fee amount for the transaction.
     * @return MoovMoneyApiResponse The response object containing transaction details and status.
     */
    public function pushTransaction(
        string $telephone,
        int $amount,
        string $message,
        string $data1 = "",
        string $data2 = "",
        int $fee = 0
    ): MoovMoneyApiResponse {

        $body = $this->builder->buildPushTransactionRequest(
            token: $this->encryption->getToken(),
            amount: $amount,
            phone: $telephone,
            message: $message,
            data1: $data1,
            data2: $data2,
            fee: $fee
        );

        return $this->request($body);

    }

    /**
     * Sends a push transaction with pending status to Moov Money API.
     *
     * @param string $telephone The recipient's phone number.
     * @param int $amount The amount to be transacted.
     * @param string $message The message to be sent along with the transaction.
     * @param string $data1 Optional extra data for the transaction.
     * @param string $data2 Optional extra data for the transaction.
     * @param int $fee Optional fee amount for the transaction.
     * @return MoovMoneyApiResponse The response object containing transaction details and status.
     */
    public function pushWithPendingTransaction(
        string $telephone,
        int $amount,
        string $message,
        string $data1 = "",
        string $data2 = "",
        int $fee = 0
    ): MoovMoneyApiResponse {

        $body = $this->builder->buildPushWithPendingRequest(
            token: $this->encryption->getToken(),
            amount: $amount,
            phone: $telephone,
            message: $message,
            data1: $data1,
            data2: $data2,
            fee: $fee
        );

        return $this->request($body);

    }

    /**
     * Checks the status of a transaction using its reference ID.
     *
     * @param string $referenceId The unique identifier for the transaction.
     * @return MoovMoneyApiResponse The response object containing transaction status and details.
     */
    public function getTransactionStatus(string $referenceId): MoovMoneyApiResponse
    {

        $body = $this->builder->buildTransactionStatusRequest($this->encryption->getToken(), $referenceId);

        return $this->request($body);

    }

    /**
    * Merchant transfer a funds to an account which allowed by the configurations.
    *
    * @return MoovMoneyApiResponse The response object containing transaction or error details.
    */
    public function transfertFlooz(
        string $destination,
        int $amount,
        string $referenceId,
        string $walletId = "0",
        string $data = ""
    ): MoovMoneyApiResponse {

        $body = $this->builder->buildTransfertFloozRequest(
            $this->encryption->getToken(),
            $destination,
            $amount,
            $referenceId,
            $walletId,
            $data
        );

        return $this->request($body);

    }

    /**
     * To check the current balance of subscribers, default of main wallet (walletID 0).
     *
     * @param string $subscriberTelephone The subscriber's phone number.
     * @return MoovMoneyApiResponse The response object containing transaction status and details.
     */
    public function getBalance(string $subscriberTelephone): MoovMoneyApiResponse
    {

        $body = $this->builder->buildGetBalanceRequest($this->encryption->getToken(), $subscriberTelephone);

        return $this->request($body);

    }

    /**
    * Sends a prepared SOAP request to the Moov Money API.
    *
    * @param string $body The SOAP request body.
    * @return MoovMoneyApiResponse The response object containing transaction or error details.
    */
    private function request(string $body): MoovMoneyApiResponse
    {
        return $this->sendRequest($this->client, $body);
    }
}
