<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Genre;
use Symfony\Component\String\Slugger\SluggerInterface;

class GenreFixtures extends Fixture
{

    public function __construct(private sluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {
        $this->createGenre('Course',$manager);
        $this->createGenre('Aventure',$manager);
        $this->createGenre('Action',$manager);
        $this->createGenre('MMO',$manager);
        $this->createGenre('Zombies',$manager);
        $this->createGenre('Post apocalyptique',$manager);
        $this->createGenre('Contemporain',$manager);
        $this->createGenre('Sport',$manager);
        $this->createGenre('RPG',$manager);
        $this->createGenre('Survie',$manager);
        $this->createGenre('Création',$manager);
        $this->createGenre('Open World',$manager);
        $this->createGenre('Sandbox',$manager);

        $manager->flush();
    }

    public function createGenre(string $title,
                    ObjectManager $manager)
    {
        $genre = new Genre;
        $genre->setTitle($title);
        $slug = $this->slugger->slug($genre->getTitle())->lower();
        $genre->setSlug($slug);

        $manager->persist($genre);

        return $genre;
    }
}