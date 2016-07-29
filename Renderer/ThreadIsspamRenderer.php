<?php

namespace Openpp\MessageBundle\Renderer;

use Openpp\MessageBundle\Model\Thread;
use Sonata\CoreBundle\Component\Status\StatusClassRendererInterface;

/**
 * Class ThreadIsspamRenderer.
 *
 * @author Vincent Composieux <vincent.composieux@gmail.com>
 */
class ThreadIsspamRenderer implements StatusClassRendererInterface
{
    /**
     * {@inheritdoc}
     */
    public function handlesObject($object, $statusName = null)
    {
        return $object instanceof Thread
            && in_array($statusName, array('yes', 'no', null));
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusClass($object, $statusName = null, $default = true)
    {
        switch ($object->getIsspam()) {
            case true:
                return 'danger';
            case false:
                return 'success';
            default:
                return $default;
        }
    }
}
