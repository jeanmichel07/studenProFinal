<?php

namespace App\Form;

use App\Entity\Matiere;
use App\Entity\PublicationStudent;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublicationStudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('explication_visio', CheckboxType::class, [
                'attr' => [
                    'class'=>'form-check-input'
                ],
                'required' => false
            ])
            ->add('delay_of_treatement', ChoiceType::class,[
                'label' => 'Délai de traitement',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder'=> 'Matière'
                ],
                'placeholder' => '',
                'choices' => [
                    '1h à 3h' => '1h à 3h',
                    '1 jour' => '1 jour',
                    '2 jours' => '2 jours',
                    '3 jours' => '3 jours',
                    'Plus de 3 jours' => 'plus de 3 jours',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PublicationStudent::class,
        ]);
    }
}
