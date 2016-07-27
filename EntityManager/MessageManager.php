<?php
namespace Openpp\MessageBundle\EntityManager;

use FOS\MessageBundle\EntityManager\MessageManager as BaseMessageManager;
use FOS\MessageBundle\Model\MessageInterface;

class MessageManager extends BaseMessageManager
{
    private $config;

    /**
     * @param mixed $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * Saves a message
     *
     * @param MessageInterface $message
     * @param Boolean $andFlush Whether to flush the changes (default true)
     */
    public function saveMessage(MessageInterface $message, $andFlush = true)
    {
        if(isset($this->config['monitoring_enable']) && $this->config['monitoring_enable'] === true)
        {
            $message->setState($this->config['monitoring_default']);
        }

        $this->denormalize($message);
        $this->em->persist($message);
        if ($andFlush) {
            $this->em->flush();
        }
    }
}