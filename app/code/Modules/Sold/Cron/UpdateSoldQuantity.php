<?php

namespace Modules\Sold\Cron;

use Magento\Sales\Api\OrderRepositoryInterface;
use Modules\Sold\Helper\AdminConfigDataHelper;
use Modules\Sold\Helper\LoggerHelper;
use Modules\Sold\Helper\ModelHelper;
use Magento\Framework\Api\SearchCriteriaBuilder;

class UpdateSoldQuantity
{
    private $logger;
    private $databaseModel;
    private $adminConfigData;
    private $orderRepository;
    protected $searchCriteriaBuilder;

    public function __construct(
        LoggerHelper $logger,
        ModelHelper $databaseModel,
        AdminConfigDataHelper $adminConfigData,
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->logger = $logger;
        $this->databaseModel = $databaseModel;
        $this->adminConfigData = $adminConfigData;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function execute()
    {
        $this->handleAllOrders();
    }

    public function handleAllOrders()
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(
                'status',
                'pending',
                'eq'
            )->create();
        $orders = $this->orderRepository->getList($searchCriteria);
        foreach ($orders->getItems() as $order) {
            $this->handleOrderData($order);
        }
    }

    public function isOrderNotCanceled($orderObject)
    {
        $orderState = $orderObject->getState();
        $outcome = ($orderState !== 'canceled') ?? true;
        return $outcome;
    }

    private function handleOrderData($orderObject)
    {
        $allItems = $orderObject->getAllItems();
        foreach ($allItems as $item) {
            $sku = $item->getSku();
            $this->logger->logIfEnabled('Cron retrieved sku :' . $sku);
            $this->databaseModel->handleOrderedItem($sku);
        }
    }
}
