<?php

namespace Undf\LocationDataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    public function getProvincesAndCitiesAction($country = null)
    {
        if ($country) {
            $result = $this
                    ->getDoctrine()
                    ->getRepository('UndfLocationDataBundle:Country')
                    ->find($country);
            if ($result) {
                $serializer = $this->container->get('serializer');
                $result = $serializer->serialize($result, 'json');
                return new Response($result);
            }
        }
        return new Response('false');
    }

    public function getCitiesAction($province = null)
    {
        if ($province) {
            $cities = $this
                    ->getDoctrine()
                    ->getRepository('UndfLocationDataBundle:Province')
                    ->find($province);
            if ($cities) {
                $serializer = $this->container->get('serializer');
                $cities = $serializer->serialize($cities, 'json');
                return new Response($cities);
            }
        }
        return new Response('false');
    }

}
