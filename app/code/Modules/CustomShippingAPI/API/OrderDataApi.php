<?php namespace Modules\CustomShippingAPI\API;

use Magento\Framework\HTTP\Adapter\CurlFactory;
use Modules\CustomShippingAPI\Helper\Data;
use Psr\Log\LoggerInterface;
use Zend_Http_Client;
use Zend_Http_Response;

class OrderDataApi
{
    private $uri;
    private $store;
    private $logger;
    private $loggerEnable;
    private $curlFactory;
    private $configData;

    public function __construct(
        LoggerInterface $logger,
        CurlFactory $curlFactory,
        Data $configData
    ) {
        $this->configData = $configData;
        $this->logger = $logger;
        $this->curlFactory = $curlFactory;
        $this->loggerEnable = $this->configData->getConfigValue('logger_enable');
        $this->uri = $this->configData->getConfigValue('uri');
        $this->store = $this->configData->getConfigValue('store_id');
    }

    public function postRequest($orderData)
    {

        $url = $this->uri
            . '/' . $this->store;
        $requestBody = json_encode($orderData);
        $httpAdapter = $this->curlFactory->create();
        $method = Zend_Http_Client::POST;
        $headers = ["Content-Type:application/json"];
        $httpAdapter->write($method, $url, '1.1', $headers, $requestBody);
        $result = $httpAdapter->read();
        $body = Zend_Http_Response::extractBody($result);
        return $body;
    }

    public function putRequest($orderData, $orderId)
    {

        $url = $this->uri
            . '/' . $this->store
            . '/' . $orderId;
        $requestBody = json_encode($orderData);
        $httpAdapter = $this->curlFactory->create();
        $method = Zend_Http_Client::PUT;
        $headers = ["Content-Type:application/json"];
        $httpAdapter->write($method, $url, '1.1', $headers, $requestBody);
        $result = $httpAdapter->read();
        $body = Zend_Http_Response::extractBody($result);
        return $body;
    }

    public function deleteRequest($orderId)
    {

        $url = $this->uri
            . '/' . $this->store
            . '/' . $orderId;
        if ($this->loggerEnable) {
            $this->logger->info($url);
        }
        $ch = curl_init();
        $requestBody = "";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $result = json_decode($result);
        curl_close($ch);
        return $result;
    }
}

