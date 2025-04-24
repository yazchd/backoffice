<?php

namespace App\Form;

use App\Entity\Store;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\PreSubmitEvent;      
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoryType extends AbstractType
{

    public function __construct(private FormListenerFactory $listenerFactory)
    {

    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('slug', TextType::class)
            ->add('store', EntityType::class, [
                'class' => Store::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ])
            // ->addEventListener(FormEvents::SUBMIT, $this->listenerFactory->autoSlug('name'))
            // ->addEventListener(FormEvents::PRE_SUBMIT, $this->listenerFactory->timestamp())
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
