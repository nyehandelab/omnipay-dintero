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
                'type',
                'reference',
                'description',
                'quantity',
                'price',
                'unit_discount_amount',
                'vat',
            );

            $totalAmount = $item->getQuantity() * ($item->getPrice() - $item->getUnitDiscountAmount());

            $orderLines[] = [
                'type' => $item->getType(),
                'reference' => $item->getReference(),
                'description' => $item->getDescription(),
                'quantity' => $item->getQuantity(),
                'unitPrice' => (int) $item->getPrice(),
                'unitDiscountAmount' => (int) $item->getUnitDiscountAmount(),
                'vat' => (int) $item->getVat(),
                'totalAmount' => (int) $totalAmount,
                'totalVatAmount' => (int) $item->getTotalVatAmount(),
            ];
        }

        return $orderLines;
    }
}
