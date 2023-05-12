<?php

namespace Nyehandel\Omnipay\Dintero;

use Nyehandel\Omnipay\Dintero\Message\Oauth\ObtainTokenRequest;
use Omnipay\Common\Http\Client;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class TokenService
{
    const CACHE_FOLDER_NAME = 'Cache';

    private string $directory;

    public function get(string $id)
    {
        $this->directory = dirname(__FILE__) . '/' . self::CACHE_FOLDER_NAME . '/';
        if (!file_exists($this->directory . md5($id))) {
            return null;
        }

        $token = file_get_contents($this->directory . md5($id));

        return json_decode($token);
    }

    public function create(string $accountId, string $clientId, string $clientSecret, string $audienceBaseUrl)
    {
        $obtainTokenRequest = new ObtainTokenRequest($accountId, $clientId, $clientSecret, $audienceBaseUrl);
        $response = $obtainTokenRequest->send();

        $response['expires_at'] = time() + $response['expires_in'] - 10;
        $data = json_encode($response);

        $this->set($id, $data);

        return json_decode($data);
    }

    private function set(string $id, string $value)
    {
        // Create Cache directory if missing
        if (!is_dir($this->directory)) {
            mkdir($this->directory);
        }

        return file_put_contents($this->directory . md5($id), $value);
    }

    public function invalidate(string $id)
    {
        return unlink($this->directory . md5($id));
    }
}
