<?php
namespace Openpp\MessageBundle\DependencyInjection;

use Sonata\EasyExtendsBundle\Mapper\DoctrineCollector;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use FOS\MessageBundle\DependencyInjection\Configuration as FOSMessageConfiguration;

class OpenppMessageExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @var array
     */
    private $config = null;

    public function prepend(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig('fos_message');
        if(isset($configs[0]) && $configs[0] == null)
        {
            $configs[0]= [
                'db_driver' => 'orm',
                'thread_class' => 'FOS\MessageBundle\Entity\Thread',
                'message_class' => 'FOS\MessageBundle\Entity\Message'
            ];
            $this->config = $this->processConfiguration(new FOSMessageConfiguration(), $configs);
            $container->prependExtensionConfig('fos_message',$configs[0]);
        }
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $parameterBag = $container->getParameterBag();
        $configs = $parameterBag->resolveValue($configs);
        $configs_openpp = $this->processConfiguration(new Configuration(), $configs);

        if(isset($configs_openpp['user_class']) && $configs_openpp['user_class'] != null)
        {
            if(class_exists($this->config['message_class']) && class_exists($this->config['thread_class']))
            {
                $this->config['message_metadata_class'] = sprintf('%s%s', $this->config['message_class'], 'Metadata');
                $this->config['thread_metadata_class'] = sprintf('%s%s', $this->config['thread_class'], 'Metadata');
                $this->registerDoctrineMapping(array_merge($this->config, $configs_openpp));
            }
        }
    }

    /**
     * @param array $config
     */
    protected function registerDoctrineMapping(array $config)
    {
        $collector = DoctrineCollector::getInstance();

        // Many-To-One Bidirectional for Message and Thread
        $collector->addAssociation($config['message_class'], 'mapManyToOne', array(
            'fieldName'     => 'thread',
            'targetEntity'  => $config['thread_class'],
            'inversedBy' => 'messages',
        ));
        // Many-To-One Unidirectional for Message and User
        $collector->addAssociation($config['message_class'], 'mapManyToOne', array(
            'fieldName'     => 'sender',
            'targetEntity'  => $config['user_class'],
        ));
        // One-To-Many Bidirectional for Message and MessageMetadata
        $collector->addAssociation($config['message_class'], 'mapOneToMany', array(
            'fieldName' => 'metadata',
            'targetEntity' => $config['message_metadata_class'],
            'cascade' => array(
                'all',
            ),
            'mappedBy' => 'message',
        ));
        // Many-To-One Bidirectional for MessageMetaData and Message
        $collector->addAssociation($config['message_metadata_class'], 'mapManyToOne', array(
            'fieldName'     => 'message',
            'targetEntity'  => $config['message_class'],
            'inversedBy' => 'metadata',
        ));
        // Many-To-One Unidirectional for MessageMetaData and User
        $collector->addAssociation($config['message_metadata_class'], 'mapManyToOne', array(
            'fieldName'     => 'participant',
            'targetEntity'  => $config['user_class'],
        ));

        // Many-To-One Unidirectional for Thread and User
        $collector->addAssociation($config['thread_class'], 'mapManyToOne', array(
            'fieldName'     => 'createdBy',
            'targetEntity'  => $config['user_class'],
        ));

        // One-To-Many Unidirectional for Thread and Message
        $collector->addAssociation($config['thread_class'], 'mapOneToMany', array(
            'fieldName' => 'messages',
            'targetEntity' => $config['message_class'],
            'mappedBy' => 'thread',
        ));
        // One-To-Many Unidirectional for Thread and ThreadMetadata
        $collector->addAssociation($config['thread_class'], 'mapOneToMany', array(
            'fieldName' => 'metadata',
            'targetEntity' => $config['thread_metadata_class'],
            'cascade'       => array(
                'all',
            ),
            'mappedBy' => 'thread',
        ));
        // One-To-Many Unidirectional for ThreadMetadata and Thread
        $collector->addAssociation($config['thread_metadata_class'], 'mapManyToOne', array(
            'fieldName' => 'thread',
            'targetEntity' => $config['thread_class'],
            'inversedBy' => 'metadata',
        ));
        // One-To-Many Unidirectional for ThreadMetadata and User
        $collector->addAssociation($config['thread_metadata_class'], 'mapManyToOne', array(
            'fieldName' => 'participant',
            'targetEntity' => $config['user_class'],
        ));
    }

}