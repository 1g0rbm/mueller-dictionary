<?php

declare(strict_types=1);

use App\Service\DictionaryParser\PartOfSpeechNormalizer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @internal
 * @group unit
 */
final class PartOfSpeechNormalizerUnitTest extends KernelTestCase
{
    public PartOfSpeechNormalizer $service;

    protected function setUp(): void
    {
        $this->service = new PartOfSpeechNormalizer();
    }

    /**
     * @dataProvider transcriptions
     */
    public function testNormalizeReturnValid(string $testing, string $expected): void
    {
        self::assertEquals($expected, $this->service->normalize($testing));
    }

    /**
     * @return string[][]
     */
    public function transcriptions(): array
    {
        return [
            ['_a.', 'adjective'],
            ['_adv.', 'adverb'],
            ['_attr.', 'attributively'],
            ['_int.', 'interjection'],
            ['_prep.', 'preposition'],
            ['_suff.', 'suffix'],
            ['_pres.', 'present'],
            ['_v.', 'verb'],
            ['_sup.', 'superlative'],
            ['_pref.', 'prefix'],
            ['_n.', 'noun'],
            ['_pron.', 'pronoun'],
            ['_poss.', 'possessive (pronoun)'],
            ['_demonstr.', 'demonstrative (pronoun)'],
            ['_cj.', 'conjunction'],
            ['_p.', 'past indefinite'],
            ['_pl.', 'plural'],
            ['_obj.', 'objective case'],
            ['_comp.', 'comparative degree'],
        ];
    }
}
