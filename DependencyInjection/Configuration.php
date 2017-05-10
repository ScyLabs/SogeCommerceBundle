<?php

namespace Mdespeuilles\SogeCommerceBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('mdespeuilles_soge_commerce');
    
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('site_id')->defaultValue(null)->end()
                ->scalarNode('mode')->defaultValue(null)->end()
                ->scalarNode('currency')->defaultValue(null)->end()
                ->scalarNode('return_route')->defaultValue(null)->end()
                ->scalarNode('cancel_route')->defaultValue(null)->end()
                ->scalarNode('test_certificate')->defaultValue(null)->end()
                ->scalarNode('prod_certificate')->defaultValue(null)->end()
                /*->arrayNode('files_folder')
                    ->useAttributeAsKey('id')
                    ->prototype('array')
                    ->children()
                        ->scalarNode('path')->defaultValue(null)->end()
                    ->end()
                ->end()
            ->end()*/
            ->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
