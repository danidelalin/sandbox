<?php

namespace Undf\MediaBundle\Manager;

use Vich\UploaderBundle\Storage\StorageInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\File\File;
use Application\Sonata\UserBundle\Entity\User;

class ProfileImageManager extends ImageManager
{

    /**
     * @var Vich\UploaderBundle\Storage\StorageInterface
     */
    private $storage;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var string
     */
    private $defaultImagePath;

    /**
     * @var Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    private $router;

    public function __construct(EntityManager $em, StorageInterface $storage, $defaultImagePath, Router $router)
    {
        $this->em = $em;
        $this->storage = $storage;
        $this->defaultImagePath = $defaultImagePath;
        $this->router = $router;
    }

    public function getDefaultImagePath()
    {
        return $this->defaultImagePath;
    }

    public function getDefaultImageContent()
    {
        return $this->_getFileContent(new File($this->defaultImagePath, true));
    }

    public function generateProfileImageUrl(User $user)
    {
        if($imageName = $user->getImageName()) {
            return $this->router->generate('undf_media_profile_image', array('name' => $imageName));
        }
        return $this->generateDefaultProfileImageUrl($user);
    }

    public function generateDefaultProfileImageUrl(User $user)
    {
        if($facebookId = $user->getFacebookId()) {
            return sprintf('https://graph.facebook.com/%s/picture?type=normal', $facebookId);
        } else {
            return $this->router->generate('undf_media_profile_default_image');
        }
    }

}
