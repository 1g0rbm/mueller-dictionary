<?php

declare(strict_types=1);

namespace App\DependencyInjection\Compiler;

use App\Service\DictionaryParser\TextTypeParserRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/** @psalm-suppress UnusedForeachValue */
final class TextTypeParserPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(TextTypeParserRegistry::class)) {
            return;
        }

        $definition = $container->findDefinition(TextTypeParserRegistry::class);
        $services   = $container->findTaggedServiceIds('text_type.parser');

        foreach ($services as $id => $tags) {
            $definition->addMethodCall('addParser', [new Reference($id)]);
        }
    }
}
