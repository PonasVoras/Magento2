<?php

namespace Modules\Featured\Block;

use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Psr\Log\LoggerInterface;

class Featured extends AbstractProduct
{
    protected $_productCollectionFactory;
    private $logger;

    public function __construct(
        Context $context,
        CollectionFactory $productCollectionFactory,
        LoggerInterface $logger,
        array $data = []
    )
    {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->logger = $logger;
        parent::__construct($context, $data);
    }

    public function getProductCollection()
    {
        $collection = $this->_productCollectionFactory->create()
            ->addAttributeToFilter('status', '1')
            ->addAttributeToFilter('Featured_attribute', '1');
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->setPageSize(3)
            ->setCurPage(1);
        return $collection;
    }

    public function getFeatured()
    {
        return 'Featured products';
    }
}