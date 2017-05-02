<?php

namespace Mdespeuilles\SogeCommerceBundle\Form;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class SogeType extends AbstractType implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    
    protected $request;
    
    protected $requestStack;
    
    protected $defaultProperties;
    
    protected $router;
    
    public function __construct(RequestStack $requestStack, Router $router)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->requestStack = $requestStack;
        $this->router = $router;
    }
    
    private function setDefaultProperties()
    {
        $now = new \DateTime('now');
        $now = $now->format('YmdHis');
                 
        $this->defaultProperties = [
            'vads_site_id' => $this->container->getParameter('mdespeuilles_soge_commerce.site_id'),
            'vads_ctx_mode' => $this->container->getParameter('mdespeuilles_soge_commerce.mode'),
            'vads_trans_id' => null,
            'vads_trans_date' => $now,
            'vads_amount' => null,
            'vads_currency' => $this->container->getParameter('mdespeuilles_soge_commerce.currency'),
            'vads_action_mode' => 'INTERACTIVE',
            'vads_page_action' => 'PAYMENT',
            'vads_version' => 'V2',
            'vads_payment_config' => 'SINGLE',
            'vads_url_return' => $this->router->generate('mdespeuilles_soge_commerce_return', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'vads_url_cancel' => $this->requestStack->getMasterRequest()->getUri(),
            'vads_return_mode' => 'GET'
        ];
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->setDefaultProperties();
        $properties = $this->filterProperties($options['properties']);
        $properties = array_merge($this->defaultProperties, $properties);
        $buttonLabel = $options['buttonLabel'];
        
        foreach ($properties as $name => $value) {
            $builder->add($name, HiddenType::class, [
                'data' => $value
            ]);
        }
        
        $builder->add('signature', HiddenType::class);
        $builder->add('submit', SubmitType::class, [
            'label' => $buttonLabel,
            'attr' => [
                'class' => 'btn-blue'
            ]
        ]);
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'action' => 'https://sogecommerce.societegenerale.eu/vads-payment/',
            'amount' => null,
            'trans_id' => null,
            'customer_email' => null,
            'properties' => [],
            'buttonLabel' => null
        ));
    }
    
    private function filterProperties($properties)
    {
        foreach ($properties as $name => $value) {
            if ('vads_trans_id' === $name) {
                if (empty($value)) {
                    throw new \Exception('vads_trans_id can not be null');
                }
                
                if (!is_integer($value)) {
                    throw new \Exception('vads_trans_id must be integer');
                }
    
                if (strlen($value) > 6) {
                    throw new \Exception('vads_trans_id must be <= 899999');
                }
    
                if ($value < 0) {
                    throw new \Exception('vads_trans_id must be >= 0');
                }
    
                if (strlen($value) < 6) {
                    $properties[$name] = str_pad($value, 6, "0", STR_PAD_LEFT);
                }
            }
    
            if ('vads_amount' === $name) {
                if (!is_numeric($value)) {
                    throw new \Exception("vads_trans_id must be integer. The amount must be in centimes. (Ex: 10.50 euros will be 1050)");
                }
            }
        }
        
        return $properties;
    }
    
    public function getBlockPrefix()
    {
        return null;
    }
}
