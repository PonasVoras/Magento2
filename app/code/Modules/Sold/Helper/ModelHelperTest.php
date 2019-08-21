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
    ) {
        $this->logger = $logger;
        $this->orderedFactory = $orderedFactory;
        $this->orderModel = $this->orderedFactory->create();
    }
    public function resetOrderedCount(string $sku)
    {

        if ($this->isConfigurable($sku)) {
            $configurableSku = explode('-', $sku);
            $configurableSku = $configurableSku[0];
            $eraseConfigurable = $this->orderModel
                ->load($configurableSku, 'sku')
                ->addData([ 'sold_quantity' => null
                ]);
            $eraseConfigurable->save();
        }
        $eraseSimple = $this->orderModel
            ->load($sku, 'sku')
            ->addData([
                'sold_quantity' => null
            ]);
        $eraseSimple->save();
    }
    public function handleOrderedItem(string $sku)
    {
        $incrementSimple = $this->incrementSimpleProductQuantity($sku);
        $incrementConfigurable = 'Item is not configurable';
        if ($this->isConfigurable($sku)) {
            $incrementConfigurable = $this->incrementConfigurableProductQuantity($sku);
        }
        $this->logger->logIfEnabled($incrementSimple);
        $this->logger->logIfEnabled($incrementConfigurable);
    }
    public function incrementSimpleProductQuantity(string $sku): string
    {
        $quantity = $this->orderModel
            ->load($sku, 'sku')
            ->getData('sold_quantity');
        $this->logger->logIfEnabled('Quantity simple :' . $quantity);
        $quantity++;
        $newQuantity = $this->orderModel
            ->load($sku, 'sku')
            ->addData([
                'sold_quantity' => $quantity
            ]);
        $saveNewQuantity = $newQuantity->save();
        if ($saveNewQuantity) {
            $outcome = 'Saved successfully';
        } else {
            $outcome = 'Eh, there were some problems';
        }
        return $outcome;
    }
    public function isConfigurable(string $sku)
    {
        $sku = explode('-', $sku);
        $sku = $sku[0];
        $type = $this->orderModel
            ->load($sku, 'sku')
            ->getData('type_id');
        $outcome = ($type == 'configurable') ?? true;
        return $outcome;
    }
    public function incrementConfigurableProductQuantity(string $simpleSku)
    {
        $configurableSku = explode('-', $simpleSku);
        $configurableSku = $configurableSku[0];
        $quantity = $this->orderModel
            ->load($configurableSku, 'sku')
            ->getData('sold_quantity');
        $this->logger->logIfEnabled('Quantity configurebale :' . $quantity);
        $quantity++;
        $newQuantity = $this->orderModel
            ->load($configurableSku, 'sku')
            ->addData([
                'sold_quantity' => $quantity
            ]);
        $saveNewQuantity = $newQuantity->save();
        if ($saveNewQuantity) {
            $outcome = 'Saved successfully';
        } else {
            $outcome = 'Eh, there were some problems';
        }
        return $outcome;
    }
}