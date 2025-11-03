<?php

namespace App\Exceptions;

use Exception;

class MissingModelCastException extends Exception
{
    public function __construct()
    {
        parent::__construct("Model class not defined for casting. Please set the protected 'model' property in the DataAccess class.");
    }

    /**
     * Report the exception.
     */
    public function report(): void
    {
        //
    }
}
