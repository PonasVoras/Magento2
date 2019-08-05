<?php namespace Modules\Popup\Block;

use Magento\Framework\View\Element\Template;

class Popup extends Template
{
    public function getContent(): string
    {
        return 'Super hot discounts, yeah';
    }
}