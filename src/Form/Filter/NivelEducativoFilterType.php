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

class NivelEducativoFilterType extends FilterType
{   
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder   
            ->add('nivelEducativo', ChoiceType::class, [
                'multiple' => false,
                'label'=>false,            
                'attr' => ['data-widget' => 'select2', 'readonly' => 'readonly'],
                'choices'  => [
                    'Primario completo' => 'Primario completo',
                    'Primario incompleto' => 'Primario incompleto',
                    'Secundario completo' => 'Secundario completo',
                    'Secundario incompleto' => 'Secundario incompleto',
                    'Terciario completo' => 'Terciario completo',
                    'Terciario incompleto' => 'Terciario incompleto',
                    'Universitario incompleto' => 'Universitario incompleto',
                    'Universitario completo' => 'Universitario completo',
                    'Posgrado' => 'Posgrado',
                ],
                ]);
    }

    public function filter(QueryBuilder $queryBuilder, FormInterface $form, array $metadata)
    {
        $queryBuilder->join('entity.alumno', 'a');
        $queryBuilder->orWhere('a.nivelEducativo LIKE :word')
            ->setParameter('word', '%' . $form->getData()['tipoDiscapacidad'] . '%');
    }
}