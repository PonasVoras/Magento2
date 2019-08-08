<?php namespace Modules\CustomShippingAPI\API;

use Modules\CustomShippingAPI\API\Intrefaces\SimpleApiInterface;
use Psr\Log\LoggerInterface;
use Zend\Http\Client;
use Zend\Http\Headers;
use Zend\Http\Request;

class ShippingMethodDataApi implements SimpleApiInterface
{
    private $apiToken;
    private $apiUri = 'https://5d317bb345e2b00014d93f1c.mockapi.io';
    private const USER_ID = 658764298;

    private $logger;
    private $shippingData = '';

    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function getHeaders()
    {
        $httpHeaders = new Headers();
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
        $finalUri = $this->apiUri . $uri;
        $request->setUri($this->apiUri . $uri);
        $this->logger->info('THY URI ' . $finalUri);
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
        return $response->getBody();
    }

    public function getToken()
    {
        $tokenResponse = $this->sendRequest('/auth/' . self::USER_ID);
        $tokenResponse = json_decode($tokenResponse, true);
        $this->apiToken = $tokenResponse['authToken'];
        $this->logger->info($this->apiToken);
    }

    public function getDataJson(string $countryId)
    {
        if ($this->shippingData == '') {
            $this->getToken();
            $requestUri = '/' . $this->apiToken . '/' . $countryId;
            $shippingData = $this->sendRequest($requestUri);
            return $shippingData;
        }
        return '';
    }

    public function getShippingMethod(string $countryId)
    {
        $responseJson = $this->getDataJson($countryId);
        $shippingMethod = empty($responseJson) ?
            "Not available" : json_decode($responseJson, true)['methodName'];
        return $shippingMethod;
    }

    public function getShippingPrice(string $countryId)
    {
        $responseJson = $this->getDataJson($countryId);
        $shippingPrice = empty($responseJson) ?
            "0.0" : json_decode($responseJson, true)['price'];
        return $shippingPrice;
    }

    public function getShippingCarrierName(string $countryId)
    {
        $responseJson = $this->getDataJson($countryId);
        $shippingCarrierName = empty($responseJson) ?
            "Not available" : json_decode($responseJson, true)['carierName'];
        return $shippingCarrierName;
    }
}