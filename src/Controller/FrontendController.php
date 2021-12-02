<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Entity\Curso;
use App\Entity\Alumno;
use App\Entity\Persona;
use App\Form\InscripcionType;
use App\Entity\Inscripcion;
use App\Entity\InscripcionCongreso;
use App\Entity\InscriptoCongreso;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Localidad;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use App\Entity\Config;
use App\Entity\Menu;

class FrontendController extends Controller {

    /**
     * @Route("/prueba-sms", name="prueba-sms")
     */
    public function pruebaSms() {
        try {
	$smsc = new \Smsc('fdserafini', '15261_3468943e7bf6983f7b019259d80242e3');

	// Estado del servicio
	echo 'El estado del servicio es '.($smsc->getEstado()?'OK':'CAIDO').'. ';

	// Saldo
	echo 'Quedan: '.$smsc->getSaldo().' sms. ';
	
	// Enviar SMS
	$smsc->addNumero(362,4525633);
        $smsc->setPrioridad(7);
	$smsc->setMensaje('pague 7 pesos');
	if ($smsc->enviar())
		echo 'Mensaje enviado.';
        } catch (Exception $e) {
                echo 'Error '.$e->getCode().': '.$e->getMessage();
        }        
    }
    
    /**
     * @Route("/", name="cursos")
     */
    public function cursos() {    
        $cursos = $this->getDoctrine()
                ->getRepository(Curso::class)
                ->findBy(["publicado" => true]);
        
        return $this->render('frontend/index.html.twig', array(                    
                    "cursos"    => $cursos
        ));    
    }

    /**
     * @Route("/materiales", name="materiales")
     */
    public function materiales() {    
        $cursos = $this->getDoctrine()
                ->getRepository(Curso::class)
                ->findBy(["publicado" => true]);
        
        return $this->render('frontend/materiales.html.twig', array(                    
                    "cursos"    => $cursos
        ));    
    }

    /**
     * @Route("/curso/{idCurso}/detalles", name="detalles_curso")
     */
    public function detalles(Request $request, $idCurso) {    
        $curso = $this->getDoctrine()
                ->getRepository(Curso::class)
                ->find($idCurso);        
        
        return $this->render('frontend/detalleCurso.html.twig', array(                    
                    "curso" => $curso,                    
        ));    
    }

    /**
     * @Route("/inscripcion/{idCurso}", name="inscripcion")
     */
    public function inscripcion(Request $request, $idCurso) {    
        $curso = $this->getDoctrine()
                ->getRepository(Curso::class)
                ->find($idCurso);
        $persona = new Persona();
        $form = $this->createForm(InscripcionType::class, $persona);
        $form->handleRequest($request);        
        if ($form->isSubmitted() && $form->isValid() && $this->captchaverify($request->get('g-recaptcha-response'))) {            
            $persona = $form->getData();
            $personaEntity = $this->getDoctrine()
            ->getRepository(Persona::class)
            ->findOneByDni($persona->getDni());
            $entityManager = $this->getDoctrine()->getManager();
            if ($personaEntity){
                $personaEntity->setEmail($persona->getEmail());
                $personaEntity->setTelefono($persona->getTelefono());
                $personaEntity->setTelefonoFijo($persona->getTelefonoFijo());
                $personaEntity->setDireccion($persona->getDireccion());
                $personaEntity->setLocalidad($persona->getLocalidad());
                $alumno = $personaEntity->getAlumno();
                $alumno->setOcupacion($persona->getAlumno()->getOcupacion());
                $alumno->setNivelEducativo($persona->getAlumno()->getNivelEducativo());
                $alumno->setDiscapacidad($persona->getAlumno()->getDiscapacidad());
                $alumno->setTipoDiscapacidad($persona->getAlumno()->getTipoDiscapacidad());
                $alumno->setCud($persona->getAlumno()->getCud());
                $alumno->setVencCud($persona->getAlumno()->getVencCud());
                $alumno->setApoyosTecnicos($persona->getAlumno()->getApoyosTecnicos());
                $alumno->setMedioInfo($persona->getAlumno()->getMedioInfo());
                $alumno->setTemasInteres($persona->getAlumno()->getTemasInteres());                
                $entityManager->flush();
                $persona = $personaEntity;                
            } else {
                $entityManager->persist($persona);
            }    
                        
            $inscripcion = new Inscripcion();
            $inscripcion->setAlumno($persona->getAlumno());
            $inscripcion->setCurso($curso);
            $inscripcion->setEstado("Pre-inscripto");
            $inscripcion->setPorcAsistencia(0);
            
            $entityManager->persist($inscripcion);
            $date = new \DateTime("now");
            $uInscripcion = $curso->getId()."".$inscripcion->getId();
                  
            $codigo = $date->format('y') . "-"             
            . strtoupper(dechex((int)$uInscripcion)). "-"  
            . strtoupper(dechex($date->format('mdhiv')));
        
            $inscripcion->setcodigo($codigo);
            $entityManager->flush();                       
            $this->addFlash('success', 'Usted ha sido pre-inscripto al curso '.$curso->getNombre().' correctamente!');
            return $this->redirectToRoute('detalles_curso', ['idCurso' => $curso->getId()]);
        } else if($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('error', 'Existen errores en el formulario');
        }

        /*if($form->isSubmitted() &&  $form->isValid() && !$this->captchaverify($request->get('g-recaptcha-response'))){
                 
            $this->addFlash(
                'error',
                'El captcha es requerido'
              );             
            }*/
        return $this->render('frontend/inscripcion.html.twig', array(                    
                    "curso" => $curso,
                    "form"  => $form->createView(),
        ));    
    }

