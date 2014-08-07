<?php 

namespace Alfa\HttpClients;

interface AlfaHttpClientInterface 
{
    /**
     * @param $url
     * @param array $parameters
     * @param string $method
     * @return stdClass
     */
    public function send($url, $parameters = array(), $method = 'GET');
}