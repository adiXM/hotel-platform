<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('adrianmarian906@gmail.com');
        $user->setFirstName('Adrian');
        $user->setLastName('Gemene');
        $user->setPassword($this->generateHashPassword($user, '123456'));
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $manager->persist($user);
        $manager->flush();
    }

    private function generateHashPassword(User $user, string $plainPassword): string
    {
        return $this->passwordHasher->hashPassword(
            $user,
            $plainPassword
        );
    }
}
