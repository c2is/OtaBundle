<?php

namespace C2is\Bundle\OtaBundle\OTA;

use Symfony\Component\DependencyInjection\Container;

class MessageFactory
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getMessage($messageName, $params)
    {
        $otaMessage = $this->container->get(sprintf('ota.%s', preg_replace('/([a-z])([A-Z])/', '$1_$2', $messageName)));
        $otaMessage->addOptions($params);

        return $otaMessage;
    }
}