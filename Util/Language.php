<?php

namespace Truelab\KottiMultilanguageBundle\Util;
use Symfony\Component\HttpFoundation\Session\Session;
use Truelab\KottiModelBundle\Model\ContentInterface;
use Truelab\KottiModelBundle\Model\NodeInterface;
use Truelab\KottiMultilanguageBundle\Repository\TranslationRepositoryInterface;

/**
 * Class Language
 * @package Truelab\KottiMultilanguageBundle\Util
 */
class Language
{
    protected $locale;
    protected $languageRoot;
    protected $defaultLocale;
    protected $repository;
    protected $translationsMap = [];
    protected $availableLocales;

    public function __construct($defaultLocale, $availableLocales, TranslationRepositoryInterface $repository, Session $session)
    {
        $this->defaultLocale = $defaultLocale;
        $this->repository = $repository;
        $this->availableLocales = $availableLocales;
        $this->session = $session;
    }

    public function setLocale(ContentInterface $context)
    {
        $this->locale = $context->getLanguage() ? $context->getLanguage() : $this->getDefaultLocale();
        $this->languageRoot = $this->repository->findByPath($this->getCurrentLanguageRootPath());
        $this->translationsMap = $this->repository->getTranslationsMap($context);

        // set locale for this session
        $this->session->set('_locale', $this->getLocale());
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function getAvailableLocales()
    {
        return $this->availableLocales;
    }

    public function getDefaultLocale()
    {
        return $this->session->get('_locale') ? $this->session->get('_locale') : $this->defaultLocale;
    }

    public function getTranslationsMap()
    {
        return $this->translationsMap;
    }

    public function getCurrentLanguageRoot()
    {
        return $this->languageRoot;
    }

    public function getCurrentLanguageRootPath()
    {
        return '/' . $this->getLocale() . '/';
    }

    public function getDefaultLanguageRootPath()
    {
        return '/' . $this->getDefaultLocale() . '/';
    }

}
