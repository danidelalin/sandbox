<?php
namespace Undf\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfileImageController extends Controller
{
    /**
     * @param type $hash
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function getProfilePictureAction($name)
    {
        $imagine = $this->get('liip_imagine.controller');
        return $imagine->filterAction($this->getRequest(),$name,'profile_photos');
    }

    /**
     * @param type $hash
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function getDefaultProfilePictureAction()
    {
        $filter = 'profile_photos';
        $defaultImage = $this->get('undf_media.image_manager.profile')->getDefaultImageContent();

        $imagine = $this->get('liip_imagine.controller');
        return $imagine->cacheResponse($imagine->createResponse($defaultImage, $filter), $filter);
    }



}
