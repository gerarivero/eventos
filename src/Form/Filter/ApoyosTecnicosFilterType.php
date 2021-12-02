<?php
namespace App\Form\Filter;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\FilterType;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ApoyosTecnicosFilterType extends FilterType
{   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder        
        ->add('apoyosTecnicos', TextType::class, [
            'label'=>false,     
            ]); 
    }
    
    public function filter(QueryBuilder $queryBuilder, FormInterface $form, array $metadata)
    {        
            
            $queryBuilder->join('entity.alumno', 'a')
                ->andWhere('a.apoyosTecnicos = :apoyosTecnicos')
                ->setParameter('apoyosTecnicos', $form->getData()['apoyosTecnicos']);        
    }
}