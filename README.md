# Openpp Message Bundle

FOSMessageBundle + SonataAdminBundle

install
--------

Command line

    composer require openpp/message-bundle
    composer update
    

config
------

app/config/config.yml

    fos_user:
        db_driver: orm # other valid values are 'mongodb', 'couchdb' and 'propel'
        firewall_name: main
        user_class:     Application\FOS\UserBundle\Entity\User
    
        group:
            group_class: FOS\UserBundle\Entity\Group
    
        registration:
            confirmation:
                enabled:    true
                from_email:
                    address:        registration@acmedemo.com
                    sender_name:    openpp
        resetting:
            email:
                from_email:
                    address:        resetting@acmedemo.com
                    sender_name:    openpp
    
Enable Bundle
-------------

app/AppKernel.php

            // SONATA CORE & HELPER BUNDLES
            new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
                
            // These are the other bundles the SonataAdminBundle relies on
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
                
            // USER
            new FOS\UserBundle\FOSUserBundle(),
            new Sonata\UserBundle\SonataUserBundle('FOSUserBundle'),
                
            // FOS MESSAGE
            new FOS\MessageBundle\FOSMessageBundle(),
            new Openpp\MessageBundle\OpenppMessageBundle(),

eazy-extends
------------

Command line

    php app/console sonata:easy-extends:generate -d src OpenppMessageBundle
    php app/console sonata:easy-extends:generate -d src SonataUserBundle
    #php app/console sonata:easy-extends:generate -d src FOSUserBundle


Append Bundle
-------------

app/AppKernel.php

            new Application\Openpp\MessageBundle\ApplicationOpenppMessageBundle(),
            new Application\FOS\UserBundle\ApplicationFOSUserBundle(),
            
User class
-----------

    <?php
    
    namespace Application\FOS\UserBundle\Entity;
    
    use FOS\UserBundle\Entity\User as BaseUser;
    use Doctrine\ORM\Mapping as ORM;
    use FOS\MessageBundle\Model\ParticipantInterface;
    
    /**
     * @ORM\Entity
     * @ORM\Table(name="fos_user")
     */
    class User extends BaseUser implements ParticipantInterface
    {
        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;
    
        /**
         * Get id
         *
         * @return integer $id
         */
        public function getId()
        {
            return $this->id;
        }
    }



config
------

app/config/routing.yml
    
    fos_message:
        resource: "@OpenppMessageBundle/Resources/config/routing.xml"
        prefix: /message
        
    fos_user_security:
        resource: "@FOSUserBundle/Resources/config/routing/security.xml"
    
    fos_user_profile:
        resource: "@FOSUserBundle/Resources/config/routing/profile.xml"
        prefix: /profile
    
    fos_user_register:
        resource: "@FOSUserBundle/Resources/config/routing/registration.xml"
        prefix: /register
    
    fos_user_resetting:
        resource: "@FOSUserBundle/Resources/config/routing/resetting.xml"
        prefix: /resetting
    
    fos_user_change_password:
        resource: "@FOSUserBundle/Resources/config/routing/change_password.xml"
        prefix: /profile
        
    _admin:
        resource: routing_admin.yml
        prefix:   /admin
        
app/config/routing_admin.yml

    admin:
        resource: '@SonataAdminBundle/Resources/config/routing/sonata_admin.xml'
    
    _sonata_admin:
        resource: .
        type: sonata_admin

app/config/config.yml

    fos_message:
        db_driver:          orm
        thread_class:       Application\Openpp\MessageBundle\Entity\Thread
        message_class:      Application\Openpp\MessageBundle\Entity\Message
        
    openpp_message:
        monitoring_enable:  true
        monitoring_default: 0
        user_class:         Application\FOS\UserBundle\Entity\User
                
    sonata_block:
        default_contexts: [cms]
        blocks:
            # enable the SonataAdminBundle block
            sonata.admin.block.admin_list:
                contexts: [admin]
             # ...
            
    sonata_admin:
        security:
            # the default value
            handler: sonata.admin.security.handler.role
    
            # use this service if you want ACL
            handler: sonata.admin.security.handler.acl
            
        dashboard:
            groups:
                openpp.message.admin.message:
                    label:            Message
                    icon:            '<i class="fa fa-weixin"></i>'
                    items:
                        - openpp.message.admin.thread


monitoring_enable を true にすることで、監視を有効にします。
監視が有効になると、 message の stats の値が STATUS_VALID(0) の場合のみ表示されます。
STATUSは3つあり、デフォルト値として monitoring_default に指定できます。
また、指定しない場合は null が入ります。

    const STATUS_VALID = 0;
    const STATUS_INVALID = 1;
    const STATUS_MODERATE = 2;

schema
------

Command line

    php app/console cache:clear
    php app/console doctrine:schema:update --dump-sql
    php app/console doctrine:schema:update --force


sonata admin
------------

Read[Admin Bundle Document](https://sonata-project.org/bundles/admin/master/doc/reference/installation.html)

app/config/config.yml

        dashboard:
            groups:
                openpp.message.admin.message:
                    label:            Message
                    icon:            '<i class="fa fa-weixin"></i>'
                    items:
                        - openpp.message.admin.thread
                        - openpp.message.admin.message
                    
app/config/security.yml

        encoders:
            FOS\UserBundle\Model\UserInterface: bcrypt
            
        role_hierarchy:
            ROLE_ADMIN:       ROLE_USER
            ROLE_SUPER_ADMIN: ROLE_ADMIN
            
        providers:
            #in_memory:
            #memory: ~
            fos_userbundle:
                id: fos_user.user_provider.username
        