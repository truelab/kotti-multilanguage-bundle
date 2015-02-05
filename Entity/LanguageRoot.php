<?php

namespace Truelab\KottiMultilanguageBundle\Entity;

use Truelab\DoctrineBundle\ORM\DiscriminatedClassInterface;
use Truelab\KottiORMBundle\Entity\Document;
use Truelab\KottiORMBundle\Entity\Node;
use Truelab\KottiORMBundle\Model\ContentProxyInterface;
use Truelab\KottiORMBundle\Model\ContentProxyTrait;

/**
 * Class LanguageRoot
 * @package Truelab\KottiMultilanguageBundle\Entity
 *
 * TODO
 * @discriminatorValue("language_root")
 */
class LanguageRoot extends Node implements DiscriminatedClassInterface
{
    /**
     * @var Document $document
     */
    protected $document;

    public function getDiscriminatorValue()
    {
        return 'language_root';
    }

    public function getDocumentBody()
    {
        return get_class($this->getDocument());
    }

    public function getDocumentMimeType()
    {
        return 'text/html';
    }

    public function setDocument(Document $document)
    {
        $this->document = $document;
    }

    public function getDocument()
    {
        return $this->document;
    }
}
