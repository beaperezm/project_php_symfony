<?php

//dónde se encuentra
namespace App\Controller;

use App\Entity\Athlete;
use App\Entity\TitlesWon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AthleteController extends AbstractController {

    //VOY POR PT3 min 1h

    //Creando la ruta de la url --> el id entre {} le estamos indicando que queremos que sea variable
    //el name tiene que ser único - no se puede repetir y es el que yo eliga (este name es el que voy a usar al añadir en href la path en el archivo html.twig)
    #[Route('/athlete/{id}', name: 'showAthlete')]
    //Creando la función para mostrar a cada atleta --> recibe el servicio que va  a utilizar (EntutyManagerInterface de doctrine) y la parte variable de la url
    public function showAthlete(EntityManagerInterface $doctrine, $id) {

        //creando el array del modelo de cada atleta con su clave => valor
        $repository = $doctrine -> getRepository(Athlete::class);
        $athlete = $repository->find($id);

        //devolviendo al resultado de llamar el método render al atleta para que lo muestre en pantalla(<nombre de la plantilla que queremos renderizar) SIEMPRE es igual return $this->render(1ºArg, 2ºArg)
        //1er argumento del método render
        //athletes - nombre del directorio
        //showAthlete.html.twig --> nombre del fichero
        //2º argumento del método render
        //array clave => valor
        //'clave' --> nombre que queremos dar a la variable de twig que vamos a crear ('athlete')
        //valor --> variable de php creada más arriba (array del modelo)
        //'athlete' es el $athlete
        return $this->render('athletes/showAthlete.html.twig', ['athlete'=>$athlete]);

    }

    //name usado en baseAthelete "listar Atletas"
    #[Route('/athletes', name: 'listAthlete')]
    public function listAthlete(EntityManagerInterface $doctrine){
        //Athlete::class ---> listado de atletas que tienen que cargarse
        //con esto tenemos el repositorio
        $repository = $doctrine -> getRepository(Athlete::class);
        //Tiene que encontrar a todos los atletas y lo que hará será devolver un array del tipo athletes
        $athletes = $repository->findAll();
   
        return $this->render('athletes/listAthlete.html.twig', ['athletes' => $athletes]);

    }

    #[Route('/new/athlete')]
    //1º argumento: EntityManagerInterface --> es el nombre del servicio de doctrine, con el que digo que quiero usar doctrine
    //2º variable que le pongo el nombre que quiera
    public function newAthlete(EntityManagerInterface $doctrine){

        //crear objetos del tipo athlete
        $athlete1 = new Athlete();
        $athlete1-> setCode(1);
        $athlete1-> setName('Rafa Nadal');
        $athlete1-> setSport('Tenista');
        $athlete1-> setDescription('Rafael Nadal Parera, más conocido como Rafa Nadal, es un tenista profesional español que ocupa la sexta posición del ranking ATP. Está consieradp cpmo el mejor tenista de la historia en pistas de tierra batida y uno de los mejores de todos los tiempos.');
        $athlete1-> setImg('https://e00-elmundo.uecdn.es/assets/multimedia/imagenes/2022/07/02/16567890145681.jpg');
        $athlete1-> setBirthDate('3 de junio de 1986');

 //con alt mayusc bloqmayus(shift) hago selección múltiple
        $athlete2 = new Athlete();
        $athlete2-> setCode(2);
        $athlete2-> setName('Pau Gasol');
        $athlete2-> setSport('Jugador de baloncesto');
        $athlete2-> setDescription('Es un exjugador español de baloncesto que disputó 18 temporadas en la NBA, donde fue dos veces campeón, y otras 3 en el FC Barcelona. Con 2,13 metros de altura jugaba en la posición de ala-pívot.');
        $athlete2-> setImg('https://www.legalsport.net/wp-content/uploads/2023/03/GettyImages-1034922748-1200x685.jpg');
        $athlete2-> setBirthDate('6 de julio de 1980');

        $titleWon1 = new TitlesWon();
        $titleWon1 -> setTitles('Grand Slam');

        $titleWon2 = new TitlesWon();
        $titleWon2 -> setTitles('Masters1000');
        
        $titleWon3 = new TitlesWon();
        $titleWon3 -> setTitles('Copa Davis');

        $titleWon4 = new TitlesWon();
        $titleWon4 -> setTitles('Wimbledon');

        $titleWon5 = new TitlesWon();
        $titleWon5 -> setTitles('Juegos Olímpicos');

        $titleWon6 = new TitlesWon();
        $titleWon6 -> setTitles('Mundial');

        $titleWon7 = new TitlesWon();
        $titleWon7 -> setTitles('Eurobasket');

        $titleWon8 = new TitlesWon();
        $titleWon8 -> setTitles('Liga ACB');

        $titleWon9 = new TitlesWon();
        $titleWon9 -> setTitles('Copa del Rey');

        $titleWon10 = new TitlesWon();
        $titleWon10 -> setTitles('Anillo de la NBA');

        //relacion atletas y titulos

        $athlete1 -> addTitlesName($titleWon1);
        $athlete1 -> addTitlesName($titleWon2);
        $athlete1 -> addTitlesName($titleWon3);
        $athlete1 -> addTitlesName($titleWon4);
        $athlete1 -> addTitlesName($titleWon5);
        $athlete2 -> addTitlesName($titleWon6);
        $athlete2 -> addTitlesName($titleWon7);
        $athlete2 -> addTitlesName($titleWon8);
        $athlete2 -> addTitlesName($titleWon9);
        $athlete2 -> addTitlesName($titleWon10);


//con el persist digo que añada estos datos a la DB
        $doctrine->persist($athlete1);
        $doctrine->persist($athlete2);
        $doctrine->persist($titleWon1);
        $doctrine->persist($titleWon2);
        $doctrine->persist($titleWon3);
        $doctrine->persist($titleWon4);
        $doctrine->persist($titleWon5);
        $doctrine->persist($titleWon6);
        $doctrine->persist($titleWon7);
        $doctrine->persist($titleWon8);
        $doctrine->persist($titleWon9);
        $doctrine->persist($titleWon10);

//una vez hecho el flush se inserta en la DB
        $doctrine->flush();
        return new Response("Atletas y títulos insertados correctamente");
    }
}

?>