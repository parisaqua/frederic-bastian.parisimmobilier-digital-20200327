<?php

namespace App\Controller;

use Twig\Environment;
use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {
    
    /**
     * Page d'accueil
     *
     * @Route("/", name="home")
     * 
     * @return Response
     */
    public function index(PropertyRepository $repository):Response {
        
        $properties = $repository->findLatest();
        
        return $this->render('pages/home.html.twig', [
            'properties' => $properties,
            'current_menu' => "accueil"
        ]);
    }

}