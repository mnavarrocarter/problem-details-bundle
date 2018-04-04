<?php

namespace MNC\Bundle\ProblemDetailsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mnc_problem_details');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('exception_normalizer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('normalize_in_dev')
                            ->defaultFalse()
                        ->end()
                        ->booleanNode('normalize_symfony_http')
                            ->defaultFalse()
                        ->end()
                        ->booleanNode('normalize_symfony_auth')
                            ->defaultFalse()
                        ->end()
                        ->booleanNode('normalize_doctrine')
                            ->defaultFalse()
                        ->end()
                        ->booleanNode('normalize_php')
                            ->defaultFalse()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
