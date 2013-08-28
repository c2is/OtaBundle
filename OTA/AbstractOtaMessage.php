<?php

namespace C2is\Bundle\OtaBundle\OTA;

use Symfony\Component\DependencyInjection\Container;

abstract class AbstractOtaMessage
{
    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @param \Twig_Environment $twig
     */
    public function setTwigEnvironment(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    abstract protected function getRequiredOptions();

    abstract protected function getName();

    /**
     * @param array $options An array of options. Existing options will be overridden.
     */
    public function addOptions($options = array())
    {
        if (is_array($options)) {
            $options = array_merge($this->options, $options);

            $this->options = $options;
        }
    }

    /**
     * @param string $name Option name. If already exists, will be overridden.
     * @param string $value Option value.
     */
    public function addOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * @return string
     */
    protected function generateEcho()
    {
        return uniqid();
    }

    /**
     * @return string
     */
    protected function getTimestamp()
    {
        return date('Y-m-d\TH:i:s\Z');
    }

    public function setDefaultOptions(Container $container)
    {
        $this->options = $container->getParameter('ota');
    }

    public function getXml()
    {
        foreach ($this->getRequiredOptions() as $key => $value) {
            $optionName = is_int($key) ? $value : $key;
            if (!is_int($key) and !array_key_exists($key, $this->options) and $value) {
                $this->options[$key] = $value;
            }

            if (!array_key_exists($optionName, $this->options)) {
                throw new MissingParameterException(sprintf('Parameter "%s" is required and was not found.', $optionName));
            }
        }

        return $this->twig->render(sprintf('OtaBundle:ota:%s.xml.twig', $this->getName()), $this->options);
    }
}