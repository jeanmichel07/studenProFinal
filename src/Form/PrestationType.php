<?php

namespace App\Form;

use App\Entity\LineProposition;
use App\Entity\Prestation;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrestationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('lineProposition',EntityType::class,[
                'class' => LineProposition::class,
                'choice_label' => 'id',
                'disabled' =>true,
                'query_builder' => function (EntityRepository $er) use ($options){
                    return $er->createQueryBuilder('h')
                   //     ->leftJoin('h.lineProposition', 's')
                        ->where('h.id = :idLine')
                        ->setParameter('idLine',$options['idLine']);
                }
            ])
            ->add('tarif', NumberType::class,[
                'attr' =>['data-type' => 'double'],
                'required' => true,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prestation::class,
            'idLine' =>0
        ]);
    }
}
