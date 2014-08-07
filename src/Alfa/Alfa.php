<?php 

namespace Alfa;

use Alfa\HttpClients\AlfaHttpClientInterface;
use Alfa\Exceptions\AlfaResponseException;
use Alfa\HttpClients\AlfaHttpfulHttpClient;

class Alfa
{
	const VERSION = '0.0.1';

	private $baseUrl = 'https://test.paymentgate.ru/testpayment/rest';

	private $login;
	private $password;

    private static $httpClientHandler;

	public function __construct($login, $password, $baseUrl = '')
	{
		$this->login = $login;
		$this->password = $password;

        if (!empty($baseUrl)) {
            $this->baseUrl = $baseUrl;
        }
	}

    public function setBaseUrl($url)
    {
        $this->baseUrl = $url;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public static function setHttpClientHandler(AlfaHttpClientInterface $handler)
    {
        static::$httpClientHandler = $handler;
    }

    public static function getHttpClientHandler()
    {
        if (static::$httpClientHandler) {
            return static::$httpClientHandler;
        }
        return new HttpClients\AlfaBuzzHttpClient();
    }

	/**
	 * Returns the base URL
	 *
	 * @return string 
	 */
	protected function getRequestUrl($path)
	{
		return $this->baseUrl . '/' . $path;
	}

    protected function getParameters($params)
    {
        if (!isset($params['userName'])) {
            $params['userName'] = $this->login;
        }
        if (!isset($params['password'])) {
            $params['password'] = $this->password;
        }
        return $params;
    }


    /**
     * @todo рассмотреть если $result === null
     * @param $path
     * @param array $params
     * @param string $method
     */
    public function request($path, $params = array(), $method = 'GET')
	{
        $url = $this->getRequestUrl($path);
        $params = $this->getParameters($params);

        if ($method === 'GET' && !empty($params)) {
            $url = self::appendParamsToUrl($url, $params);
            $params = array();
        }

        $connection = self::getHttpClientHandler();


        $result = $connection->send($url, $params, $method);

        if (isset($result->errorCode) && (int) $result->errorCode !== 0) {
            throw new AlfaResponseException($result);
        }

        return $result;
	}

    public static function appendParamsToUrl($url, $params = array())
    {
        if (!$params) {
            return $url;
        }

        if (strpos($url, '?') === false) {
            return $url . '?' . http_build_query($params, null, '&');
        }

        list($path, $queryString) = explode('?', $url, 2);
        parse_str($queryString, $queryArray);

        $params = array_merge($params, $queryArray);

        return $path . '?' . http_build_query($params, null, '&');
    }
}