<?php

namespace Truelab\KottiMultilanguageBundle\Util;
use Truelab\KottiModelBundle\Util\PostLoaderInterface;

/**
 * Class LanguageIndependentFieldsPostLoader
 * @package Truelab\KottiMultilanguageBundle\Util
 */
class LanguageIndependentFieldsPostLoader implements PostLoaderInterface
{
    /**
     * @var Language
     */
    private $language;

    public function __construct(Language $language)
    {
        $this->language = $language;
    }

    public function support($content)
    {
        return $this->language->hasIndependentFields($content);
    }

    public function onPostLoad($content)
    {
        if($this->language->hasIndependentFields($content)) {
            $this->language->setIndependentFields($content);
        }
    }
}
