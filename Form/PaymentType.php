<?php

namespace App\SogeCommerceBundle\Form;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentType extends AbstractType implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    protected $defaultProperties;

    protected $container;

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
  
    private function setDefaultProperties()
    {
        $now = new \DateTime('now');
        $now = $now->format('YmdHis');
                 
        $this->defaultProperties = [
            'vads_site_id' => $this->container->getParameter('scy_labs_soge_commerce.site_id'),
            'vads_ctx_mode' => $this->container->getParameter('scylabs_soge_commerce.mode'),
            'vads_trans_id' => null,
            'vads_trans_date' => $now,
            'vads_amount' => null,
            'vads_currency' => $this->container->getParameter('scylabs_soge_commerce.currency'),
            'vads_action_mode' => 'INTERACTIVE',
            'vads_page_action' => 'PAYMENT',
            'vads_version' => 'V2',
            'vads_payment_config' => 'SINGLE',
            'vads_url_return' => $this->router->generate('scylabs_soge_commerce_return', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'vads_url_cancel' => $this->requestStack->getMasterRequest()->getUri(),
            'vads_return_mode' => 'GET',
            'vads_url_check' => $this->router->generate('scylabs_soge_commerce_ipn', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ];
    }

    public function getBlockPrefix()
    {
        return null;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'action' => 'https://sogecommerce.societegenerale.eu/vads-payment/',
            'data_class' => null,
        ]);
    }
}
