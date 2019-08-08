<?php namespace Modules\CustomShippingAPI\API;

use Modules\CustomShippingAPI\API\Intrefaces\SimpleApiInterface;
use Psr\Log\LoggerInterface;
use Zend\Http\Client;
use Zend\Http\Headers;
use Zend\Http\Request;

class CurrencyCodeApi implements SimpleApiInterface
{
    private $apiUri = 'https://restcountries.eu/rest/v1/alpha/';
    private $logger;
    private $currencyCodeData = '';

    public function __construct(LoggerInterface $logger)
    {
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
        $request->setUri($this->apiUri . $uri);
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

    public function getDataJson(string $countryId)
    {
        if ($this->currencyCodeData == '') {
            $this->getToken();
            $requestUri = '/' . $countryId;
            $currencyCodeData = $this->sendRequest($requestUri);
            $this->logger->info($requestUri);
            $this->logger->info($currencyCodeData);
            $this->logger->info('API FINNISHED');
        }
        return $currencyCodeData;
    }

    public function getCurrencyCode(string $countryId)
    {
        $responseJson = $this->getDataJson($countryId);
        $currencyCode = empty($shippingMethod) ?
            "Not available" : json_decode($responseJson, true)['currencies'][0];
        $this->logger->info($currencyCode);
        return $currencyCode;
    }


}