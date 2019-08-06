<?php namespace Modules\Promo\Plugins;

use Magento\Catalog\Model\Product;

class Promo
{
    public function aftergetPrice(Product $product, $price)
    {
//        $price = $product->getData('price');
//        $checkPromo = $product->getData('PromoEnable_attribute');
////        if ($checkPromo !== 0) {
////            $price = $product->getData('PromoPrice_attribute');
//////            var_dump($price);
//////            var_dump($product->getData('PromoPrice_attribute'));
//////            var_dump($product->getData('PromoEnable_attribute'));
////        } else {
////            $price = $product->getData('price');
////        }
//        return $price;

        if (!$product->getData('PromoEnable_attribute')) {
            return $price;
        }
        return floatval($product->getData('PromoPrice_attribute'));
    }
    //}
}