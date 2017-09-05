<?php

namespace AppBundle\Form;

use AppBundle\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\CallbackTransformer;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class)
            ->add('thumbnail', TextType::class)
            ->add('description', TextType::class)
            ->add('image', FileType::class,
                array('label' => 'Image (optional)',
                    'required' => false))
            ->add('price', IntegerType::class);

        $builder->get('image')
            ->addModelTransformer(new CallbackTransformer(
                function ($imageAsString) {
                    if($imageAsString == null)
                        return $imageAsString;

                    return new File('images/' . $imageAsString);
                },
                function ($imageAsFile) {
                    return $imageAsFile;
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}