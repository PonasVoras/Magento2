<?php namespace Modules\Promo\Plugins;

use Magento\Catalog\Model\Product;

class Promo
{
    public function aftergetPrice(Product $product, $price)
    {
        $price = $product->getData('price');
        //var_dump($price);
        return $price;
    }
}