<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Jeux;
use Symfony\Component\String\Slugger\SluggerInterface;

class JeuxFixtures extends Fixture
{
    private $count=1;

    public function __construct(private sluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {
        $this->createJeu('Resident Evil 2','https://www.instant-gaming.com/fr/3666-acheter-resident-evil-2-biohazard-re-2-deluxe-edition-deluxe-edition-pc-jeu-steam/'
        ,'Le survival-horror Resident Evil 2 sur Nintendo 64 se déroule quelques mois après les événements de son prédécesseur. Le virus-T a réussi à se propager et la ville entière est désormais contaminée. Vous contrôlez Leon S. Kennedy et Claire Redfield, et devrez tenter de survivre et de percer le secret de l\'organisation Umbrella.'
        ,'img/Resident_Evil_2.jpg', 0.0, '2000-02-01',$manager);
        $this->createJeu('V-rally','https://www.instant-gaming.com/fr/2681-acheter-jeu-steam-v-rally-4/'
        ,'V-Rally est un jeu de courses sur Playstation. Faites votre choix entre 4 bolides : Peugeot 306 Maxi, Subaru Impreza, Ford Escort V-Rally, et Mitsubishi Lancer. Lancez-vous en mode Arcade ou Championnat dans les principaux endroits où se déroule le vrai Championnat du Monde des rallyes : Corse, Nouvelle Zélande, Italie, Les Alpes, Indonésie, Argentine, Grèce, Angleterre, Suède...'
        ,'img/v-rally.png', 0.0, '1997-07-01',$manager);
        $this->createJeu('Minecraft','https://www.minecraft.net/fr-fr/get-minecraft'
        ,'Jeu bac à sable indépendant et pixelisé dont le monde infini est généré aléatoirement, Minecraft permet au joueur de récolter divers matériaux, d\'élever des animaux et de modifier le terrain selon ses choix, en solo ou en multi (via des serveurs). Il doit également survivre en se procurant de la nourriture et en se protégeant des monstres qui apparaissent la nuit et dans des donjons. Il peut également monter de niveau afin d\'acheter des enchantements.'
        ,'img/Minecraft.jpg', 0.0, '2011-11-18',$manager);
        $this->createJeu('Colin McRae','https://www.g2a.com/fr/colin-mcrae-rally-steam-key-global-i10000007185004'
        ,'Colin McRae Rally est un jeu de course sur PC. Il s\'agit d\'une version remastérisée de la version iOS du jeu, qui propose 30 niveaux et une distance totale de course supérieure à 130 km tout en se basant sur la technologie employée pour le deuxième épisode de la série.'
        ,'img/ColinMcRae.jpg', 0.0,'2013-03-27',$manager);
        $this->createJeu('Skyrim','https://www.g2a.com/fr/the-elder-scrolls-v-skyrim-special-edition-pc-steam-key-global-i10000029090004'
        ,'The Elder Scrolls V : Skyrim est le cinquième épisode de la saga de jeux de rôle du même nom. Le scénario se passe 200 ans après l\'histoire du quatrième opus, quand Alduin fait son retour au milieu d\'une guerre civile. Seul le Dovahkiin, incarné par le joueur, peut mettre un terme à cette sombre affaire... Un monde gigantesque empli de quêtes est à explorer et auquel se sont rajoutées des extensions débloquant plus de quêtes.'
        ,'img/Skyrim.jpg', 0.0,'2011-11-11',$manager);
        $this->createJeu('Warframe','https://www.warframe.com/fr'
        ,'Warframe est un TPS gratuit se déroulant dans l\'espace. Le joueur y incarne un guerrier Tenno utilisant une combinaison Warframe. Il doit mener à bien des missions dans tout le système solaire, en solitaire ou en coopération en ligne.'
        ,'img/Warframe.jpg', 0.0,'2013-03-21',$manager);

        $manager->flush();
    }

    public function createJeu(string $title, string $url, $description, 
                    string $image, $note, $date_sortie,
                    ObjectManager $manager)
    {
        $jeu = new Jeux;
        $jeu->setTitle($title);
        $jeu->setUrl($url);
        $jeu->setDescription($description);
        $jeu->setImage($image);
        $jeu->setNote($note);
        $date_sortie = new \DateTimeImmutable('@'.strtotime($date_sortie));
        $jeu->setDateSortie($date_sortie);
        $slug = $this->slugger->slug($jeu->getTitle())->lower();
        $jeu->setSlug($slug);

        //fk Jeu
        //$genre = $this->getReference('Genre-'.rand(1,14));
        //$genre->addGenre($genre);

        $manager->persist($jeu);

        $this->addReference('Jeu-'.$this->count, $jeu);
        $this->count++;

        return $jeu;

    }

    
}