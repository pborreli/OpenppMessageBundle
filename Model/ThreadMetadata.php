<?php
namespace Openpp\MessageBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Entity\ThreadMetadata as AbstractedThreadMetadata;

/**
 *
 */
class ThreadMetadata extends AbstractedThreadMetadata
{
    /**
     *
     */
    protected $id;

    /**
     * @var \FOS\MessageBundle\Model\ThreadInterface
     */
    protected $thread;

    /**
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $participant;
}