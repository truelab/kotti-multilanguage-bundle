<?php

namespace Truelab\KottiMultilanguageBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Session\Session;
use Truelab\KottiFrontendBundle\Services\CurrentContext;
use Truelab\KottiModelBundle\Model\ContentInterface;
use Truelab\KottiMultilanguageBundle\Util\Language;

/**
 * Class LanguageListener
 * @package Truelab\KottiMultilanguageBundle\Listener
 */
class LanguageListener
{

    /** @var CurrentContext */
    private $currentContext;


    /** @var Language **/
    private $language;

    /** @var \Twig_Environment */
    private $twig;


    public function setCurrentContext(CurrentContext $currentContext)
    {
        $this->currentContext = $currentContext;
    }

    public function setLanguage(Language $language)
    {
        $this->language = $language;
    }

    public function setTwig(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $this->setLocale($event);
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $this->setLocale($event);
    }

    protected function setLocale(KernelEvent $event)
    {
        $context = $this->currentContext->get();

        $this->language->setLocale($context);

        $event->getRequest()->setLocale($this->language->getLocale());

        // add language object to global twig
        $this->twig->addGlobal('language', $this->language);
    }
}
