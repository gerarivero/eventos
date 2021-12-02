<?php
namespace App\Form;

use App\Entity\Alumno;
use App\Entity\Persona;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints\ValidCaptcha as CaptchaAssert;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AlumnoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cud', null, [
                'attr' => [
                    'hidden'=> true
                ],
                'label_attr' => [
                    'hidden'=> true
                ]
            ])            
            ->add('ocupacion', null, [
                'label' => 'Ocupación',                
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Ocupación',
                    'title' => "Ocupación",
                    'tabindex' => "16",
                    'data-toggle' => "tooltip", 
                    'data-placement' => "top", 
                    'title' => "Ingrese su Ocupación",
                    'aria-invalid' => "false"
                )
            ])
            ->add('nivelEducativo', ChoiceType::class, [
                'placeholder' => 'seleccione una opción',
                'multiple' => false,                
                'expanded' => false,
                'label' => 'Nivel Educativo',
                'attr' => [
                    "class" => 'form-control',
                    'data-toggle' => "tooltip", 
                    'data-placement' => "top", 
                    'title' => "Ingrese su Nivel Educativo",
                    'tabindex' => "16",
                    'aria-invalid' => "false"
                ],
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
            ])
            ->add('discapacidad', CheckboxType::class, [     
                'label' => 'Tiene discapacidad?',
                'required' => false,                
                'attr' => array(
                    'onclick' => "displayDiscapacidades();",                    
                    'class' => 'switch',                    
                    'title' => "Discapacidad si/no",
                    'tabindex' => "16",
                    'data-toggle' => "tooltip", 
                    'data-placement' => "top", 
                    'title' => "Indique si posee algún tipo de discapacidad",
                    'aria-invalid' => "false"
                )
            ])            
            ->add('tipoDiscapacidad', ChoiceType::class, [                
                'multiple' => true,
                'expanded' => true,
                'label' => 'Tipo de Discapacidad',
                'attr' => [                    
                    'data-toggle' => "tooltip", 
                    'data-placement' => "top", 
                    'title' => "Ingrese Tipo de Discapacidad",
                    'tabindex' => "16",
                    'aria-invalid' => "false"                    
                ],
                'choices'  => [
                    'Física Motora' => 'Física Motora',
                    'Física Visceral' => 'Física Visceral',
                    'Intelectual' => 'Intelectual',
                    'Sensorial Auditiva' => 'Sensorial Auditiva',
                    'Sensorial Visual' => 'Sensorial Visual',
                    'Mental' => 'Mental',                    
                ],                
                'choice_attr' => function($choice, $key, $value) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['tabindex' => '16', 'class' => 'checkbox-2x'];
                },
            ])            
            /*->add('vencCud', DateType::class, [
                'required' => false,                
                'label' => 'Fecha de vencimiento de CUD',                
                'widget' => 'choice',
                'input'  => 'datetime',
                'placeholder' => [
                    'year' => 'Año', 'month' => 'Mes', 'day' => 'Dia',
                ],                
                'years' => range(date('Y')-100, date('Y')),
                'attr' => [                    
                    'data-toggle' => "tooltip", 
                    'data-placement' => "top", 
                    'title' => "Ingrese Fecha de vencimiento de CUD",
                    'tabindex' => "16",
                ]                
                //'data' => date('Y-m-d HH:ii'),                           
            ])*/
            ->add('medioInfo', TextareaType::class, [  
                'required' => false,              
                'label' => 'Medio de información por el cual accedió a la capacitación:',
                'attr' => array(
                    'class' => 'form-control',                    
                    'title' => "Medio de información por el cual accedió a la capacitación:",
                    'tabindex' => "16",
                    'data-toggle' => "tooltip", 
                    'data-placement' => "top", 
                    'title' => "Ingrese el medio de información por el cual accedió a la capacitación:",
                    'aria-invalid' => "false"
                )
            ])        
            ->add('apoyosTecnicos', TextareaType::class, [
                'required' => false,                
                'label' => 'Apoyos técnicos (Describir y/o enumerar las modificaciones o adaptaciones necesarias o adecuadas que
                requiera)',
                'attr' => array(
                    'class' => 'form-control',                    
                    'title' => "apoyos técnicos",
                    'tabindex' => "16",
                    'data-toggle' => "tooltip", 
                    'data-placement' => "top", 
                    'title' => "Ingrese APOYOS TECNICOS que necesite",
                    'aria-invalid' => "false"
                )
            ])
            ->add('temasInteres', TextareaType::class, [
                'required' => false,                
                'label' => '¿Qué otros temas le interesaría que se dicten en futuras capacitaciones?',
                'attr' => array(
                    'class' => 'form-control',                    
                    'title' => "¿Qué otros temas le interesaría que se dicten en futuras capacitaciones?",
                    'tabindex' => "16",
                    'data-toggle' => "tooltip", 
                    'data-placement' => "top", 
                    'title' => "Ingrese otros temas le interesaría que se dicten en futuras capacitaciones",
                    'aria-invalid' => "false"
                )
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Alumno::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_alumno';
    }
}
