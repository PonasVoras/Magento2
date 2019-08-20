<?php

namespace Modules\Sold\Ui\DataProvider\Product;

use Magento\Framework\Data\Collection;
use Magento\Ui\DataProvider\AddFieldToCollectionInterface;

class SoldQuantityFieldToCollection implements AddFieldToCollectionInterface
{
    public function addField(Collection $collection, $field, $alias = null)
    {
        $collection->joinField(
            'sold_quantity',
            'catalog_product_entity',
            'sold_quantity',
            'entity_id=entity_id',
            null,
            'left'
        );
    }
}
