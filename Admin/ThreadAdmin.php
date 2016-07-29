<?php

namespace Openpp\MessageBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;

class ThreadAdmin extends AbstractAdmin
{
    public $baseRouteName = 'admin_openpp_message_thread';
    public $baseRoutePattern = 'openpp/message/thread';
    
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('createdAt')
            ->add('id')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('subject')
            ->add('createdAt')
            ->add('isspam', 'string', array('template' => 'OpenppMessageBundle:ThreadAdmin:list_status.html.twig'))
            ->add('createdBy')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('subject')
            ->add('isspam', 'openpp_message_isspam', array('translation_domain' => 'OpenppMessageBundle'))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('subject')
            ->add('createdAt')
            ->add('isspam')
            ->add('createdBy')
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, array('edit'))) {
            return;
        }
    
        $admin = $this->isChild() ? $this->getParent() : $this;
    
        $id = $admin->getRequest()->get('id');
    
        $menu->addChild(
                $this->trans('admin_edit', array(), 'OpenppMessageBundle'),
                array('uri' => $admin->generateUrl('edit', array('id' => $id)))
                );
    
        $menu->addChild(
                $this->trans('admin_view_messages', array(), 'OpenppMessageBundle'),
                array('uri' => $admin->generateUrl('openpp.message.admin.message.list', array('id' => $id)))
                );
    }
}
