<?php

declare(strict_types=1);

namespace Nyehandel\Omnipay\Dintero\Message;

use Nyehandel\Omnipay\Dintero\ItemBag;

trait ItemDataTrait
{
    /**
     * @param ItemBag $items
     *
     * @return array[]
     */
    public function getItemData(ItemBag $items): array
    {
        $orderLines = [];

        foreach ($items as $item) {

            $item->validate(
                'id',
                'line_id',
                'description',
                'quantity',
                'amount',
                'vat_amount',
            );

            $orderLines[] = [
                'id' => $item->getId(),
                'line_id' => (string) $item->getLineId(),
                'description' => $item->getDescription(),
                'quantity' => (int) $item->getQuantity(),
                'amount' => (int) $item->getAmount(),
                'vat_amount' => (int) $item->getVatAmount(),
            ];
        }

        return $orderLines;
    }
}
