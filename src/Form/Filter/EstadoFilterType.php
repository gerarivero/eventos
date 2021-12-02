<?php
namespace App\Form\Filter;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\FilterType;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EstadoFilterType extends FilterType
{   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder        
        ->add('estado', ChoiceType::class, [            
            'label'=>false,            
            'attr' => ['data-widget' => 'select2', 'readonly' => 'readonly'],
            'choices' => [
                "Pre-inscripto" => "Pre-inscripto",
                "Inscripto" => "Inscripto",
                "Aprobado" => "Aprobado",
                "Desaprobado" => "Desaprobado"
            ]
            ]); 
    }
    
    public function filter(QueryBuilder $queryBuilder, FormInterface $form, array $metadata)
    {        
            
            $queryBuilder->andWhere('entity.estado = :estado')
                ->setParameter('estado', $form->getData()['estado']);        
    }
}