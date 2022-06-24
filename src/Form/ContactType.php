<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'placeholder' => 'Name'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'placeholder' => 'Email'
                ]
            ])
            ->add('subject', TextType::class, [
                'label' => 'Subject',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'placeholder' => 'Subject'
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => [
                    'class' => 'form-control mt-1',
                    'placeholder' => 'Message',
                    'rows' => 8
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success btn-lg px-3',
                    'label' => 'Let’s Talk'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
