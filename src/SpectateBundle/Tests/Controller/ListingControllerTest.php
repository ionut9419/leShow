<?php

namespace SpectateBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListingControllerTest extends WebTestCase
{
    public function testListspectate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'view-spectate');
    }

}
