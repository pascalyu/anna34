<?php


namespace AppBundle\Listener;

use AppBundle\Entity\User;
use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AfterUserRegistrationSubscribers  implements EventSubscriber
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function getSubscribedEvents()
    {
        return ['prePersist'];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        
        $user = $args->getEntity();
        if (!$user instanceof User) return;
        $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
        $user->setCreatedAt(new DateTime());
        $user->setLastLogin(new DateTime());
    }
}
