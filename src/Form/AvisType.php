<?php

namespace App\Form;

use App\Entity\Jeux;
use App\Repository\JeuxRepository;
use Symfony\Component\Form\AbstractType;
//use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
//use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints As Assert;


class AvisType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //dd($options);
        $builder
            ->add('Commentaire', TextareaType::class, [
                    'attr'=>[
                        'class' =>'form-control', 
                    ],
                    'label' => 'Commentaire',
                    'label_attr' => [
                        'class'=> 'form_label'
                    ],
                    'required'=> true,
                ])
            ->add('Note', RangeType::class, [
                'attr'=>[
                    'class' =>'form-range', 
                    'min' => 1,
                    'max' => 5
                ],
                'required' => true,
                'label' => 'Note',
                'label_attr' => [
                    'class'=> 'form_label'
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(6)
                ]
            ])
            /* ->add('jeu', EntityType::class, [
                'class' => Jeux::class,
                'query_builder' => function(JeuxRepository $g){
                    return $g->createQueryBuilder('sql')
                        ->orderBy('sql.Title', 'ASC');
                    },
                'attr'=>[
                    'class' =>'form-control', 
                ],
                'label' => 'Jeu',
                'label_attr' => [
                    'class'=> 'form_label'
                ],
                'required'=> true,
                ]) */
            /* ->add('is_valid', HiddenType::class, [
                //'class' => 'form-check-input',
                //'label' => 'Valide ?',
                //'required' => true,
                'data' => 'checked',
                //'label_attr' => [
                //    'class' => 'form-check-label'
                //],
                //'constraints' => [
                //    new Assert\NotNull()
                //]
                ]) */
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
                'label'=> 'Enregistrer Avis'
            ]);
    }

/*     public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    } */
}
