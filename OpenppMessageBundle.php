<?php
namespace Openpp\MessageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Sonata\CoreBundle\Form\FormHelper;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OpenppMessageBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'FOSMessageBundle';
    }
    
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $this->registerFormMapping();
    }
    
    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->registerFormMapping();
    }
    
    /**
     * Register form mapping information.
     */
    public function registerFormMapping()
    {
        FormHelper::registerFormTypeMapping(array(
                'openpp_message_status' => 'Openpp\MessageBundle\Form\Type\MessageStatusType',
        ));
    }
}