<?php
namespace Openpp\MessageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('openpp_message');
        $supportedManagerTypes = array('orm');

        $node
            ->children()
                ->booleanNode('monitoring_default')
                    ->defaultValue(true)
                ->end()
                ->scalarNode('db_driver')
                    ->defaultValue('orm')
                        ->validate()
                        ->ifNotInArray($supportedManagerTypes)
                        ->thenInvalid('The db_driver %s is not supported. Please choose one of ' . json_encode($supportedManagerTypes))
                    ->end()
                ->end()
                ->arrayNode('class')
                    ->children()
                        ->scalarNode('user')->cannotBeEmpty()->end()
                        ->scalarNode('message')->cannotBeEmpty()->end()
                        ->scalarNode('message_metadata')->cannotBeEmpty()->end()
                        ->scalarNode('thread')->cannotBeEmpty()->end()
                        ->scalarNode('thread_metadata')->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}