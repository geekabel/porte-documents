<?php

namespace App\Form;

use App\Entity\Document;
use App\Entity\PorteDocument;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('refnumero',TextType::class,[])
            ->add('sujet',TextType::class,[])
            ->add('description',TextareaType::class,)
            ->add('file',FileType::class,
            [
                'required'=>true
            ])
            ->add('porteDocument',EntityType::class, [
                "class"=> PorteDocument::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
