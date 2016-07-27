<?php

namespace Openpp\MessageBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\MessageBundle\Controller\MessageController as BaseMessageController;
use FOS\MessageBundle\Model\ParticipantInterface;

class MessageController extends BaseMessageController
{
    /**
     * Displays the authenticated participant inbox
     *
     * @return Response
     */
    public function inboxAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        if($user instanceof ParticipantInterface){
            $threads = $this->getProvider()->getInboxThreadsFilterd($user);
        }else{
            $threads = $this->getProvider()->getInboxThreads();
        }


        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:inbox.html.twig', array(
            'threads' => $threads
        ));
    }

    /**
     * Displays the authenticated participant messages sent
     *
     * @return Response
     */
    public function sentAction()
    {
        $threads = $this->getProvider()->getSentThreads();

        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:sent.html.twig', array(
            'threads' => $threads
        ));
    }

    /**
     * Displays a thread, also allows to reply to it
     *
     * @param string $threadId the thread id
     *
     * @return Response
     */
    public function threadAction($threadId)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        if($user instanceof ParticipantInterface){
            $thread = $this->getProvider()->getThreadFilterd($threadId, $user);
        }else{
            $thread = $this->getProvider()->getThread($threadId);
        }

        $form = $this->container->get('fos_message.reply_form.factory')->create($thread);
        $formHandler = $this->container->get('fos_message.reply_form.handler');

        if ($message = $formHandler->process($form)) {
            return new RedirectResponse($this->container->get('router')->generate('fos_message_thread_view', array(
                'threadId' => $message->getThread()->getId()
            )));
        }

        return $this->container->get('templating')->renderResponse('FOSMessageBundle:Message:thread.html.twig', array(
            'form' => $form->createView(),
            'thread' => $thread
        ));
    }
}
