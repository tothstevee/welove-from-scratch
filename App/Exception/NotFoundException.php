<?php

namespace App\Exception;

/**
 * Class NotFoundException
 * 
 * @author Tóth István
 */

class NotFoundException extends \Exception
{
    protected $message = 'Page not found';
    protected $code = 404;
}