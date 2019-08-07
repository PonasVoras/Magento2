<?php namespace Modules\Button\Block;

use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Tests\NamingConvention\true\string;

class BlockButton extends Template
{
    private $helper;
    private $product;

    public function __construct(
        Product $product,
        Context $context,
        Data $helper)
    {
        $this->product = $product;
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * @return string|null
     */
    public function sayHello(): ?string
    {
        return ('Hello World');
    }
}