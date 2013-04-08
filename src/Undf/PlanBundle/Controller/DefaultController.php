<?php

namespace Undf\PlanBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Undf\PlanBundle\Entity\Stage;

class DefaultController extends Controller
{

    public function indexAction()
    {
        $plan = $this->getMainPlan();
        return $this->render('UndfPlanBundle:Default:index.html.twig', array(
                    'plan' => $plan
        ));
    }

    public function infoWindowAction($stageName)
    {
        $stage = $this->getStage($stageName);
        return $this->render('UndfPlanBundle:Partial:map_window.html.twig', array(
                    'stage' => $stage,
                    'jsonEncodedLinks' => $this->getJsonEncodedLinks($stage)
        ));
    }

    private function getStage($stageName)
    {
        return $this
                        ->getDoctrine()
                        ->getRepository('UndfPlanBundle:Stage')
                        ->findOneByCityName($stageName)
        ;
    }

    private function getJsonEncodedLinks(Stage $stage)
    {
        $encodedLinks = array();
        foreach($stage->getLinks() as $link) {
            $encodedLink = array();
            $encodedLink['type'] = 'iframe';
            $encodedLink['href'] = $link->getUrl();
            $encodedLinks[] = $encodedLink;
        }
        return json_encode($encodedLinks);
    }

    private function getMainPlan()
    {
        $planId = 1;
        return $this
                        ->getDoctrine()
                        ->getRepository('UndfPlanBundle:Plan')
                        ->find($planId)
        ;
    }

}
