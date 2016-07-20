# Openpp Message Bundle

FOSMessageBundle + SonataAdminBundle

fos_message:
    db_driver:          orm
    thread_class:       Application\Openpp\MessageBundle\Entity\Thread
    message_class:      Application\Openpp\MessageBundle\Entity\Message

openpp_message:
    monitoring_default: true
    user_class:         Application\Sonata\UserBundle\Entity\User
