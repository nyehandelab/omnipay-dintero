<?php

declare(strict_types=1);

namespace Nyehandel\Omnipay\Dintero;

use Nyehandel\Omnipay\Dintero\Exceptions\InvalidItemException;

final class Item extends \Omnipay\Common\Item
{
    /**
     * Validate the item
     *
     *
     * @return void
     * @throws Exception\InvalidRequestException
     * @throws InvalidSettingsException
     */

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getParameter('id');
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->setParameter('id', $id);
    }

    public function setLineId ($lineId)
    {
        $this->setParameter('line_id', $lineId);
    }

    public function getLineId ()
    {
        return $this->getParameter('line_id');
    }

    public function setDescription ($description)
    {
        $this->setParameter('description', $description);
    }

    public function getDescription ()
    {
        return $this->getParameter('description');
    }

    public function setAmount ($amount)
    {
        $this->setParameter('amount', $amount);
    }

    public function getAmount ()
    {
        return $this->getParameter('amount');
    }

    public function setVatAmount ($vatAmount)
    {
        $this->setParameter('vat_amount', $vatAmount);
    }

    public function getVatAmount ()
    {
        return $this->getParameter('vat_amount');
    }

    public function setVat ($vat)
    {
        $this->setParameter('vat', $vat);
    }

    public function getVat ()
    {
        return $this->getParameter('vat');
    }

    public function setType ($type)
    {
        $this->setParameter('type', $type);
    }

    public function getType ()
    {
        return $this->getParameter('type');
    }
}
