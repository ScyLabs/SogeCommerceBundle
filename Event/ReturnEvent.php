<?php
/**
 * Created by PhpStorm.
 * User: maxence
 * Date: 19/04/2017
 * Time: 11:25
 */

namespace App\SogeCommerceBundle\Event;
use Symfony\Contracts\EventDispatcher\Event;

class ReturnEvent extends Event
{
    const NAME = 'sogecommerce.return';
    
    /**
     * @var
     */
    private $datas;
    

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