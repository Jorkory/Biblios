<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Editor;
use App\Enum\BookStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('isbn', TextType::class, [
                'label' => 'Numéro ISBN',
            ])
            ->add('cover', UrlType::class, [
                'label' => 'Couverture de livre (lien URL)',
            ])
            ->add('editedAt', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'label' => 'Date d\'édition',
            ])
            ->add('plot', TextareaType::class, [
                'label' => 'Synopsis',
            ])
            ->add('pageNumber', NumberType::class, [
                'label' => 'Nombre des pages',

            ])
            ->add('status', EnumType::class, [
                'class' => BookStatus::class,
                'label' => 'Statut',
                ])
            ->add('Editor', EntityType::class, [
                'class' => Editor::class,
                'choice_label' => 'name',
                'label' => 'Éditeur',
            ])
            ->add('authors', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'name',
                'multiple' => true,
                'label' => 'Auteurs',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
