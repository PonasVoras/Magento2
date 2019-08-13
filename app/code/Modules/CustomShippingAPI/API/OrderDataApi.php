<?php namespace Modules\CustomShippingAPI\API;

use Magento\Framework\HTTP\Adapter\CurlFactory;
use Modules\CustomShippingAPI\API\Intrefaces\SimpleApiInterface;
use Psr\Log\LoggerInterface;
use Zend\http\Client;
use Zend\Http\Client\Adapter;
use Zend\Http\Headers;
use Zend\Http\Request;

class OrderDataApi
{
    private $storeId = 'store1';
    private $url = 'https://5d317bb345e2b00014d93f1c.mockapi.io/';
    private $request;
    private $logger;
    private $curlFactory;

    public function __construct(
        LoggerInterface $logger,
        CurlFactory $curlFactory
    ) {

        $this->logger = $logger;
        $this->curlFactory = $curlFactory;
    }

    public function postRequest($orderData)
    {
        try {
            $url = $this->url . $this->storeId;
            $requestBody = json_encode($orderData);
            $httpAdapter = $this->curlFactory->create();
            $method = \Zend_Http_Client::POST;
            $headers = ["Content-Type:application/json"];
            $httpAdapter->write($method, $url, '1.1', $headers, $requestBody);
            $result = $httpAdapter->read();
            $body = \Zend_Http_Response::extractBody($result);
            return $body;
        } catch (\Exception $e) {
            $this->logger->info('Error Curl', ['exception' => $e]);
        }
    }

    public function putRequest($orderData)
    {
        try {
            $url = $this->url . $this->storeId;
            $requestBody = json_encode($orderData);
            $httpAdapter = $this->curlFactory->create();
            $method = \Zend_Http_Client::PUT;
            $headers = ["Content-Type:application/json"];
            $httpAdapter->write($method, $url, '1.1', $headers, $requestBody);
            $result = $httpAdapter->read();
            $body = \Zend_Http_Response::extractBody($result);
            return $body;
        } catch (\Exception $e) {
            $this->logger->info('Error Curl', ['exception' => $e]);
        }
    }


    public function getResponse(Request $request): string
    {
        // TODO: Implement getResponse() method.
    }

    public function getDataJson(string $countryId)
    {
        // TODO: Implement getDataJson() method.
    }
}
