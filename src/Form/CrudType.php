<?php

namespace App\Form;

use App\Entity\Jeux;
use App\Entity\Genre;
use App\Repository\GenreRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints As Assert;



use Symfony\Component\String\Slugger\SluggerInterface;


class CrudType extends AbstractType
{

    public function __construct(SluggerInterface $slugger){}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Title', TextType::class, [
                    'attr'=>[
                        'class' =>'form-control', 
                        'maxlength' => 100,
                    ],
                    'label' => 'Nom du Jeu',
                    'label_attr' => [
                        'class'=> 'form_label'
                    ],
                    'constraints'=> [
                        new Assert\Length(['max'=>100,]),
                    ],
                    'required'=> true,
                ])
            ->add('Url', UrlType::class, [
                    'attr'=>[
                        'class' =>'form-control', 
                        'maxlength' => 255,
                    ],
                    'label' => 'Lien vers Page officielle',
                    'label_attr' => [
                        'class'=> 'form_label'
                    ],
                ])
            ->add('Description', TextareaType::class, [
                    'attr'=>[
                        'class' =>'form-control', 
                    ],
                    'label' => 'Description',
                    'label_attr' => [
                        'class'=> 'form_label'
                    ],
                    'required'=> true,
                ])
            ->add('Image', TextType::class, [
                'attr'=>[
                    'class' =>'form-control', 
                ],
                'label' => 'Lien vers la jacket du jeu',
                'label_attr' => [
                    'class'=> 'form_label'
                ]
            ])
            ->add('Note', NumberType::class, [
                'attr'=>[
                    'class' =>'form-control', 
                ],
                'label' => 'Note moyenne',
                'label_attr' => [
                    'class'=> 'form_label'
                ],
                'scale'=>1
            ])
            ->add('Date_sortie', DateType::class, [
                //'widget' => 'choice',
                'widget' => 'single_text', 
                'input'  => 'datetime_immutable',
                'html5' => false,
                'label' => 'Date de sortie',
                'label_attr' => [
                    'class'=> 'form_label'
                ]
                ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label'=> 'Enregistrer'
                ])

            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                'query_builder' => function(GenreRepository $g){
                    return $g->createQueryBuilder('sql')
                        ->orderBy('sql.title', 'ASC');
                },
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true
                ])

            //$slug = $this->slugger->slug($jeu->getTitle())->lower();
        // $builder
             ->add('Slug', TextType::class, [
                 'attr'=>[
                     'class' =>'form-control', 
                     'maxlength' => 100,
                 ],
                 'label' => 'Slug',
                 'label_attr' => [
                     'class'=> 'form_label'
                 ],
                 'constraints'=> [
                     new Assert\Length(['max'=>100,]),
                 ],
                 'required'=> true,
                 //'empty_data' => $slug
             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Jeux::class,
        ]);
    }
}
