<?php

namespace App\DataFixtures;

use App\Entity\SmsMessage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SmsMessageFixtures extends Fixture
{


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $limit = 100;

        for ($i = 0; $i < $limit; $i++) {
            $message = new SmsMessage();
            $message->setMessage($faker->sentence(15));
            $manager->persist($message);
        }
        $manager->flush();
    }
}