<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\WordRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

final class WordsController
{
    #[Route('/words/find/{string}', name: 'dictionary_word_find')]
    public function find(WordRepository $wordRepository, string $string): JsonResponse
    {
        return new JsonResponse([
            'string' => $string,
            'status' => 'ok',
        ]);
    }
}
