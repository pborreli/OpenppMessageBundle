<?php
namespace Openpp\MessageBundle\EntityManager;

use FOS\MessageBundle\EntityManager\ThreadManager as BaseThreadManager;

class ThreadManager extends BaseThreadManager
{
    private $config;

    /**
     * @param mixed $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }
}