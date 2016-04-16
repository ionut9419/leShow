<?php

namespace SpectateBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RezervationControllerTest extends WebTestCase
{
    public function testRezervation()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/rezervation');
    }

}
