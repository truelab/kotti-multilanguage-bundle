<?php

namespace Truelab\KottiMultilanguageBundle\Tests\Repository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Truelab\KottiModelBundle\Model\Document;
use Truelab\KottiMultilanguageBundle\Repository\TranslationRepositoryInterface;

/**
 * Class TranslationRepositoryFunctionalTest
 * @package Truelab\KottiMultilanguageBundle\Tests\Repository
 */
class TranslationRepositoryFunctionalTest extends WebTestCase
{
    private $client;

    /**
     * @var TranslationRepositoryInterface $repository
     */
    private $repository;

    public function setUp()
    {
        $this->client = self::createClient();
        $this->repository = $this->client->getContainer()->get('truelab_kotti_multilanguage.translation_repository');
    }

    public function testGetTranslationsMap()
    {
        $sourceNode = $this->repository->findOne(Document::getClass(), array(
            'nodes.path = ?' => '/en/mip/'
        ));

        $map = $this->repository->getTranslationsMap($sourceNode);

        $this->assertArrayHasKey('en', $map);
        $this->assertArrayHasKey('it', $map);

        $this->assertInstanceOf(Document::getClass(), $map['en']);
        $this->assertInstanceOf(Document::getClass(), $map['it']);

        $targetNode = $this->repository->findOne(Document::getClass(), array(
            'nodes.path = ?' => '/it/mip/'
        ));

        $map = $this->repository->getTranslationsMap($targetNode);

        $this->assertArrayHasKey('en', $map);
        $this->assertArrayHasKey('it', $map);

        $this->assertEquals($map['en']->getPath(), $sourceNode->getPath());
        $this->assertEquals($map['it']->getPath(), $targetNode->getPath());

    }
}
