<?php

namespace ScyLabs\SogeCommerceBundle\Form;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class SogeType extends AbstractType implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    
    protected $request;
    
    protected $requestStack;
    
    protected $defaultProperties;
    
    protected $router;
    
    public function __construct(RequestStack $requestStack, RouterInterface $router,ContainerInterface $container)
    {
        $this->setContainer($container);
        $this->request = $requestStack->getCurrentRequest();
        $this->requestStack = $requestStack;
        $this->router = $router;
    }
    
    private function setDefaultProperties()
    {
        $now = new \DateTime('now');
        $now = $now->format('YmdHis');
                 
        $this->defaultProperties = [
            'vads_site_id' => $this->container->getParameter('scy_labs_soge_commerce.site_id'),
            'vads_ctx_mode' => $this->container->getParameter('scy_labs_soge_commerce.mode'),
            'vads_trans_id' => null,
            'vads_trans_date' => $now,
            'vads_amount' => null,
            'vads_currency' => $this->container->getParameter('scy_labs_soge_commerce.currency'),
            'vads_action_mode' => 'INTERACTIVE',
            'vads_page_action' => 'PAYMENT',
            'vads_version' => 'V2',
            'vads_payment_config' => 'SINGLE',
            'vads_url_return' => $this->router->generate('scy_labs_soge_commerce_return', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'vads_url_cancel' => $this->requestStack->getMasterRequest()->getUri(),
            'vads_return_mode' => 'GET',
            'vads_url_check' => $this->router->generate('scy_labs_soge_commerce_ipn', [], UrlGeneratorInterface::ABSOLUTE_URL),
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
