<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Classes\Movies;

class IndexController extends AbstractController
{
    #[Route('/', name: 'base')]
    public function homepage()
    {
        return $this->render('index.html.twig');
    }

}