<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Specialty;
use App\Entity\Matiere;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class RegistrationStudyProType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('picture_student', FileType::class,[
                'label' => 'Votre photo: ',
                'required' => false
            ])
            ->add('email', EmailType::class,[
                'required' => true,
                'label' => 'Adresse email :',
            ])
            ->add('name', TextType::class,[
                'label' => 'Nom :'
            ])
            ->add('firstname', TextType::class,[
                'label' => 'Prénom(s) :'
            ])
            //Pré-inscription du Studen Pro donc pas besoin de mot de passe
            /* ->add('password', RepeatedType::class, [
                'type' => PasswordsType::class,
                'first_options' => ['label' => 'Mot de passe :'],
                'second_options' => ['label' => 'Confirmez votre mot de passe :']
            ])*/
            ->add('number', TextType::class, [
                'label' => 'Téléphone: '
            ])
            ->add('level_study', TextType::class,[
                'label' => "Votre dernier diplôme :"
            ])
            ->add('level_study_occuped', ChoiceType::class, [
                'choices' => [
                    'Collège' => 'middle_school',
                    'Lycée' => 'high_school',
                    'Université' => 'university'
                ],
                'label_html' => true,
                'expanded' => true,
                'multiple' => false,
                'label' => "Quel niveau d'études souhaitez-vous prendre en charge ?"
            ])
          //  ->add('speciality', TextType::class,[
           //     'label' => 'Votre domaine de spécialisation:'
            //])

            ->add('file_cv', FileType::class, array(
                'label' => 'Joindre CV'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
