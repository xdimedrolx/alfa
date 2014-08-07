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

use Buzz\Message;
use Buzz\Client;
use Buzz\Exception;

class AlfaBuzzHttpClient implements AlfaHttpClientInterface
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

            if ($method === 'GET' || empty($parameters)) {
                $request = new Message\Request($method, '', $url);
            }
            else {
                $request = new Message\Form\FormRequest($method, '', $url);
                $request->addFields($parameters);
            }

            $response = new Message\Response();

            $client = new Client\Curl();

            $client->send($request, $response);

            $this->responseHttpStatusCode = $response->getStatusCode();

            return json_decode($response->getContent());
        }
        catch (\Exception $e) {
            throw new AlfaRequestException($e->getMessage(), $e->getCode());
        }
    }
}