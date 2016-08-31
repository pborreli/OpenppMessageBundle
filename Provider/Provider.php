<?php
namespace Openpp\MessageBundle\Provider;

use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\ModelManager\MessageManagerInterface;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use FOS\MessageBundle\Provider\Provider as BaseProvider;
use FOS\MessageBundle\Reader\ReaderInterface;
use FOS\MessageBundle\Security\AuthorizerInterface;
use FOS\MessageBundle\Security\ParticipantProviderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class Provider extends BaseProvider
{
    /**
     * @var array
     */
    protected $config = array();


    /**
     * Provider constructor.
     * @param ThreadManagerInterface $threadManager
     * @param MessageManagerInterface $messageManager
     * @param ReaderInterface $threadReader
     * @param AuthorizerInterface $authorizer
     * @param ParticipantProviderInterface $participantProvider
     * @param array $config
     */
    public function __construct(ThreadManagerInterface $threadManager, MessageManagerInterface $messageManager, ReaderInterface $threadReader, AuthorizerInterface $authorizer, ParticipantProviderInterface $participantProvider, $config)
    {
        parent::__construct($threadManager, $messageManager, $threadReader, $authorizer, $participantProvider);
        $this->config = $config;
    }

    /**
     * 受信したメッセージのなかで未承認のものをフィルタリングする。
     *
     * @param ParticipantInterface $user
     * @return array of ThreadInterface
     */
    public function getInboxThreadsFilterd(ParticipantInterface $user)
    {
        $participant = $this->getAuthenticatedParticipant();

        $threads = $this->threadManager->findParticipantInboxThreads($participant);

        if($this->config['monitoring_enable'])
        {
            $filterd = array();
            foreach($threads as $thread)
            {
                $filterdThread = $thread->filterMessages($user);
                if($filterdThread->getMessages()->count())
                {
                    $filterd[] = $thread;
                }
            }
            return $filterd;
        }
        else
        {
            return $threads;
        }
    }

    /**
     * Gets a thread by its ID
     * Performs authorization checks
     * Marks the thread as read
     *
     * @param int $threadId
     * @param ParticipantInterface $user
     * @return ThreadInterface
     * @throws NotFoundHttpException
     * @throws AccessDeniedException
     */
    public function getThreadFilterd($threadId, ParticipantInterface $user)
    {
        $thread = $this->threadManager->findThreadById($threadId);
        if (!$thread) {
            throw new NotFoundHttpException('There is no such thread');
        }
        if (!$this->authorizer->canSeeThread($thread)) {
            throw new AccessDeniedException('You are not allowed to see this thread');
        }
        // Load the thread messages before marking them as read
        // because we want to see the unread messages
        $thread->getMessages();
        $this->threadReader->markAsRead($thread);

        if($this->config['monitoring_enable'])
        {
            $thread->filterMessages($user);
        }

        return $thread;
    }
}
