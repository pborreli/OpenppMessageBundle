<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Openpp\MessageBundle\Renderer;

use Openpp\MessageBundle\Model\Message;
use Sonata\CoreBundle\Component\Status\StatusClassRendererInterface;

/**
 * Class MessageStatusRenderer.
 *
 * @author Vincent Composieux <vincent.composieux@gmail.com>
 */
class MessageStatusRenderer implements StatusClassRendererInterface
{
    /**
     * {@inheritdoc}
     */
    public function handlesObject($object, $statusName = null)
    {
        return $object instanceof Message
            && in_array($statusName, array('moderate', 'invalid', 'valid', null));
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusClass($object, $statusName = null, $default = '')
    {
        switch ($object->getState()) {
            case Message::STATUS_MODERATE:
                return 'info';
            case Message::STATUS_VALID:
                return 'success';
            case Message::STATUS_INVALID:
                return 'important';
            default:
                return $default;
        }
    }
}
