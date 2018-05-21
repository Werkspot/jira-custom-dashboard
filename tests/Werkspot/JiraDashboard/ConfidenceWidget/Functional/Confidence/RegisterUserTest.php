<?php
declare(strict_types=1);

namespace Werkspot\Tests\Functional\User\Register;

use Werkspot\Tests\DoctrineAwareTestTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    use DoctrineAwareTestTrait;

    private const VALID_EMAIL = 'email@email.com';
    private const VALID_PASSWORD = '1234';

    /**
     * @inheritdoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpEntityManager();
        $this->fixturesLoader();
    }

    /**
     * @test
     */
    public function homepage_shouldShowHomepage()
    {
        $client = static::createClient();

        $client->request('GET', 'http://localhost/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Email', $client->getResponse()->getContent());
        $this->assertContains('Password', $client->getResponse()->getContent());
        $this->assertContains('Repeat Password', $client->getResponse()->getContent());
    }

    /**
     * @test
     */
    public function registerUser_whenDataIsValid_shouldShowNewUserRegisteredMessage()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'http://localhost');

        $form = $crawler->selectButton('register_user[submit]')->form();

        $form['register_user[email]']            = self::VALID_EMAIL;
        $form['register_user[password][first]']  = self::VALID_PASSWORD;
        $form['register_user[password][second]'] = self::VALID_PASSWORD;

        $client->submit($form);
        $client->followRedirect();

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('New user registered', $client->getResponse()->getContent());
    }
}
