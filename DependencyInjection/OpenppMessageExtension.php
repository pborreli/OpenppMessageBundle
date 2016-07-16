<?php
namespace Openpp\MessageBundle\DependencyInjection;

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

        // Many-To-One Bidirectional for Message and Thread
        $collector->addAssociation($config['class']['message'], 'mapManyToOne', array(
            'fieldName'     => 'thread',
            'targetEntity'  => $config['class']['thread'],
            'inversedBy' => 'messages',
        ));
        // Many-To-One Unidirectional for Message and User
        $collector->addAssociation($config['class']['message'], 'mapManyToOne', array(
            'fieldName'     => 'sender',
            'targetEntity'  => $config['class']['user'],
        ));
        // One-To-Many Bidirectional for Message and MessageMetadata
        $collector->addAssociation($config['class']['message'], 'mapOneToMany', array(
            'fieldName' => 'metadata',
            'targetEntity' => $config['class']['message_metadata'],
            'cascade' => array(
                'all',
            ),
            'mappedBy' => 'message',
        ));
        // Many-To-One Bidirectional for MessageMetaData and Message
        $collector->addAssociation($config['class']['message_metadata'], 'mapManyToOne', array(
            'fieldName'     => 'message',
            'targetEntity'  => $config['class']['message'],
            'inversedBy' => 'metadata',
        ));
        // Many-To-One Unidirectional for MessageMetaData and User
        $collector->addAssociation($config['class']['message_metadata'], 'mapManyToOne', array(
            'fieldName'     => 'participant',
            'targetEntity'  => $config['class']['user'],
        ));

        // Many-To-One Unidirectional for Thread and User
        $collector->addAssociation($config['class']['thread'], 'mapManyToOne', array(
            'fieldName'     => 'createdBy',
            'targetEntity'  => $config['class']['user'],
        ));

        // One-To-Many Unidirectional for Thread and Message
        $collector->addAssociation($config['class']['thread'], 'mapOneToMany', array(
            'fieldName' => 'messages',
            'targetEntity' => $config['class']['message'],
            'mappedBy' => 'thread',
        ));
        // One-To-Many Unidirectional for Thread and ThreadMetadata
        $collector->addAssociation($config['class']['thread'], 'mapOneToMany', array(
            'fieldName' => 'metadata',
            'targetEntity' => $config['class']['thread_metadata'],
            'cascade'       => array(
                'all',
            ),
            'mappedBy' => 'thread',
        ));
        // One-To-Many Unidirectional for ThreadMetadata and Thread
        $collector->addAssociation($config['class']['thread_metadata'], 'mapManyToOne', array(
            'fieldName' => 'thread',
            'targetEntity' => $config['class']['thread'],
            'inversedBy' => 'metadata',
        ));
        // One-To-Many Unidirectional for ThreadMetadata and User
        $collector->addAssociation($config['class']['thread_metadata'], 'mapManyToOne', array(
            'fieldName' => 'participant',
            'targetEntity' => $config['class']['user'],
        ));
    }
}