<?php

declare(strict_types=1);

namespace Incognito\Exception;

use Exception;
use Aws\Exception\AwsException;

class ExceptionFactory
{
    public static function make(AwsException $e): Exception
    {
        $className = $e->getAwsErrorCode();
        $fqnCandidate = "\\Incognito\\Exception\\$className";

        if (class_exists($fqnCandidate, true)) {
            return new $fqnCandidate($e);
        }

        return $e;
    }
}
