<?php namespace Modules\CustomShippingAPI\API;

use Psr\Log\LoggerInterface;
use Zend\Http\Client;
use Zend\Http\Headers;
use Zend\Http\Request;

class ApiCall
{
    private $token;
    private const URI_API = 'https://5d317bb345e2b00014d93f1c.mockapi.io';
    private const USER_ID = 658764298;

    private $logger;
    private $shippingData ='';

    public function __construct(
        LoggerInterface $logger
    )
    {
        $this->logger = $logger;
        $this->logger->info('API has been initiated');
    }

    public function getHeaders()
    {
        $httpHeaders = new Headers;
        $httpHeaders->addHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ]);
        return $httpHeaders;
    }

    public function sendRequest(string $uri)
    {
        $request = new Request();
        $request->setHeaders($this->getHeaders());
        $request->setUri(self::URI_API . $uri);
        $response = $this->getResponse($request);
        return $response;
    }


    public function getResponse(Request $request): string
    {
        $client = new Client();
        $options = [
            'adapter' => 'Zend\Http\Client\Adapter\Curl',
            'timeout' => 200
        ];
        $client->setOptions($options);
        $response = $client->send($request);
        //$this->logger->info($response);
        return $response->getBody();
    }

    private function getToken()
    {
        $tokenResponse = $this->sendRequest('/auth/' . self::USER_ID);
        $tokenResponse = json_decode($tokenResponse, true);
        $this->token = $tokenResponse['authToken'];
        $this->logger->info($this->token);
    }

    private function getShippingDataJson(string $countryId)
    {
        if ($this->shippingData == '') {
            $this->getToken();
            $requestUri = '/' . $this->token . '/' . $countryId;
            $shippingData = $this->sendRequest($requestUri);
            $this->logger->info($requestUri);
            $this->logger->info($shippingData);
            $this->logger->info('API FINNISHED');
        }
        return $shippingData;
    }

    public function getShippingMethod(string $countryId)
    {
        $responseJson = $this->getShippingDataJson($countryId);
        $shippingMethod = json_decode($responseJson, true)['methodName'];
        return $shippingMethod;
    }

    public function getShippingPrice(string $countryId)
    {
        $responseJson = $this->getShippingDataJson($countryId);
        $shippingPrice = json_decode($responseJson, true)['price'];
        return $shippingPrice;
    }

    public function getShippingCarrierName(string $countryId)
    {
        $responseJson = $this->getShippingDataJson($countryId);
        $shippingCarrierName = json_decode($responseJson, true)['carierName'];
        return $shippingCarrierName;
    }
}