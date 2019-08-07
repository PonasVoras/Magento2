<?php namespace Modules\Button\Block;

use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Template;
use Modules\Popup\Helper\Data;

class BlockButton extends Template
{
    private $helper;
    private $product;

    public function __construct(
        Product $product,
        Template\Context $context,
        Data $helper
    )
    {
        $this->product = $product;
        $this->helper = $helper;
        parent::__construct($context);
    }

    public function getDelay()
    {
    }

    public function getTitle()
    {

    }

    public function getContent()
    {

    }
}
