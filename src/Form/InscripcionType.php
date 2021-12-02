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
use App\Form\AlumnoType;

class InscripcionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', null, [
                'label' => 'Nombre',
                'required' => true,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Nombre',
                    'title' => "nombre",
                    'tabindex' => "16",
                    'aria-required' => "true",
                    'data-toggle' => "tooltip", 
                    'data-placement' => "top", 
                    'title' => "Ingrese su nombre",
                    'aria-invalid' => "false"
                )
            ])
            ->add('apellido', null, [
                'label' => 'Apellido',
                'required' => true,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Apellido',
                    'title' => "apellido",
                    'tabindex' => "16",
                    'aria-required' => true,
                    'data-toggle' => "tooltip", 
                    'data-placement' => "top", 
                    'title' => "Ingrese su apellido",
                    'aria-invalid' => "false"
                )
            ])           
            ->add('dni', NumberType::class, [
                'html5' => true,
                'label' => 'Documento Nacional de Identidad (DNI)',
                'input' => 'number',
                'required' => true,
                'attr' => array(
                    'step' => false,
                    'class' => 'form-control',
                    'placeholder' => 'Ej.: 36129087',
                    'title' => "dni",
                    'tabindex' => "16",
                    'aria-describedby' =>"tip1",
                    'aria-required' => true,                    
                    'data-toggle' => "tooltip", 
                    'data-placement' => "top", 
                    'title' => "Ingrese su número de documento nacional de identidad",
                    'aria-invalid' => "false"
                )
            ])
            ->add('email', null, [
                'label' => 'Correo Electrónico',
                'required' => true,
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Ej.: correo@dominio.com',
                    'pattern' => "[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$",
                    'title' => "email",
                    'tabindex' => "16",
                    'aria-required' => true,
                    'aria-describedby' =>"tip2",
                    'data-toggle' => "tooltip", 
                    'data-placement' => "top", 
                    'title' => "Ingrese su Correo Electrónico",
                    'aria-invalid' => "false"
                )
            ])            
            ->add('fechaNacimiento', DateType::class, [
                'label' => 'Fecha de nacimiento',
                'required' => true,                
                'widget' => 'choice',
                'input'  => 'datetime',
                'placeholder' => [
                    'year' => 'Año', 'month' => 'Mes', 'day' => 'Dia',
                ],
                'format' => 'dd-MMMM-yyyy',                
                'years' => range(date('Y')-100, date('Y')),
                'attr' => [
                    'tabindex' => '16',
                    'aria-required' => true,
                    'data-toggle' => "tooltip", 
                    'data-placement' => "top", 
                    'title' => "Ingrese su Fecha de nacimiento",
                    'tabindex' => [
                        'year' => '16', 'month' => '16', 'day' => '16',
                    ],
                    'aria-invalid' => "false"
                ]
                                
                //'data' => date('Y-m-d HH:ii'),                           
            ])
            ->add('localidad', null, [
                //'empty_data' => "asd",
                'required' => true,
                'group_by' => 'departamento',
                'placeholder' => 'seleccione una localidad',
                'attr' => array(
                    'class' => 'form-control',
                    'data-widget' => 'select2',                    
                    'placeholder' => 'localidad',
                    'title' => "localidad",
                    'tabindex' => "16",
                    'aria-required' => true,
                    'data-toggle' => "tooltip", 
                    'data-placement' => "top", 
                    'title' => "Ingrese localidad",
                    'aria-invalid' => "false"
                )
            ])
            
            ->add('telefono', TextType::class, [
                'label' => 'Teléfono Celular (obligatorio para recibir notificaciones del IPRODICH)',   
                'attr' => array(
                    //'type' => 'text',
                    'class' => 'form-control',
                    //'placeholder' => '0362-154334455',                    
                    'tabindex' => "16",
                    'aria-required' => true,
                    'data-toggle' => "tooltip", 
                    'data-placement' => "top", 
                    'data-inputmask'=> "'mask': '0 (99[9][9])- 15 9999999[99]'",
                    'title' => "Ingrese su Teléfono",
                    'aria-invalid' => "false"
                )
            ])
            ->add('telefonoFijo', null, [
                'label' => 'Teléfono Fijo',   
                'attr' => array(
                    //'type' => 'number',
                    'class' => 'form-control',
                    //'placeholder' => '0362-44456426',
                    'title' => "teléfono fijo",
                    'tabindex' => "16",
                    'aria-required' => true,
                    'data-toggle' => "tooltip", 
                    'data-placement' => "top", 
                    'data-inputmask'=> "'mask': '0 (99[9][9])- 9999999[99]'",
                    'title' => "Ingrese su Teléfono",
                    'aria-invalid' => "false"
                )
            ])
            ->add('direccion', null, [
                'label' => 'Dirección',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'B° 500 Viviendas Mz 50 Casa 6 Calle 123',
                    'title' => "dirección",
                    'tabindex' => "16",
                    'aria-required' => true,
                    'data-toggle' => "tooltip", 
                    'data-placement' => "top", 
                    'title' => "Ingrese su Dirección",
                    'aria-invalid' => "false"
                )
            ])
            ->add('alumno', AlumnoType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Persona::class,
            'error_mapping' => [
                '.' => 'nombre',
            ],
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
