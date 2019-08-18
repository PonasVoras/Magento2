<?php

namespace Modules\Sold\Helper;

use Modules\Sold\Model\OrderedFactory;

class ModelHelper
{
    private $logger;
    private $orderedFactory;
    private $model;

    public function __construct(
        OrderedFactory $orderedFactory,
        LoggerHelper $logger
    )
    {
        $this->logger = $logger;
        $this->orderedFactory = $orderedFactory;
    }

    public function handleOrderedItem(string $id, string $sku)
    {
        $this->model = $this->orderedFactory->create();
        $this->incrementSimpleProductQuantity($id);
        $this->incrementConfigurableProductQuantity($sku);
        //$this->logger->logIfEnabled($methodResponse);
    }

    public function incrementSimpleProductQuantity(string $id): string
    {
        $quantity = $this->model
            ->load($id)
            ->getData('sold_quantity');
        $this->logger->logIfEnabled('Quantity' . $quantity);
        $quantity++;
        $newQuantity = $this->model
            ->load($id)
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

    public function incrementConfigurableProductQuantity(string $sku)
    {
        $quantity = $this->model
            ->load('MH11-XS-Red', 'sku')
            ->getData('sold_quantity');
        $this->logger->logIfEnabled('Quantity configurebale' . $quantity);
//        $quantity++;
//        $newQuantity = $this->model
//            ->load($id)
//            ->addData([
//                'sold_quantity' => $quantity
//            ]);
//        $saveNewQuantity = $newQuantity->save();
//        if ($saveNewQuantity) {
//            $outcome = 'Saved successfully';
//        } else {
//            $outcome = 'Eh, there were some problems';
//        }
        //return $outcome;
    }

}
