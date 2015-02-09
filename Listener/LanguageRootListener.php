<?php

namespace Truelab\KottiMultilanguageBundle\Listener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Session\Session;
use Truelab\KottiFrontendBundle\Exception\CurrentContextNotSetException;
use Truelab\KottiFrontendBundle\Services\CurrentContext;
use Truelab\KottiModelBundle\Model\ContentInterface;

/**
 * Class LanguageRootListener
 * @package Truelab\KottiMultilanguageBundle\Listener
 */
class LanguageRootListener
{

    /** @var Session $session */
    private $session;

    /** @var CurrentContext $currentContext */
    private $currentContext;

    /** @var string $locale */
    private $locale;

    public function __construct($locale)
    {
        $this->locale = $locale;
    }

    public function setSession(Session $session)
    {
        $this->session = $session;
    }

    public function setCurrentContext(CurrentContext $currentContext)
    {
        $this->currentContext = $currentContext;
    }

    public function onRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType())
        {
            return;
        }

        $request  = $event->getRequest();

        /**
         * @var ContentInterface $context
         */
        if(!$context = $this->currentContext->get()) {
            throw new CurrentContextNotSetException();
        }

        if($context instanceof ContentInterface) {
            return;
        }

        // if context language is null, set default
        $locale = $context->getLanguage() ? $context->getLanguage() : $this->locale;

        // set locale in the request and session
        $request->attributes->set('_locale', $locale);
        $this->session->set('_locale', $locale);
    }
}
