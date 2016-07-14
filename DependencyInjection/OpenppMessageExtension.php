<?php
namespace Openpp\MessageBundle;

use Sonata\EasyExtendsBundle\Mapper\DoctrineCollector;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class OpenppMessageExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->registerDoctrineMapping($config);
    }

    /**
     * @param array $config
     */
    protected function registerDoctrineMapping(array $config)
    {
        $collector = DoctrineCollector::getInstance();

        // One-To-Many Bidirectional for Application and User
        $collector->addAssociation($config['class']['message'], 'mapOneToMany', array(
            'fieldName'     => 'users',
            'targetEntity'  => $config['class']['user'],
            'cascade'       => array(
                'remove',
                'persist',
            ),
            'mappedBy'      => 'application',
            'orphanRemoval' => false,
        ));
    }
}