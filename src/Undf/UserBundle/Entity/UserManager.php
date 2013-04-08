<?php
namespace Undf\UserBundle\Entity;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Doctrine\UserManager as BaseUserManager;
use FOS\UserBundle\Util\CanonicalizerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Application\Sonata\UserBundle\Entity\User;

class UserManager extends BaseUserManager
{
    protected $em;

    public function __construct(EncoderFactoryInterface $encoderFactory, CanonicalizerInterface $usernameCanonicalizer, CanonicalizerInterface $emailCanonicalizer, EntityManager $em, $class)
    {
        parent::__construct($encoderFactory, $usernameCanonicalizer, $emailCanonicalizer, $em, $class);

        $this->em = $em;
    }

     public function updateUserLocale(User $user, $newLocale)
    {
        if($newLocale !== $user->getLocale()) {
            $user->setLocale($newLocale);
            $andFlush = true;
            $this->updateUser($user, $andFlush);
        }
    }


}
