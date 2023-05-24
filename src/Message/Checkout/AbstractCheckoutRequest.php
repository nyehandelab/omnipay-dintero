<?php

declare(strict_types=1);

namespace Nyehandel\Omnipay\Dintero\Message\Checkout;

use Nyehandel\Omnipay\Dintero\Message\AbstractRequest;
use Nyehandel\Omnipay\Dintero\Message\ItemDataTrait;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Http\Exception\NetworkException;
use Omnipay\Common\Http\Exception\RequestException;

/**
 * Creates a Dintero Checkout order if it does not exist
 */
abstract class AbstractCheckoutRequest extends AbstractRequest
{

    use ItemDataTrait;

    public function getCurrency ()
    {
        return $this->getParameter('currency');
    }

    public function getCheckoutData ()
    {
        return [
            'url' => $this->getUrl(),
            'order' => [
                'amount' => (int) $this->getAmount(),
                'currency' => $this->getCurrency(),
                'merchant_reference' => $this->getMerchantReference(),
                'items' => $this->getItemData($this->getItems()),
                //'shipping_option' => [],
            ],
            'express' => $this->getExpress(),
            'profile_id' => $this->getProfileId(),
        ];
    }
}
