<?php
declare(strict_types=1);

namespace Nyehandel\Omnipay\Dintero;

use Nyehandel\Omnipay\Dintero\Message\AbstractRequest;

final class AuthenticationRequestHeaderProvider
{
    public function getHeaders(string $token): array
    {
        return [
            'Authorization' => sprintf(
                'Bearer ' . $token
            ),
        ];
    }
}
