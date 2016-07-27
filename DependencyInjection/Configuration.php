<?php
namespace Openpp\MessageBundle\DependencyInjection;

use Openpp\MessageBundle\Model\Message;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('openpp_message');
        $node
            ->children()
                ->scalarNode('monitoring_enable')->defaultValue(true)->end()
                ->scalarNode('monitoring_default')->defaultValue(null)->end()
                ->scalarNode('user_class')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
