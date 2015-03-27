<?php

namespace JT\AdsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RatingControllerTest extends WebTestCase
{
    public function testVote()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/{username}/vote');
    }

}
