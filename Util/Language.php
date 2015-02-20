<?php

namespace Truelab\KottiMultilanguageBundle\Util;
use Symfony\Component\HttpFoundation\Session\Session;
use Truelab\KottiModelBundle\Model\ContentInterface;
use Truelab\KottiModelBundle\Model\NodeInterface;
use Truelab\KottiMultilanguageBundle\Model\LanguageRoot;
use Truelab\KottiMultilanguageBundle\Repository\TranslationRepositoryInterface;

/**
 * Class Language
 * @package Truelab\KottiMultilanguageBundle\Util
 */
class Language
{
    protected $locale;
    protected $languageRoot;
    protected $defaultLanguageRoot;
    protected $availableLanguageRoots;
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
        $this->defaultLanguageRoot = $this->getDefaultLanguageRoot() ? $this->getDefaultLanguageRoot() : $this->repository->findByPath($this->getDefaultLanguageRootPath());
        $this->languageRoot    = $this->repository->findByPath($this->getCurrentLanguageRootPath());
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

    public function getAvailableLanguageRoots()
    {
        if($this->availableLanguageRoots) {
            return $this->availableLanguageRoots;
        }

        $language_roots = [];

        foreach($this->getAvailableLocales() as $locale)
        {
            $language_root = $this->repository->findByPath(self::buildLocalePath($locale));

            if($language_root instanceof LanguageRoot) {
                $language_roots[] = $language_root;
            }
        }

        $this->availableLanguageRoots = $language_roots;

        return $this->availableLanguageRoots;
    }

    public function getDefaultLocale()
    {
        return $this->session->get('_locale') ? $this->session->get('_locale') : $this->defaultLocale;
    }

    public function getTranslationsMap()
    {
        return $this->translationsMap;
    }

    public function getTranslatedContext(LanguageRoot $languageRoot)
    {
        $translationsMap = $this->getTranslationsMap();
        $language = $languageRoot->getLanguage();

        if(isset($translationsMap[$language])) {
            return $translationsMap[$language];
        }else{
            return $languageRoot;
        }
    }

    /**
     * @return LanguageRoot|null
     */
    public function getCurrentLanguageRoot()
    {
        return $this->languageRoot;
    }

    public function getDefaultLanguageRoot()
    {
        return $this->defaultLanguageRoot;
    }

    public function getCurrentLanguageRootPath()
    {
        return self::buildLocalePath($this->getLocale());
    }

    public function getDefaultLanguageRootPath()
    {
        return self::buildLocalePath($this->getDefaultLocale());
    }

    protected static function buildLocalePath($locale)
    {
        return '/' . $locale . '/';
    }

}
