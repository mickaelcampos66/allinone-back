<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use App\Utility\Time\TimeConverter;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppUserFixtures extends Fixture {
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasherInterface) {
        $this->passwordHasher = $passwordHasherInterface;
    }

    public function load(ObjectManager $manager): void {
        $currentDate = new DateTimeImmutable();

        $user = new User();
        $user->setEmail('user@user.fr');
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'allinone');
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);
        $user->setUsername('user');
        $user->setCreatedAt($currentDate);

        $manager->persist($user);

        $managerUser = new User();
        $managerUser->setEmail('manager@manager.fr');
        $hashedPassword = $this->passwordHasher->hashPassword($managerUser, 'allinone');
        $managerUser->setPassword($hashedPassword);
        $managerUser->setRoles(['ROLE_CATALOG_MANAGER']);
        $managerUser->setUsername('manager');
        $managerUser->setCreatedAt($currentDate);

        $manager->persist($managerUser);

        $adminUser = new User();
        $adminUser->setEmail('admin@admin.fr');
        $hashedPassword = $this->passwordHasher->hashPassword($adminUser, 'allinone');
        $adminUser->setPassword($hashedPassword);
        $adminUser->setRoles(['ROLE_ADMIN']);
        $adminUser->setUsername('admin');

        $adminUser->setCreatedAt($currentDate);

        $manager->persist($adminUser);

        $manager->flush();
    }
}
