# Openpp Message Bundle

FOSMessageBundle + SonataAdminBundle

install
--------

    composer require openpp/message-bundle
    composer update

Enable Bundle
-------------

            // FOS MESSAGE
            new FOS\MessageBundle\FOSMessageBundle(),
            new Openpp\MessageBundle\OpenppMessageBundle(),


eazy-extends
------------

    php app/console sonata:eazy-extends:generate -d=src OpenppMessageBundle


Append Bundle
-------------

            new Application\Openpp\MessageBundle\ApplicationOpenppMessageBundle()

config
------

    fos_message:
        thread_class:       Application\Openpp\MessageBundle\Entity\Thread
        message_class:      Application\Openpp\MessageBundle\Entity\Message
    openpp_message:
        monitoring_default: true
        user_class:         Application\Sonata\UserBundle\Entity\User

schema
------

    php app/console cache:clear
    php app/console doctrine:schema:update --dump-sql
    php app/console doctrine:schema:update --force

routing
--------

    fos_message:
        resource: "@FOSMessageBundle/Resources/config/routing.xml"
        prefix: /message
