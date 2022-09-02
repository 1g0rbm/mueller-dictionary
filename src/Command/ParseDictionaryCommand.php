<?php

declare(strict_types=1);

namespace App\Command;

use App\Exception\DictionaryParser\DictionaryFileNotFoundException;
use App\Service\DictionaryParser\DictionaryParser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:dictionary:parse')]
final class ParseDictionaryCommand extends Command
{
    private DictionaryParser $dictionaryParser;

    public function __construct(DictionaryParser $dictionaryParser)
    {
        parent::__construct();
        $this->dictionaryParser = $dictionaryParser;
    }

    /**
     * @throws DictionaryFileNotFoundException
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        ini_set('memory_limit', '-1');
        $this->dictionaryParser->parse(
            __DIR__ . '/../../config/dictionary/mueller-base',
            100
        );

        return 0;
    }
}
