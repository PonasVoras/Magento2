<?php namespace Modules\Promo\Block;

use DateTime;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Modules\Promo\Helper\Data;
//use Magento\Catalog\Helper\Data;

class PromoLabel extends Template
{
    private $helper;


    public function __construct(
        Context $context,
        Data $helper)
    {
        $this->helper = $helper;
        parent::__construct($context);
    }

    public function checkAdminPromotion()
    {
        return $this->helper->getGeneralConfig('enable');
    }

    public function checkItemPromotion()
    {
        return 1;
    }

    public function checkPrice()
    {
        return 4.20;
    }

    public function getLabelText()
    {
        return $this->helper->getGeneralConfig('title');
    }

    public function getLabelTextColor()
    {
        return $this->helper->getGeneralConfig('textColor');
    }

    public function getLabelBackgroundColor()
    {
        return $this->helper->getGeneralConfig('backgroundColor');
    }

    public function getCurrentTime()
    {
        $currentDateTime = date('Y-m-d H:i:s');
        return $currentDateTime;
    }

    public function getTimeLeft()
    {
        $time1 = new DateTime($this->helper->getGeneralConfig('time'));
        $time2 = new Datetime($this->getCurrentTime());
        $interval = $time1->diff($time2);
        $elapsed = $interval->format('%m months %a days %h hours %i minutes %s seconds remaining');
        return $elapsed;
    }

    function sayHello()
    {
        return __('Hello World');
    }
}