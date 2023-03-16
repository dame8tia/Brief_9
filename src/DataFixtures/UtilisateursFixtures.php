<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Utilisateurs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateursFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {}

    public function load(ObjectManager $manager): void
    {
        $this->createUtilisateur('admin@mailoo.org', 'laetiti', 'ROLE_ADMIN', 'admin',$manager);
        $this->createUtilisateur('user@mailoo.org', 'utilisateur', 'ROLE_USER', 'azerty',$manager);
        //$this->createUtilisateur('Action',$manager);

        $manager->flush();
    }

    public function createUtilisateur(string $mail, string $pseudo, string $role, string $pwd,
                    ObjectManager $manager)
    {
        $user = new Utilisateurs;
        $user->setEmail($mail);
        $user->setPseudo($pseudo);
        $user->setRoles([$role]);

        
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $pwd
        );
        $user->setPassword($hashedPassword);
        //$user->setPassword($pwd);

        $manager->persist($user);

        return $user;
    }
}