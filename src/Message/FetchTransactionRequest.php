<?php

declare(strict_types=1);

namespace Nyehandel\Omnipay\Dintero\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Http\Exception\NetworkException;
use Omnipay\Common\Http\Exception\RequestException;

/**
 * Creates a Dintero Checkout order if it does not exist
 */
final class FetchTransactionRequest extends AbstractOrderRequest
{
    /**
     * @inheritDoc
     *
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate(
            'transaction_id',
        );

        return [];
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidResponseException
     * @throws RequestException when the HTTP client is passed a request that is invalid and cannot be sent.
     * @throws NetworkException if there is an error with the network or the remote server cannot be reached.
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(
            'GET',
            'transactions/' . $this->getTransactionId() ,
        );

        return new FetchTransactionResponse(
            $this,
            $this->getResponseBody($response),
            $response->getStatusCode()
        );
    }
}
