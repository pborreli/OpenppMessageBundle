<?php
namespace Openpp\MessageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OpenppMessageBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'FOSMessageBundle';
    }
}