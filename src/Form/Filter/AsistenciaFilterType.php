<?php
namespace App\Form\Filter;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\FilterType;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class AsistenciaFilterType extends FilterType
{   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('porcentajedesde', NumberType::class, [
            'attr' => [
                'min' => 0,
                'max' => 100
            ]
        ])
        ->add('porcentajehasta', NumberType::class, [
            'attr' => [
                'min' => 0,
                'max' => 100
            ]
        ])
        ;
    }

    public function filter(QueryBuilder $queryBuilder, FormInterface $form, array $metadata)
    {
        if ($form->getData()['porcentajedesde']) {
            $queryBuilder->andWhere('entity.porcAsistencia >= :desde');
            $queryBuilder->setParameter('desde', $form->getData()['porcentajedesde']);
        }
        if ($form->getData()['porcentajehasta']) {
            $queryBuilder->andWhere('entity.porcAsistencia <= :hasta');
            $queryBuilder->setParameter('hasta', $form->getData()['porcentajehasta']);
        }
    }
}