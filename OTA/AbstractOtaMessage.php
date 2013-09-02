<?php

namespace C2is\Bundle\OtaBundle\OTA;

use C2is\Bundle\OtaBundle\Error\OtaError;
use Symfony\Component\DependencyInjection\Container;

/**
 * Implements basic methods allowing the generation and reception of OTA messages
 *
 * Class AbstractOtaMessage
 * @package C2is\Bundle\OtaBundle\OTA
 */
abstract class AbstractOtaMessage
{
    /**
     * @var array Associative array of options to be passed to the twig template.
     */
    protected $options = array();

    /**
     * @var \Twig_Environment The twig environment used to render the message.
     */
    protected $twig;

    /**
     * @var string The request XML content.
     */
    protected $request;

    /**
     * @var string The response XML content.
     */
    protected $response;

    /**
     * @var array Errors returned in the OTA response.
     */
    protected $errors;

    /**
     * @param \Twig_Environment $twig The twig environment used to render the message.
     */
    public function setTwigEnvironment(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @return mixed An array containing the name of required options in the OTA message.
     */
    abstract protected function getRequiredOptions();

    /**
     * @return mixed This message's name.
     */
    abstract protected function getName();

    /**
     * The request XML message.
     *
     * @return string The request XML message.
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Sets the request XML message.
     *
     * @param $xml The XML content.
     * @return $this We're fluid.
     */
    public function setRequest($xml)
    {
        $this->request = $xml;

        return $this;
    }

    /**
     * The response XML message.
     *
     * @return string The response XML message.
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($xml)
    {
        $this->response = $xml;

        $this->errors = $this->getErrorsFromXml($xml);

        return $this;
    }

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
     * @return string A unique identifier.
     */
    protected function generateEcho()
    {
        return uniqid();
    }

    /**
     * @return string The current UTC timestamp formatted in accordance to OTA standards.
     */
    protected function getTimestamp()
    {
        return gmdate('Y-m-d\TH:i:s\Z');
    }

    /**
     * Adds every parameters in the ota namespace as options for the message.
     *
     * @param Container $container The service container.
     */
    public function setDefaultOptions(Container $container)
    {
        $this->options = $container->getParameter('ota');
    }

    /**
     * @return string The generated message XML.
     * @throws MissingParameterException If any of the options returned by getRequiredOptions() is not set.
     */
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

        $this->request = $this->twig->render(sprintf('OtaBundle:ota:%s.xml.twig', $this->getName()), $this->options);

        return $this->request;
    }

    /**
     * @return bool Whether the response returns a successful message or not.
     */
    public function isSuccessful()
    {
        if ($this->response) {
            $dom = new \DOMDocument();
            $dom->loadXml($this->response);

            return (boolean) $dom->getElementsByTagName('Success')->length;
        }

        return false;
    }

    /**
     * @return array The errors returned by the response.
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param OtaError $error Adds an error to the response errors array.
     */
    public function addError(OtaError $error)
    {
        $this->errors[] = $error;
    }

    /**
     * @param array $errors Adds errors to the response errors array.
     */
    public function addErrors(array $errors)
    {
        $this->errors = array_merge($this->errors, $errors);
    }

    /**
     * @param $xml The response XML.
     * @return array An array of OtaErrors
     */
    public function getErrorsFromXml($xml)
    {
        $errors = array();

        $xml = simplexml_load_string($xml);

        if ($xml->Errors->Error) {
            foreach ($xml->Errors->Error as $xmlError) {
                $objError = new OtaError();
                $errorCode = $xmlError->xpath('@Code');
                $objError->setCode((string) $errorCode[0]);
                $errorType = $xmlError->xpath('@Type');
                $objError->setCode((string) $errorType[0]);
                $objError->setMessage((string) $xmlError);

                $errors[] = $objError;
            }
        }

        return $errors;
    }
}
