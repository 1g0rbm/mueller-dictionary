<?php

declare(strict_types=1);

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Word;

/**
 * @internal
 * @group functional
 */
final class DictionaryApiFunctionalTest extends ApiTestCase
{
    public function testWordReturnValidResponse(): void
    {
        self::createClient()->request('GET', '/dictionary_api/words');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/dictionary_api/contexts/Word',
            '@id' => '/dictionary_api/words',
            '@type' => 'hydra:Collection',
            'hydra:member' => [
                [
                    '@id' => '/dictionary_api/words/1',
                    '@type' => 'Word',
                    'id' => 1,
                    'source' => 'abecedarian',
                    'pos' => 'adjective',
                    'transcription' => '[,eɪbi:si:ˈdɛərɪən]',
                    'translations' => [
                        'расположенный в алфавитном порядке',
                        'азбучный',
                        'элементарный',
                    ],
                ],
                [
                    '@id' => '/dictionary_api/words/2',
                    '@type' => 'Word',
                    'id' => 2,
                    'source' => 'beside',
                    'pos' => 'preposition',
                    'transcription' => '[bɪˈsaɪd]',
                    'translations' => [
                        'рядом с',
                        'около',
                        'близ',
                        'beside the river у реки',
                        'по сравнению с',
                        'she seems dull beside her sister по сравнению со своей сестрой она кажется неинтересной',
                        'мимо',
                        'beside the mark',
                        'beside the question мимо цели',
                        'некстати',
                        'не по существу',
                        'beside the purpose нецелесообразно',
                        '_редк. кроме, помимо *) beside oneself вне себя',
                    ],
                ],
                [
                    '@id' => '/dictionary_api/words/3',
                    '@type' => 'Word',
                    'id' => 3,
                    'source' => 'betrayer',
                    'pos' => 'noun',
                    'transcription' => '[bɪˈtreɪə]',
                    'translations' => [
                        'предатель',
                        'изменник',
                    ],
                ],
                [
                    '@id' => '/dictionary_api/words/4',
                    '@type' => 'Word',
                    'id' => 4,
                    'source' => 'cage',
                    'pos' => 'verb',
                    'transcription' => '[keɪʤ]',
                    'translations' => [
                        'сажать в клетку',
                        '_разг. заключать в тюрьму',
                    ],
                ],
                [
                    '@id' => '/dictionary_api/words/5',
                    '@type' => 'Word',
                    'id' => 5,
                    'source' => 'debit',
                    'pos' => 'verb',
                    'transcription' => '[ˈdebɪt]',
                    'translations' => [
                        'дебетовать',
                        'вносить в дебет',
                    ],
                ],
            ],
            'hydra:totalItems' => 5,
            'hydra:search' => [
                '@type' => 'hydra:IriTemplate',
                'hydra:template' => '/dictionary_api/words{?source}',
                'hydra:variableRepresentation' => 'BasicRepresentation',
                'hydra:mapping' => [
                    [
                        '@type' => 'IriTemplateMapping',
                        'variable' => 'source',
                        'property' => 'source',
                        'required' => false,
                    ],
                ],
            ],
        ]);

        $this->assertMatchesResourceCollectionJsonSchema(Word::class);
    }
}
