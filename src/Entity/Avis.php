<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: AvisRepository::class)]
#[UniqueEntity(
    fields: ['utilisateur', 'jeu'],
    errorPath: 'utilisateur',
    message: 'Vous avez déjà partagé votre avis sur ce jeu.' /* Je ne sais pas trop comment le faire apparaitre ; je suis passée par le controleur et les addFlash */
)]

class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $Note = null;

    #[ORM\Column(length: 255)]
    private ?string $Commentaire = null;

    #[ORM\Column]
    private ?bool $is_valid = null;


    #[ORM\ManyToOne(inversedBy: 'avis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Jeux $jeu = null;

    #[ORM\ManyToOne(inversedBy: 'avis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateurs $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->Note;
    }

    public function setNote(int $Note): self
    {
        $this->Note = $Note;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->Commentaire;
    }

    public function setCommentaire(string $Commentaire): self
    {
        $this->Commentaire = $Commentaire;

        return $this;
    }

    public function isis_Valid(): ?bool
    {
        return $this->is_valid;
    }

    public function setis_Valid(bool $is_valid): self
    {
        $this->is_valid = $is_valid;

        return $this;
    }

    public function getJeu(): ?Jeux
    {
        return $this->jeu;
    }

    public function setJeu(?Jeux $jeu): self
    {
        $this->jeu = $jeu;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateurs
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateurs $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
