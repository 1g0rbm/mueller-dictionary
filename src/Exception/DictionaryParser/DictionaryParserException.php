<?php

declare(strict_types=1);

namespace App\Exception\DictionaryParser;

use Exception;

abstract class DictionaryParserException extends Exception
{
    protected string $moduleName = 'dictionary parser';

    public function __toString()
    {
        return sprintf('[%s] %s', $this->moduleName, $this->message);
    }

    final public function getModuleName(): string
    {
        return $this->moduleName;
    }
}
