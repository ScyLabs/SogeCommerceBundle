<?php

namespace App\SogeCommerceBundle\Controller;

use App\SogeCommerceBundle\Event\ReturnEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class DefaultController extends AbstractController
{
    public function returnAction(Request $request)
    {
        $datas = $request->query->all();
        
        $route = $this->getParameter('scylabs_soge_commerce.return_route');

        if ($datas['vads_trans_status'] == "REFUSED") {
            $route = $this->getParameter('scylabs_soge_commerce.cancel_route');
        }
        
        return $this->redirectToRoute($route);
    }

    public function ipnAction(Request $request,EventDispatcherInterface $eventDispatcher) {
        $datas = $request->request->all();
        
        $event = new ReturnEvent();
        
        $event->setDatas($datas);

        $eventDispatcher->dispatch($event);
        
        return new Response();
    }
}
