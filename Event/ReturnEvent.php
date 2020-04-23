<?php
/**
 * Created by PhpStorm.
 * User: maxence
 * Date: 19/04/2017
 * Time: 11:25
 */

namespace ScyLabs\SogeCommerceBundle\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class ReturnEvent extends Event
{
    const NAME = 'sogecommerce.return';
    
    /**
     * @var
     */
    private $datas;
    
    public function __construct(Request $request){
        $this->request  = $request;
    }
    /**
     * @return mixed
     */
    public function getDatas() {
        return $this->datas;
    }
    
    /**
     * @param mixed $datas
     */
    public function setDatas($datas) {
        $this->datas = $datas;
    }
    public function setRequest(Request $request) : self {
        $this->request = $request;
        return $this;
    }
    public function getRequest() : Request{
        return $this->request;
    }
}