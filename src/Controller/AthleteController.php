<?php

//dónde se encuentra
namespace App\Controller;


use App\Entity\Athlete;
use App\Entity\TitlesWon;
use App\Form\AthleteType;
use App\Form\SearcherType;
use App\Manager\AthleteManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AthleteController extends AbstractController {


    //Creando la ruta de la url con id variable
    #[Route('/athlete/{id}', name: 'showAthlete')]
    //Creando la función para mostrar a cada atleta
    public function showAthlete(EntityManagerInterface $doctrine, $id) {

        //Creando el array del modelo de cada atleta con su clave => valor
        $repository = $doctrine -> getRepository(Athlete::class);
        $athlete = $repository->find($id);

        if(!$athlete) {
            throw $this->createNotFoundException(
                "No se ha encontrado ningún atleta con el id indicado: nº $id"
            );
        }

        //Devolviendo con el método render al atleta para que lo muestre en pantalla
        return $this->render('athletes/showAthlete.html.twig', ['athlete'=>$athlete]);

    }

    
    #[Route('/athletes', name: 'listAthlete')]
    public function listAthlete(EntityManagerInterface $doctrine, Request $request){
        $form = $this -> createForm(SearcherType::class);
        $form -> handleRequest($request);
        if($form -> isSubmitted() && $form -> isValid()) {
            //Creando el search
            $athlete = $form -> get('atleta') -> getData();
            return $this -> redirectToRoute('showAthlete', ['id' => $athlete -> getId()]);
        }

        //Athlete::class ---> listado de atletas que tienen que cargarse
        $repository = $doctrine -> getRepository(Athlete::class);
        //Para encontrar a todos los atletas
        $athletes = $repository->findAll();

    
        return $this->render('athletes/listAthlete.html.twig', ['athletes' => $athletes, 'searchForm' => $form]);

    }

    #[Route('/new/athlete')]
    public function newAthlete(EntityManagerInterface $doctrine){

        //Creando objetos del tipo athlete
        $athlete1 = new Athlete();
        $athlete1-> setCode(15);
        $athlete1-> setName('');
        $athlete1-> setSport('');
        $athlete1-> setDescription('');
        $athlete1-> setImg('');
        $athlete1-> setBirthDate('');
    
        //Creando objetos del tipo titleWon
        $titleWon1 = new TitlesWon();
        $titleWon1 -> setTitles('');
        
        //Relacion atletas y titulos
        $athlete1 -> addTitlesName($titleWon1);

        //Avisando para que añada estos datos a la DB
        $doctrine->persist($athlete1);
        $doctrine->persist($titleWon1);
   

        //Insertando los datos en la DB
        $doctrine->flush();

        return new Response("Atletas y títulos insertados correctamente");
    }

    #[Route('/insert/athlete', name: 'insertAthlete')]
    //Request es el componente de symfony para manejar peticiones
    public function insertAthlete(Request $request, EntityManagerInterface $doctrine, AthleteManager $manager) {
        //Seleccionando form --> con el que vamos a trabajar 
        $form = $this-> createForm(AthleteType::class);
        //Viendo si el usuario ha rellenado los datos y le ha dado a enviar
        $form -> handleRequest($request);
        if($form -> isSubmitted() && $form -> isValid()) {
            //Devolviendo un objeto del tipo atleta con los datos rellenos por el user
            $athlete = $form -> getData();
            $athleteImg = $form -> get('imagenAtleta') -> getData();
            if($athleteImg) {
            //Guardando la respuesta del método load
                $athleImage = $manager -> load($athleteImg, $this -> getParameter('kernel.project_dir').'/public/assets/image');
                $athlete -> setImg("/assets/image/$athleImage");
            }
            $doctrine -> persist($athlete);
            $doctrine -> flush();
            //Añadiendo mensaje
            $this -> addFlash('success', 'Atleta insertado correctamente');
        
            return $this -> redirectToRoute('listAthlete');
        }

        //Haciendo el "renderForm"
        return $this-> render('athletes/createAthlete.html.twig', ['athleteForm' => $form -> createView()]);
    }

    #[Route('/edit/athlete/{id}', name: 'editAthlete')]
    public function editAthlete(Request $request, EntityManagerInterface $doctrine, $id, AthleteManager $manager) {
        $repository = $doctrine -> getRepository(Athlete::class);
        $athlete = $repository -> find($id);
        $form = $this-> createForm(AthleteType::class, $athlete);
        $form -> handleRequest($request);
        if($form -> isSubmitted() && $form -> isValid()) {
            $athlete = $form -> getData();
            $athleteImg = $form -> get('imagenAtleta') -> getData();
            if($athleteImg) {
                $athleImage = $manager -> load($athleteImg, $this -> getParameter('kernel.project_dir').'/public/assets/image');
                $athlete -> setImg("/assets/image/$athleImage");
            }
            $doctrine -> persist($athlete);
            $doctrine -> flush();
            $this -> addFlash('success', 'Atleta editado correctamente');

            return $this -> redirectToRoute('listAthlete');
        }
        return $this-> render('athletes/createAthlete.html.twig', ['athleteForm' => $form -> createView()]);
    }

    #[IsGranted('ROLE_ADMIN')] //solo la gente con role_admin va a poder borrar
    #[Route('/remove/athlete/{id}', name: 'removeAthlete')]
    public function removeAthlete(EntityManagerInterface $doctrine, $id) {
        //Haciendo consulta a DB para saber el atleta que queremos borrar
        $repository = $doctrine -> getRepository(Athlete::class);
        $athlete = $repository->find($id);
        $doctrine -> remove($athlete);
        $doctrine -> flush();
        $this -> addFlash('success', 'Atleta borrado correctamente');
        if(!$athlete) {
            throw $this->createNotFoundException(
                "No se ha encontrado ningún atleta con el id indicado: nº $id"
            );
        }
        return $this->redirectToRoute('listAthlete');

    }
}

?>