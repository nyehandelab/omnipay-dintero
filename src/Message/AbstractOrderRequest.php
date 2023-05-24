<?php

declare(strict_types=1);

namespace Nyehandel\Omnipay\Dintero\Message;

use Nyehandel\Omnipay\Dintero\Address;
use Nyehandel\Omnipay\Dintero\Customer;
use Nyehandel\Omnipay\Dintero\ItemBag;
use Nyehandel\Omnipay\Dintero\Settings;
use Nyehandel\Omnipay\Dintero\WidgetOptions;

abstract class AbstractOrderRequest extends AbstractRequest
{
    use ItemDataTrait;

    public function getCurrency ()
    {
        return $this->getParameter('currency');
    }

    public function getAmount ()
    {
        return $this->getParameter('amount');
    }

    public function setAmount ($amount)
    {
        return $this->setParameter('amount', $amount);
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
