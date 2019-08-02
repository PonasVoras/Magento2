<?php

namespace Modules\Featured\Controller\Index;

use Magento\Framework\App\Action\Context;
use \Magento\Framework\App\Action\Action;
use \Modules\Featured\Helper\Data;

class Config extends Action
{

    protected $helperData;

    public function __construct(
        Context $context,
        Data $helperData

    )
    {
        $this->helperData = $helperData;
        return parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {

        // TODO: Implement execute() method.

        echo $this->helperData->getGeneralConfig('enable');
        echo $this->helperData->getGeneralConfig('display_text');
        exit();

    }
}
