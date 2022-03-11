<?php

namespace Trexima\EuropeanCvBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('trexima_european_cv');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('upload_url')->defaultValue('')->end()
                ->scalarNode('upload_dir')->defaultValue('')->end()
                ->scalarNode('user_class')->defaultValue('')->end()
            ->end();

        return $treeBuilder;
    }
}