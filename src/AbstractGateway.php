<?php

declare(strict_types=1);

namespace Nyehandel\Omnipay\Dintero;

use Nyehandel\Omnipay\Dintero\Message\AcknowledgeOrderRequest;
use Nyehandel\Omnipay\Dintero\Message\AuthorizeRequest;
use Nyehandel\Omnipay\Dintero\Message\CancelOrderRequest;
use Nyehandel\Omnipay\Dintero\Message\CaptureRequest;
use Nyehandel\Omnipay\Dintero\Message\CreateOrderSessionRequest;
use Nyehandel\Omnipay\Dintero\Message\ExtendAuthorizationRequest;
use Nyehandel\Omnipay\Dintero\Message\FetchTransactionRequest;
use Nyehandel\Omnipay\Dintero\Message\FullCaptureRequest;
use Nyehandel\Omnipay\Dintero\Message\GetOrderRequest;
use Nyehandel\Omnipay\Dintero\Message\GetOrderSessionRequest;
use Nyehandel\Omnipay\Dintero\Message\Oauth\ObtainTokenRequest;
use Nyehandel\Omnipay\Dintero\Message\PartialCaptureRequest;
use Nyehandel\Omnipay\Dintero\Message\PartialRefundRequest;
use Nyehandel\Omnipay\Dintero\Message\SetOrderReferenceRequest;
use Nyehandel\Omnipay\Dintero\Message\UpdateCustomerAddressRequest;
use Nyehandel\Omnipay\Dintero\Message\UpdateMerchantReferencesRequest;
use Nyehandel\Omnipay\Dintero\Message\UpdateOrderRequest;
use Nyehandel\Omnipay\Dintero\Message\UpdateOrderSessionRequest;
use Nyehandel\Omnipay\Dintero\Message\UpdateTransactionRequest;
use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Message\RequestInterface;

final class Gateway extends AbstractGateway implements GatewayInterface
{
    const BASE_URL = 'https://api.dintero.com/';
    const VERSION = 'v1';

    /**
     * @inheritDoc
     */
    public function getDefaultParameters(): array
    {
        return [
            'accountId' => '',
            'clientId' => '',
            'clientSecret' => '',
            'testMode' => true,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'Dintero';
    }

    /**
     * @return string
     */
    public function getAccountId(): string
    {
        return $this->getParameter('account_id');
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->getParameter('client_id');
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->getParameter('client_secret');
    }

    /**
     * @inheritDoc
     */
    public function initialize(array $parameters = [])
    {
        parent::initialize($parameters);

        $this->setBaseUrl();

        return $this;
    }

    public function obtainOauthToken(array $parameters = [])
    {
        return $this->createRequest(ObtainTokenRequest::class, $parameters);
    }

    /**
     * @param string $accountId
     *
     * @return $this
     */
    public function setAccountId(string $accountId): self
    {
        $this->setParameter('account_id', $accountId);

        return $this;
    }

    /**
     * @param string $clientId
     *
     * @return $this
     */
    public function setClientId(string $clientId): self
    {
        $this->setParameter('client_id', $clientId);

        return $this;
    }

    /**
     * @param string $clientSecret
     *
     * @return $this
     */
    public function setClientSecret(string $clientSecret): self
    {
        $this->setParameter('client_secret', $clientSecret);

        return $this;
    }

    public function setTestMode($testMode): self
    {
        parent::setTestMode($testMode);

        $this->setBaseUrl();

        return $this;
    }

    private function setBaseUrl()
    {
        $this->parameters->set('base_url', self::BASE_URL . '/' . self::VERSION . '/');
    }
}