    public function menuAction() {
        $em = $this->getDoctrine()->getManager();
        $menues = $em->getRepository(Menu::class)->findBy(['activo' => true], ['orden' => 'ASC']);
        return $this->render('frontend/menu.html.twig', [
                    'menues' => $menues,
        ]);
    }

    public function footerAction() {
        $em = $this->getDoctrine()->getManager();
        $config = $em->getRepository(Config::class)->findAll();
        $footer = $config[0]->getFooter();
        return $this->render('frontend/footer.html.twig', [
                    'footer' => $footer,
        ]);
    }

    /**
     * @Route("/consultar-cud/{cud}", name="cud")
     */
    public function consultarCud(Request $request, $cud) {
        if ($request->isXmlHttpRequest()) {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', 'https://registrodeprueba.iprodich.chaco.gov.ar/oauth/v2/token?client_id=2_1nu1vd0dzrdwwwk0c0840c4oks08c0ww004wck40w8sgcw4gsw&client_secret=juxtvoqx5mo4sksc0gwog0kwwscc04048ksk8w0c04oscwckg&grant_type=client_credentials');
        $token = $response->toArray()["access_token"];
        $responseCud = $httpClient->request('GET', 'https://registrodeprueba.iprodich.chaco.gov.ar/api/v1/registro-by-cud/'.$cud,
            [
                'headers' => [
                    'Authorization' => 'Bearer '.$token,
                ],
            ]    
        );        
        if($responseCud->getContent() != ""){
            return new JsonResponse(array('data' => $responseCud->toArray()));
        } else {
            $response = new Response();
            $response->setStatusCode(410);
            return $response;
        }    
    }
    }

    /**
     * @Route("/get-localidad", name="get_localidad")
     */
    public function getLocalidad(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $idLocalidad = $request->get('idLocalidad');        
        $localidad = $em->getRepository(Localidad::class)->findOneBy(["idrpcd" => $idLocalidad]);
        return new JsonResponse($localidad->getId());
    }

    /**    
     *
     * @Route("/imprimir/certificado", name="certificado_congreso_imprimir")
     */
    public function imprimirCertificadoAction(Request $request)
    {
        $id = $request->query->get('id');
        $inscripcion = $this->getDoctrine()
                ->getRepository(InscripcionCongreso::class)
                ->find($id);

        if (!$inscripcion) {
            throw $this->createNotFoundException('No se encontró una inscripción válida');
        }
        
        $qr = $inscripcion->getCongreso()->getPlantilla()->getQr();

        $html = $this->renderView('frontend/pdfCongreso.html.twig', array(
          'inscripcion' => $inscripcion,
          'qr' => $qr
        ));
        $path = __DIR__.'/../../../../web/bundles/mwsimpleadmincrud/css/bootstrap.min.css';
        /*    return $this->render('SistemaAdminBundle:recibo:pdf.html.twig', array(
          'entity'      => $this->entity,
          )); */
        return new Response(
                $this->get('knp_snappy.pdf')->getOutputFromHtml($html, array(
                    'lowquality' => false,
                    'title' => 'Certificado',
                    'orientation'=>'Landscape',
                    'margin-top'    => 0,
                    'margin-right'  => 0,
                    'margin-bottom' => 0,
                    'margin-left'   => 0,
                    //'dpi' => 300,
                    'images' => true,
                    'no-background' => false,
                    //'image-dpi' => 300,
                    'page-size'=> 'A4',
                    //'no-pdf-compression'=> true
                        //'user-style-sheet'=> $path//'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css'//$request->getUriForPath($this->get('assets.packages')->getUrl('bundles/mwsimpleadmincrud/css/bootstrap.min.css')),
                )),
            200,
            array(
            'Content-Type' => 'application/pdf',            
                )
        );
    }

