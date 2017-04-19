<?php
/**
 * Created by PhpStorm.
 * User: maxence
 * Date: 19/04/2017
 * Time: 11:25
 */

namespace Mdespeuilles\SogeCommerceBundle\Event;
use Symfony\Component\EventDispatcher\Event;

class ReturnEvent extends Event
{
    const NAME = 'sogecommerce.return';
    
    /**
     * @var
     */
    private $datas;
    
    /**
     * ReturnEvent constructor.
     * @param $datas
     */
    public function __construct($datas)
    {
        $this->datas = (object)$datas;
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
}