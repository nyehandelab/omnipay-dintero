<?php
declare(strict_types=1);

namespace Nyehandel\Omnipay\Dintero;

use Nyehandel\Omnipay\Dintero\Message\AbstractRequest;

final class AuthenticationRequestHeaderProvider
{
    public function getHeaders(AbstractRequest $request, string $token): array
    {
        $headers = [
            'Authorization' => sprintf(
                'Bearer ' . $token
            ),
        ];

        if ($request->getSystemName() != '') {
            $headers['Dintero-System-Name'] = $request->getSystemName();
        }

        return $headers;
    }
}
