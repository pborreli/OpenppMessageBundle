<?php
namespace Openpp\MessageBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Entity\Message as AbstractedMessage;

/**
 *
 */
class Message extends AbstractedMessage
{
    /**
     * Available message status.
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
    
    /**
     * @return string
     */
    public function __toString()
    {
        return 'Message message #'.$this->getId();
    }
    
    /**
     * Returns comment state list.
     *
     * @return array
     */
    public static function getStateList()
    {
        return array(
                self::STATUS_VALID => 'valid',
                self::STATUS_INVALID => 'invalid',
                self::STATUS_MODERATE => 'moderate',
        );
    }
    
    /**
     * Returns comment state label.
     *
     * @return int|null
     */
    public function getStateLabel()
    {
        $list = self::getStateList();
    
        return isset($list[$this->getState()]) ? $list[$this->getState()] : null;
    }
}
