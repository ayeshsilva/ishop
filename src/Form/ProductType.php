<?php

namespace App\Form;

use App\Entity\Category;

use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;


class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price', MoneyType::class)
            ->add('stock')
            ->add('category', EntityType::class, [
                'placeholder' => 'Select category',
                'class' => Category::class,
                'choice_label' => 'name',

            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'default Image',
                'required' => true,
                'allow_delete' => false,
                'delete_label' => false,
                'download_label' => false,
                'download_uri' => true,
                'image_uri' => true,
                'imagine_pattern' => 'my_thumb',

            ])
            ->add('images', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
