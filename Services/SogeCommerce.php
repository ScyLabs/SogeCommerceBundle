<?php
/**
 * Created by PhpStorm.
 * User: maxence
 * Date: 18/04/2017
 * Time: 16:46
 */

namespace Mdespeuilles\SogeCommerceBundle\Services;
use Mdespeuilles\SogeCommerceBundle\Form\SogeType;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\Form;

class SogeCommerce implements ContainerAwareInterface {
        
    use ContainerAwareTrait;
    
    protected $properties;
    
    public function __construct()
    {
        $this->properties = [];
    }
    
    public function set($property, $value)
    {
        $this->properties[$property] = $value;
    }
    
    public function getForm()
    {
        $form = $this->createForm(SogeType::class, null, [
            'properties' => $this->properties
        ]);
        
        $sign = $this->calculateSign($form);
    
        $form->get('signature')->setData($sign);
        
        return $form->createView();
    }
    
    public function createForm($type, $data = null, array $options = array())
    {
        return $this->container->get('form.factory')->create($type, $data, $options);
    }
    
    public function calculateSign(Form $form) {
        $fields = [];
        foreach ($form->all() as $name => $child) {
            if (substr($name, 0, 5) === "vads_") {
                $fields[$name] = $child;
            }
        }
        
        ksort($fields);
    
        $values = [];
        foreach ($fields as $name => $child) {
            array_push($values, $child->getData());
        }
        
        array_push($values, $this->getCertificate());
        
        $string = implode("+", $values);
        
        return sha1($string);
    }
    
    private function getCertificate() {
        $test_certificate = $this->container->getParameter('mdespeuilles_soge_commerce.test_certificate');
        $prod_certificate = $this->container->getParameter('mdespeuilles_soge_commerce.prod_certificate');
        $mode = $this->container->getParameter('mdespeuilles_soge_commerce.mode');
    
        if ($mode === 'PRODUCTION') {
            return $prod_certificate;
        }
        
        return $test_certificate;
    }
}
