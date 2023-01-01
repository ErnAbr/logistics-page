<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use App\Classes\Uploader;
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

            $this->addFlash('success', true);


            return $this->redirectToRoute('home');
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

    #[Route('/admin', name: 'admin')]
    function admin(ManagerRegistry $doctrine)
    {
        $contactRepository = $doctrine->getManager()->getRepository(Contacts::class);
        $contacts = $contactRepository->findAll();
        $blogRepository = $doctrine->getManager()->getRepository(Blog::class);

        $request = Request::createFromGlobals();
        $articleSlug = $request->request->get('article-slug');
        $articleTitle = $request->request->get('article-title');
        $articleDate = $request->request->get('article-date');
        $articleText = $request->request->get('article-text');

        if ($request->isMethod('POST')) {

            $blog = $blogRepository->findOneBy(['slug' => $articleSlug]);

            $em = $doctrine->getManager();
            $em->remove($blog);
            $em->flush();

            $blogs = new Blog();
            $blogs->setTitle($articleTitle);
            $blogs->setDate($articleDate);
            $blogs->setContent($articleText);
            $blogs->setSlug($articleSlug);

            $em->persist($blogs);
            $em->flush();

            $this->addFlash('success', true);

            return $this->redirectToRoute('admin');

        }
        $upload = new Blog();
        $form = $this->createForm(Uploader::class, $upload);


        return $this->render('admin.html.twig', [
            'contacts' => $contacts,
            'upload_form' => $form->createView()

        ]);
    }

    #[Route('/admin/deleted-{id}', name: 'delete_contact')]
    function deleteQuery(ManagerRegistry $doctrine, $id)
    {
        $contactRepository = $doctrine->getManager()->getRepository(Contacts::class);
        $contactsDelete = $contactRepository->findOneBy(['id' => $id]);

        $em = $doctrine->getManager();
        $em->remove($contactsDelete);
        $em->flush();

        // $contacts = $contactRepository->findAll();

        return $this->redirectToRoute('admin');
    }

    #[Route('/blog-post-{slug}', name: 'blog_posts')]
    function blogPosts(ManagerRegistry $doctrine, $slug)
    {
        $blogRepository = $doctrine->getManager()->getRepository(Blog::class);
        $blogs = $blogRepository->findOneBy(['slug' => $slug]);
        return $this->render('newsBlog.html.twig', [
            'blogs' => $blogs
        ]);
    }

}