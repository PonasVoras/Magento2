<?php

namespace Modules\Sold\Ui\DataProvider\Product;

use Magento\Framework\Data\Collection;
use Magento\Ui\DataProvider\AddFilterToCollectionInterface;

// TODO name
class SoldQuantityFilterToCollection implements AddFilterToCollectionInterface
{
    public function addFilter(Collection $collection, $field, $condition = null)
    {
        if (isset($condition['eq'])) {
            $collection->addFieldToFilter($field, $condition);
        }
    }
}
