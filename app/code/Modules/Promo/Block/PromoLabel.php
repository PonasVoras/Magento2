<?php namespace Modules\Promo\Block;

use DateTime;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Modules\Promo\Helper\Data;

class PromoLabel extends Template
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

    public function checkPromotion(): string
    {
        $this->helper->getGeneralConfig('enable');
        var_dump(get_object_vars($this->product));
        var_dump($this->product->getData('PromoEnable_attribute'));
        return 1;
    }

    public function getLabelText(): string
    {
        return $this->helper->getGeneralConfig('title');
    }

    public function getLabelTextColor(): string
    {
        return $this->helper->getGeneralConfig('textColor');
    }

    public function getLabelBackgroundColor(): string
    {
        return $this->helper->getGeneralConfig('backgroundColor');
    }

    public function getCurrentTime(): string
    {
        $currentDateTime = date('Y-m-d H:i:s');
        return $currentDateTime;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getTimeLeft(): string
    {
        $time1 = new DateTime($this->helper->getGeneralConfig('time'));
        $time2 = new Datetime($this->getCurrentTime());
        $interval = $time1->diff($time2);
        $elapsed = $interval
            ->format('%a days %h hours %i minutes remaining');
        return $elapsed;
    }
}