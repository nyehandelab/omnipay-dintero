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
    const BASE_URL = 'https://checkout.dintero.com/';
    const VERSION = 'v1';

    /**
     * @inheritdoc
     */
    public function acknowledge(array $options = []): RequestInterface
    {
        return $this->createRequest(AcknowledgeOrderRequest::class, $options);
    }

    /**
     * @inheritdoc
     */
    public function authorize(array $options = [])
    {
        return $this->createRequest(CreateCheckoutSessionRequest::class, $options);
    }

    public function getOrder(array $options = [])
    {
        return $this->createRequest(GetOrderRequest::class, $options);
    }

    public function getOrderSession(array $options = [])
    {
        return $this->createRequest(GetOrderSessionRequest::class, $options);
    }

    public function updateOrder(array $options = [])
    {
        return $this->createRequest(UpdateOrderSessionRequest::class, $options);
    }

    public function cancelOrder(array $options = [])
    {
        return $this->createRequest(CancelOrderRequest::class, $options);
    }

    /**
     * @inheritdoc
     */
    public function capture(array $options = [])
    {
        return $this->createRequest(PartialCaptureRequest::class, $options);
    }

    /**
     * @inheritdoc
     */
    public function fullCapture(array $options = [])
    {
        return $this->createRequest(FullCaptureRequest::class, $options);
    }

    /**
     * @inheritdoc
     */
    public function extendAuthorization(array $options = []): RequestInterface
    {
        return $this->createRequest(ExtendAuthorizationRequest::class, $options);
    }

    /**
     * @inheritdoc
     */
    public function fetchTransaction(array $options = []): RequestInterface
    {
        return $this->createRequest(FetchTransactionRequest::class, $options);
    }

    /**
     * @inheritDoc
     */
    public function getDefaultParameters(): array
    {
        return [
            'secret' => '',
            'testMode' => true,
            'username' => '',
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
    public function getSecret(): string
    {
        return $this->getParameter('secret');
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->getParameter('id');
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
     * @inheritdoc
     */
    public function refund(array $options = [])
    {
        return $this->createRequest(PartialRefundRequest::class, $options);
    }

    /**
     * @param string $secret
     *
     * @return $this
     */
    public function setSecret(string $secret): self
    {
        $this->setParameter('secret', $secret);

        return $this;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setId(string $id): self
    {
        $this->setParameter('id', $id);

        return $this;
    }

    public function setTestMode($testMode): self
    {
        parent::setTestMode($testMode);

        $this->setBaseUrl();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function updateCustomerAddress(array $options = []): RequestInterface
    {
        return $this->createRequest(UpdateCustomerAddressRequest::class, $options);
    }

    /**
     * @inheritdoc
     */
    public function updateTransaction(array $options = []): RequestInterface
    {
        return $this->createRequest(UpdateTransactionRequest::class, $options);
    }

    public function setOrderReference(array $options = []): RequestInterface
    {
        return $this->createRequest(SetOrderReferenceRequest::class, $options);
    }

    private function setBaseUrl()
    {
        $this->parameters->set('base_url', self::BASE_URL . '/' . self::VERSION . '/');
    }
}
