<?php namespace Modules\CustomShippingAPI\API\Intrefaces;

use Zend\Http\Request;

interface SimpleApiInterface
{
    /**
     * @return mixed
     */
    public function getHeaders();

    /**
     * @param Request $request
     * @return string
     */
    public function getResponse(Request $request): string;

    /**
     * @param string $countryId
     * @return mixed
     */
    public function getDataJson(string $countryId);
}