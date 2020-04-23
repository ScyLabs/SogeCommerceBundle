<?php

namespace ScyLabs\SogeCommerceBundle;

use ScyLabs\SogeCommerceBundle\DependencyInjection\ScyLabsSogeCommerceExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ScyLabsSogeCommerceBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new ScyLabsSogeCommerceExtension();
    }
}
