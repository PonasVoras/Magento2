<?php

namespace Modules\Sold\Helper;

use Modules\Sold\Model\ResourceModel\OrderedFactory;

class ModelHelperTest
{
    private $logger;
    private $orderedFactory;
    private $orderModel;

    public function __construct(
        OrderedFactory $orderedFactory,
        LoggerHelper $logger
    )
    {
        $this->logger = $logger;
        $this->orderedFactory = $orderedFactory;
        $this->orderModel = $this->orderedFactory->create();
    }

    public function resetOrderedCount(string $sku)
    {
        $configurableSku = $this->getConfigurableSku($sku);
        if (!empty($configurableSku)) {
            $this->orderModelAddQuantity($configurableSku);
        }
        $this->orderModelAddQuantity($sku);
    }

    public function orderModelAddQuantity(string $sku, bool $increment = false): void
    {
        $quantity = null;
        if ($increment) {
            $quantity = $this->orderModel
                ->load($sku, 'sku')
                ->getData('sold_quantity');
            $quantity++;
        }

        $newQuantity = $this->orderModel
            ->load($sku, 'sku')
            ->addData([
                'sold_quantity' => $quantity
            ]);
        $savedNewQuantity = $newQuantity->save();
        if ($savedNewQuantity) {
            $this->logger->logIfEnabled('Quantity' . $sku . 'saved successfully');
        }
    }

    public function handleOrderedItem(string $sku)
    {
        $this->incrementSimpleProductQuantity($sku);
        $configurableSku = $this->getConfigurableSku($sku);
        if (!empty($configurableSku)) {
            $this->incrementConfigurableProductQuantity($configurableSku);
        }
    }

    public function incrementSimpleProductQuantity(string $sku): string
    {
        $this->orderModelAddQuantity($sku, true);
    }

    public function incrementConfigurableProductQuantity(string $configurableSku)
    {
        $this->orderModelAddQuantity($configurableSku, true);
    }

    public function getConfigurableSku(string $sku): string
    {
        $configurableSku = explode('-', $sku);
        $configurableSku = $configurableSku[0];
        $configurableSku = $this->orderModel
            ->load($configurableSku, 'sku')
            ->getData('type_id');
        return $configurableSku;
    }
}
