<?php
/**
 * Created by PhpStorm.
 * User: maxence
 * Date: 18/04/2017
 * Time: 16:46
 */

namespace App\SogeCommerceBundle\Services;
use App\SogeCommerceBundle\Form\SogeType;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Form;

class SogeCommerce implements ContainerAwareInterface {
        
    use ContainerAwareTrait;
    
    protected $properties;
    
    protected $buttonLabel;
    
    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
        $this->properties = [];
        $this->buttonLabel = "Payment";
    }
    
    public function set(string $property, $value) : self
    {
        $this->properties[$property] = $value;
        return $this;
    }
    
    public function getForm()
    {
        $form = $this->createForm(SogeType::class, null, [
            'properties' => $this->properties,
            'buttonLabel' => $this->buttonLabel
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
        
        $signature = base64_encode(hash_hmac('sha256',$string, $this->getCertificate(), true));
        return $signature;
    }
    
    private function getCertificate() {
        $test_certificate = $this->container->getParameter('scylabs_soge_commerce.test_certificate');
        $prod_certificate = $this->container->getParameter('scylabs_soge_commerce.prod_certificate');
        $mode = $this->container->getParameter('scylabs_soge_commerce.mode');
    
        if ($mode === 'PRODUCTION') {
            return $prod_certificate;
        }
        
        return $test_certificate;
    }
    
    /**
     * @return string
     */
    public function getButtonLabel() {
        return $this->buttonLabel;
    }
    
    /**
     * @param string $buttonLabel
     */
    public function setButtonLabel($buttonLabel) {
        $this->buttonLabel = $buttonLabel;
    }
}
