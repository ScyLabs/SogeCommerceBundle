<?php

namespace ScyLabs\SogeCommerceBundle\Controller;

use ScyLabs\SogeCommerceBundle\Event\ReturnEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/soge-commerce/return",name="scy_labs_soge_commerce_return")
     */
    public function returnAction(Request $request)
    {
        $datas = $request->query->all();
        
        $route = $this->getParameter('scy_labs_soge_commerce.return_route');

        if ($datas['vads_trans_status'] == "REFUSED") {
            $route = $this->getParameter('scy_labs_soge_commerce.cancel_route');
        }
        
        return $this->redirectToRoute($route);
    }

    /**
     * @Route("/soge-commerce/ipn",name="scy_labs_soge_commerce_ipn")
     */
    public function ipnAction(Request $request,EventDispatcherInterface $eventDispatcher) {
        $datas = $request->request->all();
        
        $event = new ReturnEvent($request);
        
        $event->setDatas($datas);

        $eventDispatcher->dispatch($event,ReturnEvent::NAME);
        
        return new Response();
    }
}
