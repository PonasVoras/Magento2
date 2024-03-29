<?php namespace Modules\Promo\Plugins;

use Magento\Catalog\Model\Product;

class Promo
{
    /**
     * @param Product $product
     * @param $price
     * @return mixed
     */
    public function aftergetPrice(Product $product, $price)
    {
        if (!$product->getData('PromoEnable_attribute')) {
            return $price;
        }
        return floatval($product->getData('PromoPrice_attribute'));
    }
}