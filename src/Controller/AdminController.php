<?php

namespace App\Controller;

use App\Entity\Curso;
use App\Entity\Inscripcion;
use App\Entity\Persona;
use Wandi\EasyAdminPlusBundle\Controller\AdminController as EasyAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Localidad;
use App\Entity\Notificacion;
use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Congreso;
use App\Entity\InscriptoCongreso;
use App\Entity\InscripcionCongreso;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use PHPExcel_IOFactor;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use App\Entity\Acreditacion;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use EasyCorp\Bundle\EasyAdminBundle\Twig\EasyAdminTwigExtension;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class AdminController extends EasyAdminController
{
    protected function deleteAction()
    {
        $this->denyAccessUnlessGranted(['ROLE_SUPER_ADMIN']);
        parent::deleteAction();    
    }
    
    protected function persistInscripcionEntity($entity)
    {

        parent::persistEntity($entity);
        $date = new \DateTime("now");
        $uInscripcion = $entity->getCurso()->getId() . "" . $entity->getId();

        $codigo = $date->format('y') . "-"
            . strtoupper(dechex((int) $uInscripcion)) . "-"
            . strtoupper(dechex($date->format('mdhiv')));

        $entity->setcodigo($codigo);
        parent::updateEntity($entity);
    }

    protected function persistInscripcionCongresoEntity($entity)
    {
        parent::persistEntity($entity);
        $date = new \DateTime("now");
        $uInscripcion = $entity->getCongreso()->getId() . "" . $entity->getId();

        $codigo = $date->format('y') . "-"
            . strtoupper(dechex((int) $uInscripcion)) . "-"
            . strtoupper(dechex($date->format('mdhiv')));

        $entity->setcodigo($codigo);
        parent::updateEntity($entity);
    }

    public function verInscriptosAction()
    {
        $this->denyAccessUnlessGranted(['ROLE_SUPER_ADMIN', 'ROLE_CURSO', 'ROLE_CURSO_ACREDITADOR']);
        $id = $this->request->query->get('id');
        $filters['curso']['comparison'] = '=';
        $filters['curso']['value'] = $id;
        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'entity' => 'Inscripcion',
            'filters' => $filters
        ));
    }

    public function verInscriptosCongresoAction()
    {
        $this->denyAccessUnlessGranted(['ROLE_SUPER_ADMIN', 'ROLE_ADMIN_CONGRESO']);
        $id = $this->request->query->get('id');
        $filters['congreso']['comparison'] = '=';
        $filters['congreso']['value'] = $id;
        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'entity' => 'InscripcionCongreso',
            'filters' => $filters
        ));
    }

    public function enviarSmsAction()
    {
        $this->denyAccessUnlessGranted(['ROLE_SUPER_ADMIN', 'ROLE_CURSO', 'ROLE_CURSO_ACREDITADOR']);
        $id = $this->request->query->get('id');
        $entity = $this->em->getRepository(Inscripcion::class)->find($id);
        $telefono = $entity->getAlumno()->getPersona()->getTelefono();
        $this->enviarSMS($entity, $telefono);
        // redirect to the 'list' view of the given entity ...
        return $this->redirectToRoute('easyadmin', array(
            'action' => 'list',
            'entity' => $this->request->query->get('entity'),
        ));
    }

    /**
     * @Route("/cambiar_estado", name="cambiar_estado")
     */
    public function cambiarEstado(Request $request)
    {
        $this->denyAccessUnlessGranted(['ROLE_SUPER_ADMIN', 'ROLE_CURSO', 'ROLE_CURSO_ACREDITADOR']);
        $em = $this->getDoctrine()->getManager();
        $estado = $request->get('estado');
        $entity = $request->get('entity');
        $inscripcion = $em->getRepository(Inscripcion::class)->find($entity);
        $inscripcion->setEstado($estado);
        $em->persist($inscripcion);
        $em->flush();
        return new JsonResponse("ok");
    }

    /**
     * @Route("/importar-localidades", name="importar_localidades")
     */
    public function importarLocalidades()
    {
        //Abrimos nuestro archivo
        $medios_totales = fopen("loc-dpto.csv", "r");
        //Lo recorremos
        $datos = fgetcsv($medios_totales, 0, ",");
        $em = $this->getDoctrine()->getManager();
        while (($datos = fgetcsv($medios_totales, 0, ",")) == true) {
            $localidad = new Localidad();
            $localidad->setNombre($datos[2]);
            $localidad->setIdrpcd($datos[0]);
            $em->persist($localidad);
        }
        $em->flush();

        return new JsonResponse("ok");
    }

    public function notificarInscriptosSmsBatchAction(array $ids)
    {
        $class = $this->entity['class'];
        $em = $this->getDoctrine()->getManagerForClass($class);

        foreach ($ids as $id) {
            $entity = $this->em->getRepository(Inscripcion::class)->find($id);
            $telefono = $entity->getAlumno()->getPersona()->getTelefono();
            $this->enviarSMS($entity, $telefono);
        }

        $this->em->flush();
    }

    public function cambiarEstadoPreInscriptoBatchAction(array $ids)
    {
        $class = $this->entity['class'];
        $em = $this->getDoctrine()->getManagerForClass($class);

        foreach ($ids as $id) {
            $entity = $this->em->getRepository(Inscripcion::class)->find($id);
            $entity->setEstado("Pre-inscripto");
        }

        $this->em->flush();
    }

    public function cambiarEstadoInscriptoBatchAction(array $ids)
    {
        $class = $this->entity['class'];
        $em = $this->getDoctrine()->getManagerForClass($class);

        foreach ($ids as $id) {
            $entity = $this->em->getRepository(Inscripcion::class)->find($id);
            $entity->setEstado("Inscripto");
        }

        $this->em->flush();
    }

    public function cambiarEstadoAprobadoBatchAction(array $ids)
    {
        $class = $this->entity['class'];
        $em = $this->getDoctrine()->getManagerForClass($class);

        foreach ($ids as $id) {
            $entity = $this->em->getRepository(Inscripcion::class)->find($id);
            $entity->setEstado("Aprobado");
        }

        $this->em->flush();
    }

    public function cambiarEstadodesaprobadoBatchAction(array $ids)
    {
        $class = $this->entity['class'];
        $em = $this->getDoctrine()->getManagerForClass($class);

        foreach ($ids as $id) {
            $entity = $this->em->getRepository(Inscripcion::class)->find($id);
            $entity->setEstado("Desaprobado");
        }

        $this->em->flush();
    }

    function enviarSMS($entity, $telefono)
    {
        try {
            $smsc = new \Smsc('fdserafini', '15261_3468943e7bf6983f7b019259d80242e3');

            // Estado del servicio
            //echo 'El estado del servicio es '.($smsc->getEstado()?'OK':'CAIDO').'. ';
            $this->addFlash('success', 'El estado del servicio es ' . ($smsc->getEstado() ? 'OK' : 'CAIDO') . '. ');
            // Saldo
            //echo 'Quedan: '.$smsc->getSaldo().' sms. ';
            $this->addFlash('success', 'Quedan: ' . $smsc->getSaldo() . ' sms. ');

            // Enviar SMS
            $smsc->addNumero($telefono);
            $smsc->setPrioridad(7);
            $smsc->setMensaje('Usted se encuentra ' . $entity->getEstado() . ' al curso ' . $entity->getCurso());
            if ($smsc->enviar()) {
                $this->addFlash('success', 'Mensaje enviado.');
                $notificacion = new Notificacion();
                $notificacion->setFecha(new DateTime('now'));
                $notificacion->setEstado($entity->getEstado());
                $entity->addNotificacion($notificacion);
                $this->em->flush();
            }
            //echo 'Mensaje enviado.';
        } catch (Exception $e) {
            //echo 'Error '.$e->getCode().': '.$e->getMessage();
            $this->addFlash('warning', 'Error ' . $e->getCode() . ': ' . $e->getMessage());
        }
    }

    /**
     * @Route(path = "/admin/importar/inscriptos", name="importar_inscriptos")
     */
    public function importarInscriptosAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('congreso', EntityType::class, array(
                'label' => 'Congreso',
                'class' => Congreso::class,
                'choice_label' => 'nombre',
                'expanded' => false,
                'attr' => array(
                    'class' => 'form-control'
                ),
                'placeholder' => 'Seleccione Congreso',

            ))
            ->add('condicion', ChoiceType::class, [
                'label' => 'Condicion',
                'required' => true,
                'choices' => [
                    'Asistente'=>'Asistente', 
                    'Colaborador'=>'Colaborador', 
                    'Participante'=>'Participante', 
                    'Disertante'=>'Disertante', 
                    'Organizador'=>'Organizador', 
                    'Stand'=>'Stand', 
                    'Prensa'=>'Prensa'
                ],
                'attr' => array(
                    'class' => 'form-control'
                ),
            ])
            ->add('archivo', VichFileType::class, [])
            ->add('nombre', TextType::class, [
                'label' => 'Columna nombre',
                'attr' => array(
                    'class' => 'form-control'
                ),
            ])
            ->add('apellido', TextType::class, [
                'label' => 'Columna apellido',
                'attr' => array(
                    'class' => 'form-control'
                ),
            ])
            ->add('dni', TextType::class, [
                'label' => 'Columna dni',
                'attr' => array(
                    'class' => 'form-control'
                ),
            ])
            ->add('email', TextType::class, [
                'label' => 'Columna email',
                'attr' => array(
                    'class' => 'form-control'
                ),
            ])
            ->add('asistencia', TextType::class, [
                'label' => 'Columna asistencia',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control'                    
                ),
            ])
            ->add('adquisicion', TextType::class, [
                'label' => 'Columna lugar de adquisicion',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'required' => false
                ),
            ])
            ->add('codCupon', TextType::class, [
                'label' => 'Columna Código de cupon',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control',
                    'required' => false
                ),
            ])
            ->add('importar', SubmitType::class, [
                'label' => 'Importar',
                'attr' => array(
                    'class' => 'btn btn-primary'
                )
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            if (move_uploaded_file($task["archivo"], "uploads/excel.xls")) {
                echo "El archivo ha sido cargado correctamente.";
            } else {
                echo "Ocurrio algun error al subir el fichero. No pudo guardarse.";
            }
            $entityManager = $this->getDoctrine()->getManager();

            $archivo = "uploads/excel.xls";
            $inputFileType = \PHPExcel_IOFactory::load($archivo);
            $sheetData = $inputFileType->getActiveSheet()->toArray(NULL, FALSE, FALSE, TRUE);

            foreach ($sheetData as $row) {
                $find = $entityManager->getRepository(Persona::class)->findOneByDni($row[$form->getData()["dni"]]);
                if ($find) {
                    $find->setApellido($row[$form->getData()["apellido"]]);
                    $find->setDni($row[$form->getData()["dni"]]);
                    $find->setEmail($row[$form->getData()["email"]]);
                    $inscripto = $find;
                    $entityManager->flush();
                } else {
                    $inscripto = new Persona();
                    $inscripto->setNombre($row[$form->getData()["nombre"]]);
                    $inscripto->setApellido($row[$form->getData()["apellido"]]);
                    $inscripto->setDni($row[$form->getData()["dni"]]);
                    $inscripto->setEmail($row[$form->getData()["email"]]);
                    $entityManager->persist($inscripto);
                }
                $findInscripcion = $entityManager->getRepository(InscripcionCongreso::class)->findOneBy(['persona' => $inscripto, 
                'congreso' => $form->getData()["congreso"], 
                'condicion' => $form->getData()["condicion"]]);
                if ($findInscripcion){
                    $inscripcion = $findInscripcion;
                } else {
                    $inscripcion = new InscripcionCongreso();
                }                
                $inscripcion->setCongreso($form->getData()["congreso"]);
                $inscripcion->setPersona($inscripto);
                if ($form->getData()["asistencia"] != null){
                    $inscripcion->setPorcAsistencia($row[$form->getData()["asistencia"]]);
                }
                if ($form->getData()["adquisicion"] != null){
                    $inscripcion->setLugarAdquisicion($row[$form->getData()["adquisicion"]]);
                }
                if ($form->getData()["codCupon"] != null){
                    $inscripcion->setCodigoCupon($row[$form->getData()["codCupon"]]);
                }
                if ($form->getData()["condicion"] != null){
                    $inscripcion->setCondicion($form->getData()["condicion"]);
                }
                
                $entityManager->persist($inscripcion);
                $date = new \DateTime("now");
                $uInscripcion = $inscripcion->getCongreso()->getId() . "" . $inscripcion->getId();

                $codigo = $date->format('y') . "-"
                    . strtoupper(dechex((int) $uInscripcion)) . "-"
                    . strtoupper(dechex($date->format('mdhiu')));

                $inscripcion->setcodigo($codigo);
            }
            
            $entityManager->flush();
            return $this->redirectToRoute('easyadmin', array(
                'action' => 'list',
                'entity' => 'InscripcionCongreso',
            ));
        }
        return $this->render('backend/importar.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(path = "/admin/acreditar", name="acreditar")
     */
    public function acreditarAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Congreso::class);

        $id = $request->query->get('id');
        $entity = $repository->find($id);
        foreach ($entity->getFechas() as $key => $value) {
            $arrayFechas[$value['fecha']->format('d-m-y')] = $value['fecha']->format('Y-m-d');
        }
        $form = $this->createFormBuilder()
            ->add('fecha', ChoiceType::class, array(
                'choices' => $arrayFechas,
                'attr' => [
                    'class' => 'form-control col-4'
                ]
            ))
            ->add('acreditar', SubmitType::class, [
                'label' => 'Acreditar',
                'attr' => array(
                    'class' => 'btn btn-primary'
                )
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->render('backend/acreditar.html.twig', [
                'congreso' => $entity,
                'fecha' => $date = new \DateTime($form->getData()["fecha"])
            ]);
        }
        return $this->render('backend/selectDate.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/acreditarAjax", name="ajax_acreditar")
     */
    public function acreditarAjax(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $fecha = new \DateTime($request->get('fecha'));
        $entity = $request->get('id');
        $inscripcion = $em->getRepository(InscripcionCongreso::class)->find($entity);
        if ($request->get('action') == "add") {
            $acreditacion = new Acreditacion();
            $acreditacion->setInscripcion($inscripcion);
            $acreditacion->setFecha($fecha);
            $em->persist($acreditacion);
        }
        if ($request->get('action') == "remove") {
            $acreditacion = $em->getRepository(Acreditacion::class)->findOneBy(['inscripcion' => $inscripcion, 'fecha' => $fecha]);
            $em->remove($acreditacion);
            $em->flush();
        }
        $em->flush();
        return new JsonResponse("ok");
    }

    /**
     * @Route(path = "/admin/estadisticasCongreso", name="estadisticas_congreso")
     */
    public function estadisticasCongresoAction(Request $request)
    {
        $this->denyAccessUnlessGranted(['ROLE_SUPER_ADMIN', 'ROLE_ADMIN_CURSO']);
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Congreso::class);

        $id = $request->query->get('id');
        $entity = $repository->find($id);
        foreach ($entity->getFechas() as $key => $value) {
            $arrayFechas[$value['fecha']->format('d-m-y')] = $value['fecha']->format('Y-m-d');
        }

        return $this->render('backend/estadisticasCongreso.html.twig', [
            'congreso' => $entity,
        ]);
    }

    /**
     * @Route("/get-acreditados-congreso", name="get_acreditados_congreso")
     */
    public function getLocalidad(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $idCongreso = $request->get('idCongreso');
        $congreso = $em->getRepository(Congreso::class)->findOneBy(["id" => $idCongreso]);
        return new JsonResponse($congreso->getId());
    }

    /**
     * @Route(path = "/admin/estadisticasCongresos", name="estadisticas_congresos")
     */
    public function estadisticasCongresosAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('fechaDesde', DateType::class, [
                'label' => 'Desde',
                'required' => false,
                'widget' => 'choice',
                'input'  => 'datetime',                
                'placeholder' => [
                    'year' => 'Año', 'month' => 'Mes', 'day' => 'Dia',
                ],
                'data' => new \DateTime("01-01-2014"),
                'format' => 'dd-MM-yyyy',
                'years' => range(date('Y') - 100, date('Y')),
            ])
            ->add('fechaHasta', DateType::class, [
                'label' => 'Hasta',
                'required' => false,
                'widget' => 'choice',
                'input'  => 'datetime',                
                'placeholder' => [
                    'year' => 'Año', 'month' => 'Mes', 'day' => 'Dia',
                ],
                'data' => new \DateTime("now"),
                'format' => 'dd-MM-yyyy',
                'years' => range(date('Y') - 100, date('Y')),
            ])
            ->getForm();
        $form->handleRequest($request);

        $anios = array();
        $congresos = array();
        $claves = array();

        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Congreso::class);
        $entities = $repository->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($entities as $entity) {
                $anio = $entity->getFechas()[0]["fecha"]->format('Y');
                if ($anio >= $form->getData()['fechaDesde']->format('Y') && $anio <= $form->getData()['fechaHasta']->format('Y')){
                    $claves[$entity->getClave()][] = $entity;
                    array_push($anios, $anio);
                }                
            }    
        } else {
            foreach ($entities as $entity) {
                $anio = $entity->getFechas()[0]["fecha"]->format('Y');
                $claves[$entity->getClave()][] = $entity;
                array_push($anios, $anio);
            }
        }  

        $anios = array_unique($anios);        
        sort($anios);
        
        foreach ($claves as $key => $clave) {
            $data = array();
            $dataAcreditados = array();
            foreach ($clave as $clav) {
                foreach ($anios as $keyanio => $anio) {
                    if ($clav->getFechas()[0]["fecha"]->format('Y') == $anio) {                        
                        $acreditados = 0;
                        foreach ($clav->getInscripciones() as $inscripcion) {
                            if (count($inscripcion->getAcreditacions()) > 0) {
                                $acreditados++;    
                            }
                        }
                        $dataAcreditados[] = $acreditados;
                        $data[] = count($clav->getInscripciones()) - $acreditados;
                    }
                }
            }            
            $congresos[] = array("name" => $key . " - NO ACREDITADOS", "data" => $data, "stack" => $key);
            $congresos[] = array("name" => $key . " - ACREDITADOS", "data" => $dataAcreditados, "stack" => $key);            
        }

        //dump(json_encode($congresos));die();
        return $this->render('backend/estadisticasCongresos.html.twig', [
            'congresos' => $congresos,
            'anios' => $anios, 
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(path = "/admin/estadisticasCursos", name="estadisticas_cursos")
     */
    public function estadisticasCursosAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('fechaDesde', DateType::class, [
                'label' => 'Desde',
                'required' => false,
                'widget' => 'choice',
                'input'  => 'datetime',                
                'placeholder' => [
                    'year' => 'Año', 'month' => 'Mes', 'day' => 'Dia',
                ],
                'data' => new \DateTime("01-01-2014"),
                'format' => 'dd-MM-yyyy',
                'years' => range(date('Y') - 100, date('Y')),
            ])
            ->add('fechaHasta', DateType::class, [
                'label' => 'Hasta',
                'required' => false,
                'widget' => 'choice',
                'input'  => 'datetime',                
                'placeholder' => [
                    'year' => 'Año', 'month' => 'Mes', 'day' => 'Dia',
                ],
                'data' => new \DateTime("now"),
                'format' => 'dd-MM-yyyy',
                'years' => range(date('Y') - 100, date('Y')),
            ])
            ->add('estado', ChoiceType::class, [              
                'label' => 'Estado',
                'attr' => ['class' => 'form-control'],                
                'choices'  => [
                    'Todos' => 'Todos',
                    'Pre-Inscripto' => 'Pre-Inscripto',
                    'Inscripto' => 'Inscripto',
                    'Aprobado' => 'Aprobado',
                    'Desaprobado' => 'Desaprobado',                    
                ]    
            ])
            ->getForm();
        $form->handleRequest($request);

        $estado = "Inscipciones";
        $anios = array();
        $cursos = array();
        $claves = array();

        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Curso::class);        
        $repositoryInscripciones = $this->getDoctrine()->getRepository(Inscripcion::class);
        
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()['estado'] != "Todos"){
                $estado = $form->getData()['estado'];
            }            
            $entities = $repository->findAll();    
            foreach ($entities as $entity) {
                $anio = $entity->getfechaInicio()->format('Y');
                if ($anio >= $form->getData()['fechaDesde']->format('Y') && $anio <= $form->getData()['fechaHasta']->format('Y')){
                    $claves[$entity->getClave()][] = $entity;
                    array_push($anios, $anio);
                }                
            }    
        } else {
            $entities = $repository->findAll();
            foreach ($entities as $entity) {
                $anio = $entity->getfechaInicio()->format('Y');
                $claves[$entity->getClave()][] = $entity;
                array_push($anios, $anio);
            }
        }  

        array_unique($anios);
        sort($anios);

        foreach ($claves as $key => $clave) {
            $data = array();
            foreach ($clave as $clav) {
                foreach ($anios as $keyanio => $anio) {
                    if ($clav->getfechaInicio()->format('Y') == $anio) {
                        $inscripciones = $repositoryInscripciones->findByCursoEstadoInscripto($clav->getId(), $form->getData()['estado']);
                        $data[$anio] = count($inscripciones);
                    }
                }
            }
            sort($data);
            $cursos[] = array("name" => $key, "data" => $data);
        }

        return $this->render('backend/estadisticasCursos.html.twig', [
            'cursos' => $cursos,
            'anios' => $anios, 
            'form' => $form->createView(),
            'estado' => $estado
        ]);
    }

    public function exportAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_SEARCH);
        $searchableFields = $this->entity['search']['fields'];
        $paginator = $this->findBy($this->entity['class'],
            $this->request->query->get('query'), $searchableFields, 1, 10000000000,
            $this->request->query->get('sortField'),
            $this->request->query->get('sortDirection'),
            $this->entity['search']['dql_filter']);        
            $fields = $this->entity['list']['fields'];                 
        if (array_key_exists('estado', $fields)){
            $fields["estado"]["template"] = "@EasyAdmin/default/field_text.html.twig";
        }
        $this->dispatch(EasyAdminEvents::POST_SEARCH,
            array(
            'fields' => $fields,
            'paginator' => $paginator,
        ));
        return $this->getExportFile($paginator, $fields);
    }

    public function getExportFile($paginator, $fields)
    {
        $handle = fopen('php://memory', 'r+');

        // first legend line
        $keys = array_keys($fields);
        for($i=0, $count=count($keys) ; $i<$count ; $i++){
            $keys[$i] = $fields[$keys[$i]]['label'] ?? $keys[$i];
        }
        fputcsv($handle, $keys, ';', '"');

        $twig = $this->get('twig');
        $ea_twig = $twig->getExtension(EasyAdminTwigExtension::class);

        foreach ($paginator as $entity) {            
            $row = [];
            foreach ($fields as $field) {
                $value = strip_tags($ea_twig->renderEntityField($twig, 'list', $this->entity['name'], $entity, $field));
                $row[] = trim($value);
            }
            
            fputcsv($handle, $row, ';', '"');
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return new Response("\xEF\xBB\xBF".$content, 200, array(
            'Content-Type' => 'application/force-download',
            'Content-Disposition' => 'attachment; filename="' . sprintf('export-%s-%s.csv', strtolower($this->entity['name']), date('Ymd_His')) . '"'
        ));
    }
}
