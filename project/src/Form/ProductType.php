<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\FormListenerFactory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ProductType extends AbstractType
{
    public function __construct(private FormListenerFactory $listenerFactory)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title' , 
            TextType::class,
            [
                'label' => 'Title',
                'attr' => [
                    'placeholder' => 'Enter the title of the product',
                    'class' => 'form-control bg-light border-1 small',
                ]
            ]
            )
            ->add('description' , 
            TextareaType::class,    
            [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Enter the description of the product',
                    'class' => 'form-control bg-light border-1 small',
                ]
            ]
            )
            ->add('slug', TextType::class,
            [
                'label' => 'Slug',
                'attr' => [
                    'placeholder' => 'Enter the slug of the product',
                    'class' => 'form-control bg-light border-1 small',
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control bg-light border-1 small',
                ]
            ])
            ->add('thumbnailFile', FileType::class, [
                'label' => 'Thumbnail',
                'mapped' => false,
                'constraints' => [
                    new Image()
                ],
                'attr' => [
                    'class' => 'form-control bg-light border-1 small',
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ])
            // ->addEventListener(FormEvents::SUBMIT, $this->listenerFactory->autoSlug('title'))
            // ->addEventListener(FormEvents::PRE_SUBMIT, $this->listenerFactory->timestamp())
            ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
