<?php

namespace C2is\Bundle\OtaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ota');

        $rootNode
            ->children()
                ->arrayNode('requestor')
                    ->children()
                        ->scalarNode('id')->isRequired()->end()
                        ->scalarNode('type')->isRequired()->end()
                    ->end()
                ->end()
                ->arrayNode('ota')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('version')->isRequired()->cannotBeEmpty()->defaultValue('2006')->end()
                        ->scalarNode('namespace')->isRequired()->cannotBeEmpty()->defaultValue('http://www.opentravel.org/OTA/2006/01')->end()
                    ->end()
                ->end()
                ->scalarNode('company_name')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('target')->isRequired()->cannotBeEmpty()->defaultValue('Test')->end()
            ->end();

        return $treeBuilder;
    }
}
