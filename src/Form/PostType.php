<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Post;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PostType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('titre')
            ->add('description')
            ->add('image', FileType::class)
            ->add('datePublication')
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'libelle',
            ])
            ->add('utilisateur', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'nom',
            ]);
        /* la facon pour declarer un type object :
        ->add('category', EntityType::class, [
            'class' => Category::class,
            'choice_label' => 'title'
        ])*/
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
