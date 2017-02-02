<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersControllerTest extends WebTestCase
{
    public function testPostUsersActionCorrect()
    {
        $client = static::createClient();

        $client->request('POST',
            '/api/users',
            ['username' => 'Fabien']
        );

        $this->assertEquals(
            $client->getResponse()->isSuccessful(),
            $client->getResponse()->getStatusCode()
        );
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
        $this->assertContains('apikey', $client->getResponse()->getContent());
    }

    public function testPostUsersActionWrongUsername()
    {
        $client = static::createClient();

        $client->request('POST',
            '/api/users',
            ['username' => 'f']
        );

        $this->assertEquals(
            $client->getResponse()->isClientError(),
            $client->getResponse()->getStatusCode()
        );
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
        $this->assertContains('error', $client->getResponse()->getContent());
    }

    public function testPostUsersActionEmpty()
    {
        $client = static::createClient();

        $client->request('POST',
            '/api/php users'
        );

        $this->assertEquals(
            $client->getResponse()->isClientError(),
            $client->getResponse()->getStatusCode()
        );
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
    }
}
