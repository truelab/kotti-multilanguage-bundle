<?php

namespace Truelab\KottiMultilanguageBundle\Tests\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class DefaultControllerTest
 * @package Truelab\KottiMultilanguageBundle\Tests\Controller
 * @group functional
 */
class DefaultControllerTest extends WebTestCase
{
    /**
     * @dataProvider providePaths
     *
     * @param $path
     */
    public function testGetActionIsSuccessful($path)
    {
        $client = self::createClient();

        $client->followRedirects();
        $client->request('GET', $path);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function providePaths()
    {
        return array(
            array('/')
        );
    }
}
