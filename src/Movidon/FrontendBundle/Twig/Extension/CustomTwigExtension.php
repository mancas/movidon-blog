<?php

namespace Movidon\FrontendBundle\Twig\Extension;

class CustomTwigExtension extends \Twig_Extension
{
    protected $environment;

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
    }

    public function getName()
    {
    }
}