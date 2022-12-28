<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Contacts;
use App\Entity\Blog;

class IndexController extends AbstractController
{
    #[Route('/', name: 'home')]
    function homepage(ManagerRegistry $doctrine)
    {

        $request = Request::createFromGlobals();
        $clientName = $request->request->get('client-name');
        $clientEmail = $request->request->get('client-email');
        $message = $request->request->get('client-message');

        if ($request->isMethod('POST')) {

            $error = null;
            if (!$clientName || !$clientEmail || !$message) {
                $error = 'būtina užpildyti visus laukus';
            }

            if (!str_contains($clientEmail, "@")) {
                $error = 'įveskite tinkamą el. pašto adresą';
            }

            if ($error) {
                return $this->render('index.html.twig', [
                    'error' => $error
                ]);
            }

            $contact = new Contacts();

            $contact->setEmail($clientEmail);
            $contact->setName($clientName);
            $contact->setMessage($message);

            $manager = $doctrine->getManager();

            $manager->persist($contact); // pridedam $contact objektą į sąrašą queriu kuriuos mes vykdysim

            $manager->flush(); // mes įvykdom visus querius.

            return $this->render('index.html.twig', [
                'success' => true
            ]);
        }
        return $this->render('index.html.twig');
    }

    #[Route('/create-blog-post', name: 'create_blog')]
    function createBlog(ManagerRegistry $doctrine)
    {
        $blog = new Blog();

        $blog->setTitle('New title');
        $blog->setSlug('some-slug');
        $blog->setContent('Some content');
        $blog->setDate(date('Y-M-D'));

        $manager = $doctrine->getManager();

        $manager->persist($blog);
        $manager->flush();

        return new Response(true);
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

    #[Route('/admin', name: 'admin')]
    function admin(ManagerRegistry $doctrine)
    {
        $contactRepository = $doctrine->getManager()->getRepository(Contacts::class);
        $contacts = $contactRepository->findAll();

        return $this->render('admin.html.twig', [
            'contacts' => $contacts
        ]);
    }

    #[Route('/admin/{id}', name: 'delete_contact')]
    function deleteQuery(ManagerRegistry $doctrine, $id)
    {
        $contactRepository = $doctrine->getManager()->getRepository(Contacts::class);
        $contactsDelete = $contactRepository->findOneBy(['id' => $id]);

        $em = $doctrine->getManager();
        $em->remove($contactsDelete);
        $em->flush();

        $contacts = $contactRepository->findAll();

        return $this->render('admin.html.twig', [
            'contacts' => $contacts
        ]);
    }

}