<?php

namespace App\DataFixtures;

use App\Entity\Media;
use DateTimeImmutable;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppMovieFixtures extends Fixture {
    public function load(ObjectManager $manager): void {
        // create Faker object to generate fake data
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Movie($faker));

        $category = new Category();
        $now = new DateTimeImmutable();
        $film = $category->setName('film');
        $category->setCreatedAt($now);
        // loop to create 50 films
        for ($nbMovieToAdd = 1; $nbMovieToAdd <= 50; $nbMovieToAdd++) {

            $posterUrl = "https://picsum.photos/id/" . mt_rand(0, 1084) . "/200/300";
            $media = new Media();
            $media->setTitle($faker->movie());
            $media->setSummary($faker->realText(20));
            $media->setReleaseDate(mt_rand(1980, 2023));
            $media->setSynopsis($faker->realTextBetween(140, 200));
            $media->setPicture($posterUrl);
            $media->setDuration(mt_rand(45, 180));
            $media->setCategory($film);
            $media->setCreatedAt($now);
            $manager->persist($media);
        }

        $manager->flush();
    }
}
