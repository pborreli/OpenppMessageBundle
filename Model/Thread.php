<?php
namespace Openpp\MessageBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\MessageBundle\Entity\Thread as AbstractedThread;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 *
 */
class Thread extends AbstractedThread
{
    /**
     *
     */
    protected $id;

    /**
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $createdBy;

    /**
     * @var Message[]|\Doctrine\Common\Collections\Collection
     */
    protected $messages;

    /**
     * @var ThreadMetadata[]|\Doctrine\Common\Collections\Collection
     */
    protected $metadata;

    /**
     * @see FOS\MessageBundle\Model\ThreadInterface::getMessages()
     */
    public function getMessages()
    {
        return $this->messages;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return 'Message thread #'.$this->getId();
    }

    /**
     * 自分が送信したものはフィルタリングしない。
     * 他人が送信したものは、監視承認状態(state: 1)のメッセージ以外、フィルタリングする。
     *
     * @param ParticipantInterface $sender
     * @return \Doctrine\Common\Collections\Collection|Message[]
     */
    public function filterMessages(ParticipantInterface $sender)
    {
        $filterd = $this->messages->filter(function($message) use ($sender)
        {
            /* @var $message Message */
            if($message->getSender() == $sender)
            {
                return true;
            }
            else if($message->getState() === Message::STATUS_VALID)
            {
                return true;
            }
        });
        $this->messages = $filterd;
        return $this->messages;
    }
}