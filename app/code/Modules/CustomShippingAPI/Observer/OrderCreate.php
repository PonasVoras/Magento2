<?php namespace Modules\CustomShippingAPI\Observer;

use Exception;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Modules\CustomShippingAPI\API\OrderDataApi;
use Modules\CustomShippingAPI\Helper;
use Modules\CustomShippingAPI\Model;
use Modules\CustomShippingAPI\Helper\Data;
use Psr\Log\LoggerInterface;

class OrderCreate implements ObserverInterface
{
    private $logger;
    private $configData;
    private $loggerEnable;
    private $orderDataApi;
    private $orderDataFactory;
    private $orderDataModelFactory;

    public function __construct(
        LoggerInterface $logger,
        OrderDataApi $orderDataApi,
        Helper\OrderDataFactory $orderDataFactory,
        Model\OrderDataFactory $orderDataModelFactory,
        Data $configData
    ) {
        $this->orderDataModelFactory = $orderDataModelFactory;
        $this->orderDataFactory = $orderDataFactory;
        $this->orderDataApi = $orderDataApi;
        $this->configData = $configData;
        $this->logger = $logger;
        $this->loggerEnable = $this->configData->getConfigValue('logger_enable');
    }

    public function execute(Observer $observer)
    {
        $orderObject = $observer->getEvent()->getOrder();
        $this->logger->info('Order was placed');
        $this->saveOrder($orderObject);
    }

    public function saveOrder($orderObject)
    {
        $orderData = $this->orderDataFactory->create($orderObject);
        $orderDataResponse = $this->orderDataApi->postRequest($orderData);

        if ($this->loggerEnable) {
            $this->logger->info('Formatted order data, ready to send to an API: '
                . json_encode($orderData));

            $this->logger->info('API response' . $orderDataResponse);
        }

        $orderDataResponse = json_decode($orderDataResponse, true);
        $saveOrderToDb = $this->orderDataModelFactory->create();
        $saveOrderToDb->addData([
            'order_id' => $orderData['id'],
            'order_id_response' => $orderDataResponse['id']
        ]);

        try {
            $saveOrderToDb->save();
        } catch (Exception $e) {
        }

        if ($this->loggerEnable) {
        $this->logger->info(
            'Successfully saved order data response from API in the database'
        );
        }
    }
}
