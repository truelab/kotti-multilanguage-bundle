<?php

namespace Truelab\KottiMultilanguageBundle\Repository;
use Truelab\KottiModelBundle\Model\ContentInterface;
use Truelab\KottiModelBundle\Repository\RepositoryInterface;
use Truelab\KottiModelBundle\Model\NodeInterface;

/**
 * Interface TranslationRepositoryInterface
 * @package Truelab\KottiMultilanguageBundle\Repository
 */
interface TranslationRepositoryInterface extends RepositoryInterface
{
    /**
     * @param ContentInterface $source
     *
     * @return array
     */
    public function getTranslationsMap(ContentInterface $source);

    /**
     * @param $content
     *
     * @return mixed
     */
    public function getSource(ContentInterface $content);
}
