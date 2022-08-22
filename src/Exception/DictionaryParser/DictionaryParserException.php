<?php

declare(strict_types=1);

namespace App\Exception\DictionaryParser;

use Exception;

class DictionaryParserException extends Exception
{
    protected string $moduleName = 'dictionary parser';
}
