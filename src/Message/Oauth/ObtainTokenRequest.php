<?php
declare(strict_types=1);

namespace Nyehandel\Omnipay\Dintero\Message\Oauth;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Http\Client;
use Omnipay\Common\Http\Exception\NetworkException;
use Omnipay\Common\Http\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

/**
 * Creates a Dintero oauth 2 token
 */
final class ObtainTokenRequest
{
    protected $endpoint = 'https://checkout.dintero.com/v1/';
    protected $version = 'v1';

    public function __construct(protected string $accountId, protected string $clientId, protected string $clientSecret, string $audienceBaseUrl)
    {
        //
    }

    public function getEndpoint()
    {
        return $this->endpoint . '/' . $this->version . '/accounts/' . $this->accountId . '/auth/token';
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
     * @inheritDoc
     *
     * @throws InvalidResponseException
     * @throws RequestException when the HTTP client is passed a request that is invalid and cannot be sent.
     * @throws NetworkException if there is an error with the network or the remote server cannot be reached.
     */
    public function send()
    {
        $authString = base64_encode($this->clientId . ':' . $this->clientSecret);

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . $authString,
        ];
        $httpClient = new Client();

        $response = $httpClient->request(
            'POST',
            $this->getEndpoint(),
            $headers,
            http_build_query([
                'grant_type' => 'client_credentials',
                'audience' => $this->audienceBaseUrl . '/accounts/' . $this->accountId,
            ]),
        );

        return $this->getResponseBody($response);
    }
}
