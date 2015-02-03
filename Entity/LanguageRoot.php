<?php

namespace Truelab\KottiMultilanguageBundle\Entity;

use Truelab\DoctrineBundle\ORM\DiscriminatedClassInterface;
use Truelab\KottiORMBundle\Entity\Node;

/**
 * Class LanguageRoot
 * @package Truelab\KottiMultilanguageBundle\Entity
 *
 * TODO
 * @discriminatorValue("language_root")
 */
class LanguageRoot extends Node implements DiscriminatedClassInterface
{
    protected $document;

    public function getDiscriminatorValue()
    {
        return 'language_root';
    }
}
