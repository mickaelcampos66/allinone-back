<?php

namespace App\DataFixtures;

use App\Entity\Media;
use DateTimeImmutable;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppShowFixtures extends Fixture {
    public function load(ObjectManager $manager): void {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Xylis\FakerCinema\Provider\TvShow($faker));

        $category = new Category();
        $now = new DateTimeImmutable();
        $category->setCreatedAt($now);

        $serie = $category->setName('serie');

        for ($nbShowToAdd = 1; $nbShowToAdd <= 50; $nbShowToAdd++) {

            $posterUrl = "https://picsum.photos/id/" . mt_rand(0, 1084) . "/200/300";
            $media = new Media();
            $media->setTitle($faker->tvShow());
            $media->setSummary($faker->realText(20));
            $media->setReleaseDate(mt_rand(1980, 2023));
            $media->setSynopsis($faker->realTextBetween(140, 200));
            $media->setPicture($posterUrl);
            $media->setDuration(mt_rand(45, 180));
            $media->setCategory($serie);
            $media->setCreatedAt($now);

            $manager->persist($media);
        }

        $manager->flush();
    }
}
