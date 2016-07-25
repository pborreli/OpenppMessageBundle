<?php
namespace Openpp\MessageBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Entity\MessageMetadata as AbstractedMessageMetadata;

/**
 *
 */
class MessageMetadata extends AbstractedMessageMetadata
{
    /**
     *
     */
    protected $id;

    /**
     * @var \FOS\MessageBundle\Model\MessageInterface
     */
    protected $message;

    /**
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $participant;
}