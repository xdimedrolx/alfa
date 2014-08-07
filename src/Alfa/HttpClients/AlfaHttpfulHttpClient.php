<?php
/**
 * Class AlfaHttpFulHttpClient
 *
 * User: xdimedrolx
 * Date: 31.07.14
 * Time: 10:27
 */

namespace Alfa\HttpClients;

use Alfa\Exceptions\AlfaRequestException;

use Httpful\Request;
use Httpful\Mime;

use Httpful\Exception\ConnectionErrorException;


class AlfaHttpfulHttpClient implements AlfaHttpClientInterface
{

    private $responseHttpStatusCode = 0;

    /**
     * @param $url
     * @param array $parameters
     * @param string $method
     * @return stdClass
     * @throws \Alfa\Exceptions\AlfaRequestException
     */
    public function send($url, $parameters = array(), $method = 'GET')
    {
        try {

            if ($method === 'POST') {
                $response = Request::post($url, $parameters, Mime::FORM)->expectsType(Mime::JSON)->send();
            }
            else {
                $response = Request::get($url)->send();
            }

            $this->responseHttpStatusCode = $response->code;

            return $response->body;
        }
        catch (ConnectionErrorException $e) {
            throw new AlfaRequestException($e->getMessage(), $e->getCode());
        }
    }
}