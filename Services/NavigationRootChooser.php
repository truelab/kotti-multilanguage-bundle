<?php

namespace Truelab\KottiMultilanguageBundle\Services;
use Truelab\KottiFrontendBundle\Services\NavigationRootChooserInterface;
use Truelab\KottiMultilanguageBundle\Model\LanguageRoot;

/**
 * Class NavigationRootChooser
 * @package Truelab\KottiMultilanguageBundle\Services
 */
class NavigationRootChooser implements NavigationRootChooserInterface
{

    public function choose($node)
    {

        if($node && get_class($node) === LanguageRoot::getClass()) {
            return true;
        }

        return false;
    }
}
