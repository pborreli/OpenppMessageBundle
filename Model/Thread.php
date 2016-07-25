<?php
namespace Openpp\MessageBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\MessageBundle\Entity\Thread as AbstractedThread;

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
     * @see FOS\MessageBundle\Model\ThreadInterface::getLastMessage()
     */
    public function getLastMessage()
    {
        return $this->getMessages()->last();
    }

    /**
     * @see FOS\MessageBundle\Model\ThreadInterface::getMessages()
     */
    public function getMessages()
    {
        if($this->messages->last()){
            $filterd = $this->messages->filter(function($v){
                if($v->getState() == 1)
                {
                    return true;
                }
            });
            return $filterd;
        }
        return $this->messages;
    }
}