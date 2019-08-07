<?php namespace Modules\Button\Block;

use Magento\Catalog\Helper;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Template;

class BlockButton extends Template
{
    private $magentoHelper;
    private $product;

    public function __construct(
        Product $product,
        Helper\Data $magentoHelper,
        Template\Context $context)
    {
        $this->product = $product;
        $this->magentoHelper = $magentoHelper;
        parent::__construct($context);
    }

    public function popupEnabled()
    {
        if (is_null($this->product
            ->getData('PromoEnable_attribute'))) {
            $this->product = $this->magentoHelper->getProduct();
            $popupEnabled = $this->product->getData('PromoEnable_attribute');
            return $popupEnabled;
        }
        return false;
    }

    public function getTitle()
    {
        return $this->product->getAttributeText('PopupContent_attribute');
    }
}
