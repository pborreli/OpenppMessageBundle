<?php
namespace Openpp\MessageBundle\Provider;

use FOS\MessageBundle\ModelManager\MessageManagerInterface;
use FOS\MessageBundle\ModelManager\ThreadManagerInterface;
use FOS\MessageBundle\Provider\Provider as BaseProvider;
use FOS\MessageBundle\Reader\ReaderInterface;
use FOS\MessageBundle\Security\AuthorizerInterface;
use FOS\MessageBundle\Security\ParticipantProviderInterface;

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
     * Gets the thread in the inbox of the current user
     *
     * @return array of ThreadInterface
     */
    public function getInboxThreads()
    {
        $participant = $this->getAuthenticatedParticipant();

        $threads = $this->threadManager->findParticipantInboxThreads($participant);

        $filterd = array();
        if($this->config['monitoring_default'])
        {
            foreach($threads as $thread)
            {
                if($thread->getMessages())
                {
                    $filterd[] = $thread;
                }
            }
        }
        else
        {
            $filterd = $threads;
        }
        return $filterd;
    }
}
