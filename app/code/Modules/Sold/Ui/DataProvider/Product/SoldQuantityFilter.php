<?php

namespace Modules\Sold\Ui\DataProvider\Product;

use Magento\Framework\Data\Collection;
use Magento\Ui\DataProvider\AddFilterToCollectionInterface;

class SoldQuantityFilter implements AddFilterToCollectionInterface
{
    public function addFilter(
        Collection $collection,
        $field,
        $condition = null
    ) {
        if (isset($condition['like'])) {
            $collection->addFieldToFilter($field, $condition);
        }
    }
}
