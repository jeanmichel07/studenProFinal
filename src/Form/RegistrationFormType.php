<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('picture_student', FileType::class,[
                'label' => 'Votre photo: ',
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Adresse email :',
            ])
            ->add('name', TextType::class, [
                'label'=> 'Nom :'
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom(s) :'
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Mot de passe :'],
                'second_options' => ['label' => 'Confirmez votre mot de passe :']
            ])

            ->add('number', TextType::class, [
                'label' => 'Téléphone: '
            ])
            ->add('school', TextType::class, [
                'label' => 'Nom de votre école/université :'
            ])
            ->add('level_study', ChoiceType::class, [
                'choices' => [
                    'Collège' => 'middle_school',
                    'Lycée' => 'high_school',
                    'Université' => 'university'
                ],
                'label_html' => true,
                'expanded' => true,
                'multiple' => false,
                'label' => "Votre niveau d'étude :"
            ])

        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
