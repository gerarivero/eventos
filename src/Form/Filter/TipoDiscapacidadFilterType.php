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

class TipoDiscapacidadFilterType extends FilterType
{   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder   
            ->add('tipoDiscapacidad', ChoiceType::class, [
                'multiple' => false,
                'label'=>false,            
                'attr' => ['data-widget' => 'select2', 'readonly' => 'readonly'],
                'choices'  => [
                    'Física Motora' => 'Física Motora',
                    'Física Visceral' => 'Física Visceral',
                    'Intelectual' => 'Intelectual',
                    'Sensorial Auditiva' => 'Sensorial Auditiva',
                    'Sensorial Visual' => 'Sensorial Visual',
                    'Mental' => 'Mental',                    
                ],
                ]);
    }

    public function filter(QueryBuilder $queryBuilder, FormInterface $form, array $metadata)
    {
        $queryBuilder->join('entity.alumno', 'a');
        $queryBuilder->orWhere('a.tipoDiscapacidad LIKE :word')
            ->setParameter('word', '%' . $form->getData()['tipoDiscapacidad'] . '%');
    }
}