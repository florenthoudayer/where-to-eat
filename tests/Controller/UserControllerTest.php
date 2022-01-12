<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('#body h3', 'Latest restaurants');
    }

    public function testRegister()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $client->clickLink('Register');
        $this->assertSelectorTextContains('#body h3','Register');

        //When not Work
        $client->submitForm('Register', [
            'user[firstName]' =>'',
        ]);
        $this->assertEquals(4, $client->getCrawler()->filter('.has-error')->count());

        //When work
        $client->submitForm('Register', [
            'user[firstName]' =>'Jospeh',
            'user[lastName]' =>'Jospeh',
            'user[email]' =>'josepeh@jo.com',
            'user[password]' =>'testest',
            'user[cgu]' =>true,
        ]);
        $this->assertEquals(0, $client->getCrawler()->filter('.has-error')->count());
        /** @var UserRepository */
        $userRepo = $client->getContainer()->get(UserRepository::class);
        $user = $userRepo->findOneBy(['email' => 'josepeh@jo.com']);
        $this->assertEquals('josepeh@jo.com', $user->getEmail());

    }
}
