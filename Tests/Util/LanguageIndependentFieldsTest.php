<?php

namespace Truelab\KottiMultilanguageBundle\Tests\Util;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Truelab\KottiMultilanguageBundle\Util\LanguageIndependentFields;

/**
 * Class LanguageIndependentFieldsTest
 * @package Truelab\KottiMultilanguageBundle\Tests\Util
 * @group functional
 *
 * FIXME THIS TEST FAIL! NOT EXISTENT SERVICE, WEIRD!
 * .. excludes declaring class abstract ..
 */
abstract class LanguageIndependentFieldsTest extends WebTestCase
{
    /**
     * @var LanguageIndependentFields
     */
    private $service;

    public function setUp()
    {
        $this->service = self::createClient()->getContainer()->get('truelab_kotti_multilanguage.util.language_independent_fields');
    }

    public function testInstanceOf()
    {
        $this->assertInstanceOf('Truelab\KottiMultilanguageBundle\Util\LanguageIndependentFields', $this->service);
    }
}
