<?php

declare(strict_types=1);

namespace Nyehandel\Omnipay\Dintero\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Http\Exception\NetworkException;
use Omnipay\Common\Http\Exception\RequestException;

final class PartialRefundRequest extends AbstractRequest
{
    use ItemDataTrait;

    /**
     * @inheritDoc
     *
     * @throws InvalidRequestException
     * @throws RequestException
     * @throws \InvalidArgumentException
     */
    public function getData()
    {
        $this->validate('order_id', 'amount', 'dintero_id', 'items');

        $data = ['totalRefundAmount' => $this->getAmountInteger()];
        $items = $this->getItems();

        $data['orderLines'] = $this->getItemData($items);

        return $data;
    }

    /**
     * @inheritdoc
     *
     * @throws RequestException when the HTTP client is passed a request that is invalid and cannot be sent.
     * @throws NetworkException if there is an error with the network or the remote server cannot be reached.
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(
            'POST',
            'orders/' . $this->getOrderId() . '/partialrefund/' . $this->getDinteroId(),
            $data
        );

        return new PartialRefundResponse(
            $this,
            $this->getResponseBody($response),
            $response->getStatusCode()
        );
    }
}
