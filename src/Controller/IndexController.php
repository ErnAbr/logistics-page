<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Kontaktai;

class IndexController extends AbstractController
{
    #[Route('/', name: 'home')]
    function homepage(ManagerRegistry $doctrine)
    {

        $request = Request::createFromGlobals();

        if ($request->isMethod('POST')) {

            $clientName = $request->request->get('client-name');
            $clientEmail = $request->request->get('client-email');
            $message = $request->request->get('client-message');
            var_dump($clientName);
            var_dump($clientEmail);
            var_dump($message);
            $contact = new Kontaktai();

            $contact->setEmail($clientEmail);
            $contact->setName($clientName);
            $contact->setMessage($message);

            $manager = $doctrine->getManager();

            $manager->persist($contact); // pridedam $contact objektą į sąrašą queriu kuriuos mes vykdysim

            $manager->flush(); // mes įvykdom visus querius.

        }
        return $this->render('index.html.twig');
    }

    #[Route('/about-us', name: 'about-us')]
    function aboutUs()
    {
        return $this->render('aboutUs.html.twig');
    }

    #[Route('/services', name: 'services')]
    function services()
    {
        return $this->render('services.html.twig');
    }

    #[Route('/careers', name: 'careers')]
    function careers()
    {
        return $this->render('careers.html.twig');
    }


}