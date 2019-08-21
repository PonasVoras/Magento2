<?php

namespace Modules\Sold\Helper;

use Modules\Sold\Model\ResourceModel\OrderedResourceModelFactory;
use Modules\Sold\Model\ResourceModel\Ordered\OrderedCollectionFactory;

class ModelHelper
{
    private $logger;
    private $orderedResourceModelFactory;
    private $orderedCollection;
    private $orderedCollectionFactory;
    private $orderedResourceModel;

    public function __construct(
        OrderedCollectionFactory $orderedCollectionFactory,
        OrderedResourceModelFactory $orderedResourceModelFactory,
        LoggerHelper $logger
    ) {
        $this->logger = $logger;
        $this->orderedCollectionFactory = $orderedCollectionFactory;
        $this->orderedResourceModelFactory = $orderedResourceModelFactory;
        $this->orderedResourceModel = $this->orderedResourceModelFactory->create();
    }

    public function resetOrderedCount(string $sku)
    {
        //$configurableSku = $this->getConfigurableSku($sku);
//        if (!empty($configurableSku)) {
//            $this->orderModelAddQuantity($configurableSku);
//        }
        $this->orderModelAddQuantity($sku);
    }

    public function orderModelAddQuantity(string $sku, bool $increment = false): void
    {
        $orderedCollection = $this->orderedCollectionFactory->create();
//        $orderedCollection = $orderedCollection->addFieldToFilter('*', array(
//            'like' => $sku . ' %'
//        ));
        $orderedCollection = $orderedCollection->addFieldToSelect('*')->load();
        //$this->logger->logIfEnabled(json_encode($orderedCollection));

        foreach ($orderedCollection as $product) {
            $this->logger->logIfEnabled(json_encode($product));
        }

//        $quantity = null;
//        if ($increment) {
//            $quantity = $this->orderModel
//                ->load($sku, 'sku')
//                ->getData('sold_quantity');
//            $quantity++;
//        }
//
//        $newQuantity = $this->orderModel
//            ->load($sku, 'sku')
//            ->addData([
//                'sold_quantity' => $quantity
//            ]);
//        $savedNewQuantity = $newQuantity->save();
//
//        if ($savedNewQuantity) {
//            $this->logger->logIfEnabled('Quantity ' . $sku . ' saved successfully');
//        }
    }

    public function handleOrderedItem(string $sku)
    {
        $this->orderModelAddQuantity($sku, true);
        //$configurableSku = $this->getConfigurableSku($sku);
//        if (!empty($configurableSku)) {
//            $this->orderModelAddQuantity($configurableSku, true);
//        }
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
