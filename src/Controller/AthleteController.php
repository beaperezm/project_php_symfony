<?php

//Where it´s located
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


    //Creating URL path with variable id
    #[Route('/athlete/{id}', name: 'showAthlete')]
    //Creando la función para mostrar a cada atleta
    public function showAthlete(EntityManagerInterface $doctrine, $id) {

        //Creating the array of each athlete´s model with its key => value
        $repository = $doctrine -> getRepository(Athlete::class);
        $athlete = $repository->find($id);

        if(!$athlete) {
            throw $this->createNotFoundException(
                "No se ha encontrado ningún atleta con el id indicado: nº $id"
            );
        }

        //Returning with the render method to the athlete to be displayed on the screen
        return $this->render('athletes/showAthlete.html.twig', ['athlete'=>$athlete]);

    }

    
    #[Route('/athletes', name: 'listAthlete')]
    public function listAthlete(EntityManagerInterface $doctrine, Request $request){
        $form = $this -> createForm(SearcherType::class);
        $form -> handleRequest($request);
        if($form -> isSubmitted() && $form -> isValid()) {
            //Creating the search input
            $athlete = $form -> get('atleta') -> getData();
            return $this -> redirectToRoute('showAthlete', ['id' => $athlete -> getId()]);
        }

        //Athlete::class ---> list of athletes to be charged
        $repository = $doctrine -> getRepository(Athlete::class);
        //To find all athletes
        $athletes = $repository->findAll();

    
        return $this->render('athletes/listAthlete.html.twig', ['athletes' => $athletes, 'searchForm' => $form]);

    }

    #[Route('/new/athlete')]
    public function newAthlete(EntityManagerInterface $doctrine){

        //Creating athlete type objects
        $athlete1 = new Athlete();
        $athlete1-> setCode(15);
        $athlete1-> setName('');
        $athlete1-> setSport('');
        $athlete1-> setDescription('');
        $athlete1-> setImg('');
        $athlete1-> setBirthDate('');
    
        //Creando titleWon type objects
        $titleWon1 = new TitlesWon();
        $titleWon1 -> setTitles('');
        
        //Relacion atletas y titulos
        $athlete1 -> addTitlesName($titleWon1);

        //Advising to add this data to the DB
        $doctrine->persist($athlete1);
        $doctrine->persist($titleWon1);
   

        //Inserting the data into the DB
        $doctrine->flush();

        return new Response("Atletas y títulos insertados correctamente");
    }

    #[Route('/insert/athlete', name: 'insertAthlete')]
    //Handling requests with "Request" (from symfony)
    public function insertAthlete(Request $request, EntityManagerInterface $doctrine, AthleteManager $manager) {
        //Seleccionando form --> con el que vamos a trabajar 
        $form = $this-> createForm(AthleteType::class);
        //Seeing if the user has filled in the data and clicker on send
        $form -> handleRequest($request);
        if($form -> isSubmitted() && $form -> isValid()) {
            //Returning an object of type athlete with the data filled in by the user
            $athlete = $form -> getData();
            $athleteImg = $form -> get('imagenAtleta') -> getData();
            if($athleteImg) {
            //Saving the response of the load method
                $athleImage = $manager -> load($athleteImg, $this -> getParameter('kernel.project_dir').'/public/assets/image');
                $athlete -> setImg("/assets/image/$athleImage");
            }
            $doctrine -> persist($athlete);
            $doctrine -> flush();
            //Adding message
            $this -> addFlash('success', 'Atleta insertado correctamente');
        
            return $this -> redirectToRoute('listAthlete');
        }

        //Making the "renderForm"
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

    #[IsGranted('ROLE_ADMIN')] //only people with role_admin are going to be able to delete
    #[Route('/remove/athlete/{id}', name: 'removeAthlete')]
    public function removeAthlete(EntityManagerInterface $doctrine, $id) {
        //Consulting DB to find out wich athlete we want to delete
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