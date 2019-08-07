<?php

namespace Modules\Featured\Controller\Index;

use Magento\Framework\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;
use \Magento\Framework\Registry;
use \Magento\Framework\App\Action\Action;

class Index extends Action
{
    protected $resultPageFactory;
    protected $coreRegistry;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $coreRegistry
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return ResultInterface|ResponseInterface
     * @throws Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $helper = $this->_objectManager->create('Modules\Featured\Helper\Data');
        if ($helper->getGeneralConfig('enable')) {
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()
                ->getTitle()
                ->set(
                    (__($helper->getGeneralConfig('title')))
                );
            $this->coreRegistry->register('limit', $helper->getGeneralConfig('limit'));
            return $resultPage;
        }
        return $this->_redirect('/');
    }
}