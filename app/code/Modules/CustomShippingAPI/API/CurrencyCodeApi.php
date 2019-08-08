<?php namespace Modules\CustomShippingAPI\API;

use Modules\CustomShippingAPI\API\Intrefaces\SimpleApiInterface;
use Psr\Log\LoggerInterface;
use Zend\Http\Client;
use Zend\Http\Client\Adapter\Curl;
use Zend\Http\Headers;
use Zend\Http\Request;

class CurrencyCodeApi implements SimpleApiInterface
{
    private $apiUri = 'https://restcountries.eu/rest/v1/alpha/';
    private $logger;

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
        $finalUri = $this->apiUri . $uri;
        $this->logger->info('THY URI ' . $finalUri);
        $request->setUri($finalUri);
        $response = $this->getResponse($request);
        return $response;
    }

    public function getResponse(Request $request): string
    {
        $client = new Client();
        $options = [
            'adapter' => Curl::class,
            'timeout' => 200
        ];
        $client->setOptions($options);
        $response = $client->send($request);
        return $response->getBody();
    }

    public function getDataJson(string $countryId)
    {
        $requestUri = $countryId;
        $currencyCodeData = $this->sendRequest($requestUri);
        return $currencyCodeData;
    }

    public function getCurrencyCode(string $countryId)
    {
        $responseJson = $this->getDataJson($countryId);
        $currencyCode = empty($responseJson) ?
            "Not available" : json_decode($responseJson, true)['currencies'][0];
        return $currencyCode;
    }


}