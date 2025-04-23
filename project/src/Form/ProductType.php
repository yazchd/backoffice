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
            TextType::class
            )
            ->add('description' , 
            TextareaType::class
            )
            ->add('slug', TextType::class)
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('thumbnailFile', FileType::class, [
                'label' => 'Thumbnail',
                'mapped' => false,
                'constraints' => [
                    new Image()
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                ],
            )
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
