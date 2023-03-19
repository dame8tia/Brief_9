<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Genre;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class GenreFixtures extends Fixture implements DependentFixtureInterface
{
    private $count=1;
     
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
        //fk Jeu
        $jeu = $this->getReference('Jeu-'.rand(1,6));
        $genre->addJeux($jeu);

        $manager->persist($genre);

        // Pour traiter les fk
        $this->addReference('Genre-'.$this->count, $genre);
        $this->count++;

        return $genre;
    }

    public function getDependencies()
    {
        return array(
            JeuxFixtures::class
        );
    }
}