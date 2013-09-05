<?php

namespace C2is\Bundle\OtaBundle\OTA;

use C2is\OTA\Request;
use Symfony\Component\DependencyInjection\Container;

class Factory
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function createRequest($name, array $params = array())
    {
        $request = new Request($name, $this->container->getParameter('ota'));
        $request->addParams($params);

        return $request;
    }
}
