<?php

namespace Truelab\KottiMultilanguageBundle\Util;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Truelab\KottiModelBundle\Util\PostLoaderInterface;

/**
 * Class LanguageIndependentFieldsPostLoader
 * @package Truelab\KottiMultilanguageBundle\Util
 */
class LanguageIndependentFieldsPostLoader implements PostLoaderInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * FIXME - avoid circular dependencies injecting the container
     * the problem is the model factory is used by the repository
     * and here i tried to inject @see Language that dependes on repository too.
     *
     * Model design is bad!
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function support($content)
    {
        return $this->getLanguage()->hasIndependentFields($content);
    }

    public function onPostLoad($content)
    {
        if($this->getLanguage()->hasIndependentFields($content)) {
            $this->getLanguage()->setIndependentFields($content);
        }
    }

    /**
     * @return Language
     */
    protected function getLanguage()
    {
        return $this->container->get('truelab_kotti_multilanguage.util.language');
    }
}
