<?php

declare(strict_types=1);

namespace App\Exception\DictionaryParser;

use Exception;

abstract class DictionaryParserException extends Exception
{
    protected string $moduleName = 'dictionary parser';
}