    /**    
     *
     * @Route("/admin/imprimir/certificado", name="admin_certificado_imprimir")
     */
    public function imprimirCertificadoAdminAction(Request $request)
    {
        $this->denyAccessUnlessGranted(['ROLE_SUPER_ADMIN']);
        $id = $request->query->get('id');
        $inscripcion = $this->getDoctrine()
                ->getRepository(Inscripcion::class)
                ->find($id);
        $qr = $inscripcion->getCurso()->getPlantilla()->getQr();
        $html = $this->renderView('frontend/pdf.html.twig', array(
            'inscripcion' => $inscripcion,
            "qr" => $qr
        ));
          $path = __DIR__.'/../../../../web/bundles/mwsimpleadmincrud/css/bootstrap.min.css';
          return new Response(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html, array(
                'lowquality' => false,
                'title' => 'Certificado',
                'orientation'=>'Landscape',
                'margin-top'    => 0,
                'margin-right'  => 0,
                'margin-bottom' => 0,
                'margin-left'   => 0,
                //'dpi' => 300,
                'images' => true,
                'no-background' => false,
                //'image-dpi' => 300,
                'page-size'=> 'A4',
                //'no-pdf-compression'=> true
                    //'user-style-sheet'=> $path//'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css'//$request->getUriForPath($this->get('assets.packages')->getUrl('bundles/mwsimpleadmincrud/css/bootstrap.min.css')),
            )),
        200,
        array(
        'Content-Type' => 'application/pdf',            
            )
    );
    }

    /**    
     *
     * @Route("/admin/imprimir/certificadoCongreso", name="admin_certificado_congreso_imprimir")
     */
    public function imprimirCertificadoCongresoAdminAction(Request $request)
    {
        $this->denyAccessUnlessGranted(['ROLE_SUPER_ADMIN']);
        $id = $request->query->get('id');
        $inscripcion = $this->getDoctrine()
                ->getRepository(InscripcionCongreso::class)
                ->find($id);
        $qr = $inscripcion->getCongreso()->getPlantilla()->getQr();
          return $this->render('frontend/pdfCongreso.html.twig', array(                    
            "inscripcion"    => $inscripcion,
            "qr" => $qr
        ));           
    }

    /**
     * @Route("/certificados", name="certificados")
     */
    public function certificados(Request $request) {
        $certificados = null;
        $certificadosCongreso = null;
        $porCodigoCurso = null;
        $porCodigoCongreso= null;
        $form = $this->createFormBuilder()
        ->add('dni', null, [            
            'label' => 'Documento Nacional de Identidad (DNI)',            
            'required' => false,
            'data' => $request->query->get('dni'),
            'attr' => array(
                'onkeypress' => "return isNumberKey(event)",
                //'pattern' => "[0-9]",    
                'class' => 'form-control',
                'placeholder' => 'Ej.: 36129087',
                'title' => "dni",
                'tabindex' => "16",
                'aria-describedby' =>"tip1",
                'aria-required' => false,                    
                'data-toggle' => "tooltip", 
                'data-placement' => "top", 
                'title' => "Ingrese su número de documento nacional de identidad"
            )
        ])
        ->add('codigo', null, [
            'label' => 'Código',
            'required' => false,
            'attr' => array(
                'class' => 'form-control',
                'placeholder' => 'Ej.: 19-2-2276632E8',
                'title' => "Código",
                'tabindex' => "16",
                'aria-required' => true,
                'data-toggle' => "tooltip", 
                'data-placement' => "top", 
                'title' => "Ingrese código del certificado"
            )
        ])
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {            
            $dni = $form->getData()["dni"];
            $codigo = $form->getData()["codigo"];           
            $persona = $this->getDoctrine()
                ->getRepository(Persona::class)
                ->findOneBy(["dni" => $dni]);
                if($persona){
                    if($persona->getAlumno()){
                        $certificados = $persona->getAlumno()->getInscripciones();
                    }
                    if($persona->getInscripcionesCongreso()){
                        $certificadosCongreso = $persona->getInscripcionesCongreso();    
                    }                    
                }
                
                $porCodigoCongreso = $this->getDoctrine()
                ->getRepository(InscripcionCongreso::class)
                ->findOneBy(["codigo" => $codigo]);
               
                $porCodigoCurso = $this->getDoctrine()
                ->getRepository(Inscripcion::class)
                ->findOneBy(["codigo" => $codigo]);
                                  
                return $this->render('frontend/certificados.html.twig', [
                    'submitted' => true,
                    'form' => $form->createView(),
                    'certificados' => $certificados,
                    'certificadosCongreso' => $certificadosCongreso,
                    'codigoCongreso' => $porCodigoCongreso,
                    'codigoCurso' => $porCodigoCurso,
                ]);
        }
        return $this->render('frontend/certificados.html.twig', [            
            'form' => $form->createView(),
            'certificados' => $certificados,
            'certificadosCongreso' => $certificadosCongreso,
            'codigoCongreso' => $porCodigoCongreso,
            'codigoCurso' => $porCodigoCurso,
            'submitted' => false,
        ]);
    }

    function captchaverify($recaptcha)
    {
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            "secret" => "6Lc5MrsUAAAAAMcvGhl0vIPG74xNkCz0nTUv6lhU", "response" => $recaptcha
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response);
        return $data->success;
    }
}
