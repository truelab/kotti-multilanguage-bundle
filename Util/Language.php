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

    /**
     * @var LanguageIndependentFields
     */
    protected $languageIndependentFields;

    public function __construct($defaultLocale, $availableLocales,
                                TranslationRepositoryInterface $repository,
                                Session $session, LanguageIndependentFields $languageIndependentFields = null)
    {
        $this->defaultLocale = $defaultLocale;
        $this->repository = $repository;
        $this->availableLocales = $availableLocales;
        $this->session = $session;
        $this->languageIndependentFields = $languageIndependentFields;
    }


    public function setLocale(ContentInterface $context = null)
    {
        $this->translationsMap     = $context ? $this->repository->getTranslationsMap($context) : [];
        $this->locale              = $context && $context->getLanguage() ? $context->getLanguage() : $this->getDefaultLocale();

        $this->defaultLanguageRoot = $this->getDefaultLanguageRoot() ? $this->getDefaultLanguageRoot() : $this->repository->findByPath($this->getDefaultLanguageRootPath()); // HARD TO TEST WRONG!
        $this->languageRoot = $this->repository->findByPath($this->getCurrentLanguageRootPath());

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

    public function getSource($content)
    {
        return $this->repository->getSource($content);
    }

    public function setIndependentFields($content)
    {
        $source = $this->getSource($content);

        if($source && $this->hasIndependentFields($content)) {
            foreach($this->getIndependentFields($content) as $indieField) {
                $content[$indieField] = $source[$indieField];
            }
        }
    }

    public function hasIndependentFields($content)
    {
        return $this->languageIndependentFields->hasFields($content);
    }

    public function getIndependentFields($content)
    {
        return $this->languageIndependentFields->getFields($content);
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
