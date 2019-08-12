<?php namespace Modules\CustomShippingAPI\API;

use Modules\CustomShippingAPI\API\Intrefaces\SimpleApiInterface;
use Zend\Http\Request;

class OrderDataApi implements SimpleApiInterface
{
    private $storeId = 'store1';

    public function getHeaders()
    {
        // TODO: Implement getHeaders() method.
    }

    public function sendRequest(string $uri)
    {
        // TODO: Implement sendRequest() method.

    }

    public function createPostRequest($orderData)
    {
        $request = new Request();
        $request->setMethod(Request::METHOD_POST);
        $request->setUri('5d317bb345e2b00014d93f1c.mockapi.io/' . $this->storeId);
        $request->getHeaders()->addHeaders([
            'Content-Type' => 'application/json; charset=UTF-8'
        ]);
        return $this;
    }

    public function createPutRequest()
    {
        return $this;
    }

    public function createDeleteRequest()
    {
        return $this;
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
