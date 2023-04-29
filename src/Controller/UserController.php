<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/create/user', name: 'createUser')]
    public function insertUser(Request $request, EntityManagerInterface $doctrine, UserPasswordHasherInterface $hasher) {
        $form = $this-> createForm(UserType::class);
        $form -> handleRequest($request);
        if($form -> isSubmitted() && $form -> isValid()) {
            $user = $form -> getData();
            $password = $user -> getPassword();
           //Encripting the password
            $passwordBCrypt = $hasher -> hashPassword($user, $password);
            $user -> setPassword($passwordBCrypt);
            $doctrine -> persist($user);
            $doctrine -> flush();
            
            $this -> addFlash('success', 'Usuario insertado correctamente');
            
            return $this -> redirectToRoute('listAthlete');
        }

        return $this-> render('user/createUser.html.twig', ['userForm' => $form -> createView()]);
}

#[Route('/create/admin', name: 'createAdmin')]
public function insertAdmin(Request $request, EntityManagerInterface $doctrine, UserPasswordHasherInterface $hasher) {
    $form = $this-> createForm(UserType::class);
    $form -> handleRequest($request);
    if($form -> isSubmitted() && $form -> isValid()) {
        $user = $form -> getData();
        $password = $user -> getPassword();
        $passwordBCrypt = $hasher -> hashPassword($user, $password);
        $user -> setPassword($passwordBCrypt);
        $user -> setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $doctrine -> persist($user);
        $doctrine -> flush();
        $this -> addFlash('success', 'Administrador insertado correctamente');
       
        return $this -> redirectToRoute('listAthlete');
    }

    return $this-> render('user/createUser.html.twig', ['userForm' => $form -> createView()]);
}

}