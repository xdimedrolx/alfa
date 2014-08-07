<?php
/**
 * Class AlfaRequestException
 *
 * User: xdimedrolx
 * Date: 05.08.14
 * Time: 16:02
 */

namespace Alfa\Exceptions;

class AlfaRequestException extends AlfaException
{
    public function __construct($message, $code)
    {
        parent::__construct($message, $code, null);
    }
}