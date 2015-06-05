<?php
namespace Movidon\FrontendBundle\Twig\Extension;

use Movidon\FrontendBundle\Twig\Extension\CustomTwigExtension;

class IsSuperadminAction extends CustomTwigExtension
{
    public function getFunctions()
    {
        return array('isSuperadminAction' => new \Twig_Function_Method($this, 'isSuperadminAction'));
    }

    public function isSuperadminAction($path)
    {
        if (strpos($path, 'super_admin') !== FALSE) {
            return true;
        }

        return false;
    }


    public function getName()
    {
        return 'isSuperadminAction_extension';
    }
}