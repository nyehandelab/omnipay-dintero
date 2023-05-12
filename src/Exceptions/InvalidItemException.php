<?php

namespace Nyehandel\Omnipay\Dintero\Exceptions;

use Omnipay\Common\Exception\OmnipayException;


/**
 * Invalid Settings Exception
 *
 * Thrown when the settings object is invalid or missing required fields.
 */
class InvalidItemException extends \Exception implements OmnipayException
{
}
