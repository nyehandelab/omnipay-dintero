<?php

declare(strict_types=1);

namespace Nyehandel\Omnipay\Dintero\Message;

use Money\Money;
use Nyehandel\Omnipay\Dintero\AuthenticationRequestHeaderProvider;
use Nyehandel\Omnipay\Dintero\ItemBag;
use Nyehandel\Omnipay\Dintero\TokenService;
use Omnipay\Common\Http\Exception\NetworkException;
use Omnipay\Common\Http\Exception\RequestException;
use Omnipay\Common\Message\AbstractRequest as BaseAbstractRequest;
use Psr\Http\Message\ResponseInterface;

/**
 * @method ItemBag|null getItems()
 */
abstract class AbstractRequest extends BaseAbstractRequest
{
    /**
     * @return Money|null
     */
    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    public function getShippingOption()
    {
        return $this->getParameter('shipping_option');
    }

    public function setShippingOption(array $shipping_option)
    {
        return $this->setParameter('shipping_option', $shipping_option);
    }

    /**
     * @return string
     */
    public function getAccountId(): string
    {
        return $this->getParameter('account_id');
    }

    public function getProfileId(): string
    {
        return $this->getParameter('profile_id');
    }
    /**
     * @return string|null
     */

    /**
     * @return string|null
     */
    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    /**
     * The total tax amount of the order
     *
     * @return Money|null
     */
    public function getTaxAmount()
    {
        return $this->getParameter('tax_amount');
    }


    /**
     * @param string $baseUrl
     *
     * @return $this
     */
    public function setBaseUrl(string $baseUrl): self
    {
        $this->setParameter('base_url', $baseUrl);

        return $this;
    }

    public function setAudience(string $audience)
    {
        $this->setParameter('audience', $audience);
        return $this;
    }

    public function setUrl(array $url): self
    {
        $this->setParameter('url', $url);

        return $this;
    }

    public function getUrl ()
    {
        return $this->getParameter('url');
    }

    public function setExpress (array $express)
    {
        $this->setParameter('express', $express);

        return $this;
    }

    public function getExpress ()
    {
        return $this->getParameter('express');
    }
    public function setShippingMethod(array $shipping_method): self
    {
        $this->setParameter('shipping_method', $shipping_method);

        return $this;
    }

    public function getShippingMethod ()
    {
        return $this->getParameter('shipping_method');
    }

    public function setMerchantReference (string $merchant_reference)
    {
        $this->setParameter('merchant_reference', $merchant_reference);
    }

    public function getMerchantReference ()
    {
        return $this->getParameter('merchant_reference');
    }

    /**
     * @return string
     */
    public function getSystemName(): string
    {
        return $this->getParameter('system_name');
    }

    public function setSystemName(string $systemName)
    {
        $this->setParameter('system_name', $systemName);

        return $this;
    }
    /**
    /**
     * @inheritdoc
     */
    public function setItems($items)
    {
        if ($items && !$items instanceof ItemBag) {
            $items = new ItemBag($items);
        }

        return $this->setParameter('items', $items);
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale)
    {
        $this->setParameter('locale', $locale);
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
     * @param string $id
     *
     * @return $this
     */
    public function setAccountId(string $id): self
    {
        $this->setParameter('account_id', $id);

        return $this;
    }

    public function setClientId (string $client_id)
    {
        $this->setParameter('client_id', $client_id);
    }

    public function getClientId ()
    {
        return $this->getParameter('client_id');
    }

    public function setProfileId(string $id): self
    {
        $this->setParameter('profile_id', $id);

        return $this;
    }

    /**
     * @param mixed $value
     */
    public function setTaxAmount($value)
    {
        $this->setParameter('tax_amount', $value);
    }

    /**
     * @return string|null
     */
    public function getOrderId()
    {
        return $this->getParameter('order_id');
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setOrderId($value)
    {
        return $this->setParameter('order_id', $value);
    }

    /**
     * @return string|null
     */
    public function getDinteroId()
    {
        return $this->getParameter('dintero_id');
    }

    /**
     * @param string $dinteroId
     *
     * @return $this
     */
    public function setDinteroId(string $dinteroId): self
    {
        $this->setParameter('dintero_id', $dinteroId);

        return $this;
    }

    public function getBaseUrl ()
    {
        return $this->getParameter('base_url');
    }

    public function getAudience ()
    {
        return $this->getParameter('audience');
    }

    public function getReference ()
    {
        return $this->getParameter('reference');
    }

    public function setReference ($reference)
    {
        return $this->setParameter('reference', $reference);
    }

    /**
     * @param ResponseInterface $response
     *
     * @return array
     */
    protected function getResponseBody(ResponseInterface $response): array
    {
        try {
            return \json_decode($response->getBody()->getContents(), true);
        } catch (\TypeError $exception) {
            return [];
        }
    }

    /**
     * @param string $method
     * @param string $url
     * @param mixed  $data
     *
     * @return ResponseInterface
     *
     * @throws RequestException when the HTTP client is passed a request that is invalid and cannot be sent.
     * @throws NetworkException if there is an error with the network or the remote server cannot be reached.
     */
    protected function sendRequest(string $method, string $url, $data = []): ResponseInterface
    {

        $tokenExpired = false;
        $tokenService = new TokenService();
        $token = $tokenService->get($this->getAccountId());

        if (is_null($token) || !property_exists($token, 'expires_in') || $tokenExpired = $token->expires_in < time()) {
            if ($tokenExpired) {
                $tokenService->invalidate($this->getAccountId());
            }

            // todo; fix parameters, add audience
            $token = $tokenService->create($this->getAccountId(), $this->getClientId(), $this->getSecret(), $this->getAudience(), $this->getTestMode());
        }

        $headers = (new AuthenticationRequestHeaderProvider())->getHeaders($this, $token->access_token);

        if ('GET' === $method) {
            return $this->httpClient->request(
                $method,
                $this->getBaseUrl() . $url,
                $headers
            );
        }

        return $this->httpClient->request(
            $method,
            $this->getBaseUrl() . $url,
            array_merge(
                ['Content-Type' => 'application/json'],
                $headers
            ),
            \json_encode($data)
        );
    }
}
