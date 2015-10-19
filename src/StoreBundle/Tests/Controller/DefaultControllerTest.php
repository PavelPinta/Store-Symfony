<?php

namespace StoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testGetimage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/images');
    }

}
