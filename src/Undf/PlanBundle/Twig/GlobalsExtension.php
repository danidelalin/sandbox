<?php

namespace Undf\PlanBundle\Twig;

use Sonata\MediaBundle\Provider\ImageProvider;
use Sonata\MediaBundle\Model\MediaInterface;

class GlobalsExtension extends \Twig_Extension
{

    private $imageProvider;

    public function __construct(ImageProvider $imageProvider)
    {
        $this->imageProvider = $imageProvider;
    }

    public function getFilters()
    {
        return array(
            'getImageUrl' => new \Twig_Filter_Method($this, 'getImageUrl'),
        );
    }

    public function getImageUrl(MediaInterface $media = null, $format = 'reference')
    {
        if ($media) {
            $format = $media->getContext().'_'.$format;
            return $this->imageProvider->generatePublicUrl($media, $format);
        }
        return false;
    }

    public function getGlobals()
    {
        return array(
            'globals_imageProvider' => $this->imageProvider,
        );
    }

    public function getName()
    {
        return 'globals_extension';
    }

}