<?php

namespace Truelab\KottiMultilanguageBundle\Tests\Util;
use Truelab\KottiMultilanguageBundle\Util\Language;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class LanguageFunctionalTest
 * @package Truelab\KottiMultilanguageBundle\Tests\Util
 * @group functional
 */
class LanguageFunctionalTest extends WebTestCase
{
    /**
     * @var Language
     */
    private $service;

    public function setUp()
    {
        $this->service = self::createClient()->getContainer()->get('truelab_kotti_multilanguage.util.language');
    }

    public function testInstanceOf()
    {
        $this->assertInstanceOf('Truelab\KottiMultilanguageBundle\Util\Language', $this->service);
    }
}
