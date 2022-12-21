<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Classes\Movies;

class IndexController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function homepage()
    {
        return $this->render('index.html.twig');

    }

    #[Route('/about-us', name: 'about-us')]
    public function aboutUs()
    {
        return $this->render('aboutUs.html.twig');
    }

    #[Route('/services', name: 'services')]
    public function services()
    {
        return $this->render('services.html.twig');
    }

    #[Route('/careers', name: 'careers')]
    public function careers()
    {
        return $this->render('careers.html.twig');
    }


}