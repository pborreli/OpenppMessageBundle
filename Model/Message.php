<?php
namespace Openpp\MessageBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\MessageBundle\Entity\Message as AbstractedMessage;

/**
 *
 */
class Message extends AbstractedMessage
{
    /**
     * Available comment status.
     */
    const STATUS_VALID = 0;
    const STATUS_INVALID = 1;
    const STATUS_MODERATE = 2;

    protected $id;

    /**
     * @var \FOS\MessageBundle\Model\ThreadInterface
     */
    protected $thread;

    /**
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $sender;

    /**
     * @var MessageMetadata[]|\Doctrine\Common\Collections\Collection
     */
    protected $metadata;

    /**
     * @var integer
     */
    protected $state;

    /**
     * @return int
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param int $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }
}
