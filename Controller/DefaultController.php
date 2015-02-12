<?php

namespace Truelab\KottiMultilanguageBundle\Controller;
use Truelab\KottiFrontendBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class DefaultController
 * @package Truelab\KottiMultilanguageBundle\Controller
 */
class DefaultController extends BaseController
{
    /**
     * @return RedirectResponse
     */
    public function redirectAction()
    {
        return new RedirectResponse(
            $this->path($this->getLanguage()->getDefaultLanguageRoot())
        );
    }

    /**
     * @return \Truelab\KottiMultilanguageBundle\Util\Language
     */
    protected function getLanguage()
    {
        return $this->container->get('truelab_kotti_multilanguage.util.language');
    }
}
