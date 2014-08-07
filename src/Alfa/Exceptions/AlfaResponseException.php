<?php

namespace Alfa\Exceptions;

class AlfaResponseException extends AlfaException
{
    /**
     * @var array Raw response
     */
    private $rawResponse;

    /**
     * @param stdClass $rawResponse
     */
    public function __construct($rawResponse)
    {
        $this->rawResponse = $rawResponse;
        $this->code = (int) $this->get('errorCode', -1);
        $this->message = $this->get('errorMessage', 'Unknown message');
        $this->message = mb_convert_encoding($this->message, 'utf-8', mb_detect_encoding($this->message));
        parent::__construct($this->message, $this->code, null);
    }

    protected function get($key, $default = null)
    {
        if (isset($this->rawResponse->$key)) {
            return $this->rawResponse->$key;
        }
        return $default;
    }

}