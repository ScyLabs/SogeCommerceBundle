<?php

namespace ScyLabs\SogeCommerceBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('scy_labs_soge_commerce');
    
        $rootNode
            ->children()
                ->scalarNode('site_id')->end()
                ->scalarNode('mode')->end()
                ->scalarNode('currency')->defaultValue(978)->end()
                ->scalarNode('return_route')->end()
                ->scalarNode('cancel_route')->end()
                ->scalarNode('test_certificate')->end()
                ->scalarNode('prod_certificate')->end()
                
            ->end();

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
