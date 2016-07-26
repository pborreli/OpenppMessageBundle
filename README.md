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
        monitoring_enable:  true
        monitoring_default: 0
        user_class:         Application\Sonata\UserBundle\Entity\User


monitoring_enable を true にすることで、監視を有効にします。
監視が有効になると、 message の stats の値が STATUS_VALID(0) の場合のみ表示されます。
STATUSは3つあり、デフォルト値として monitoring_default に指定できます。
また、指定しない場合は null が入ります。

    const STATUS_VALID = 0;
    const STATUS_INVALID = 1;
    const STATUS_MODERATE = 2;

schema
------

    php app/console cache:clear
    php app/console doctrine:schema:update --dump-sql
    php app/console doctrine:schema:update --force

routing
--------

    fos_message:
        resource: "@OpenppMessageBundle/Resources/config/routing.xml"
        prefix: /message

sonata admin
------------

            openpp.message.admin.message:
                label:            Message
                icon:            '<i class="fa fa-weixin"></i>'
                items:
                    - openpp.message.admin.message