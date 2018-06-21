<?php

namespace Fixtures;

use App\Entity\Post\Meta;
use App\Entity\Post\Post;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class PostFixture implements FixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 50; $i++) {
            $post = new Post(
                \DateTimeImmutable::createFromMutable($faker->dateTime),
                trim($faker->sentence, '.'),
                $faker->text(500),
                new Meta(
                    trim($faker->sentence, '.'),
                    $faker->text(200)
                )
            );
            $manager->persist($post);
        }

        $manager->flush();
    }
}