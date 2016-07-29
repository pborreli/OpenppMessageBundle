<?php
namespace Openpp\MessageBundle\Model;

//use Doctrine\ORM\Mapping as ORM;
//use Doctrine\Common\Collections\ArrayCollection;
use FOS\MessageBundle\Entity\Thread as AbstractedThread;
use FOS\MessageBundle\Model\ParticipantInterface;
use Doctrine\Common\Collections\Criteria;

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
     * Returns thread isspam list.
     *
     * @return array
     */
    public static function getIsSpamList()
    {
        return array(
                true => 'yes',
                false => 'no',
        );
    }
    
    /**
     * Returns thread isspam label.
     *
     * @return int|null
     */
    public function getIsSpamLabel()
    {
        $list = self::getIsSpamList();
    
        return isset($list[$this->getIsSpam()]) ? $list[$this->getIsSpam()] : null;
    }

    /**
     * 自分が送信したものはフィルタリングしない。
     * 他人が送信したものは、監視承認状態(state: 0)のメッセージ以外、フィルタリングする。
     *
     * @param ParticipantInterface $sender
     * @return \Doctrine\Common\Collections\Collection|Message[]
     */
    public function filterMessages(ParticipantInterface $sender)
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq("sender", $sender))->orWhere(Criteria::expr()->eq('state', Message::STATUS_VALID));
        return $this->messages->matching($criteria); 
    }
}