<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Contacts;
use App\Entity\Blog;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use App\Classes\Uploader;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class IndexController extends AbstractController
{

    #[Route('/', name: 'home')]
    function homepage(ManagerRegistry $doctrine)
    {

        $request = Request::createFromGlobals();
        $clientName = $request->request->get('client-name');
        $clientEmail = $request->request->get('client-email');
        $message = $request->request->get('client-message');

        $blogRepository = $doctrine->getManager()->getRepository(Blog::class);
        $blogs = $blogRepository->findAll();

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

            $manager->persist($contact);

            $manager->flush();

            $this->addFlash('success', true);


            return $this->redirectToRoute('home');
        }

        return $this->render('index.html.twig', [
            'blogs' => $blogs
        ]);
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
    function admin(ManagerRegistry $doctrine, SluggerInterface $slugger)
    {
        $contactRepository = $doctrine->getManager()->getRepository(Contacts::class);
        $contacts = $contactRepository->findAll();
        $blogs = new Blog();

        $blogRepository = $doctrine->getManager()->getRepository(Blog::class);

        $request = Request::createFromGlobals();
        $articleSlug = $request->request->get('article-slug');
        $articleTitle = $request->request->get('article-title');
        $articleDate = $request->request->get('article-date');
        $articleText = $request->request->get('article-text');
        $articleImage = $request->request->get('article-image');

        $articleImageFile = $request->files->get('article-image');

        if ($request->isMethod('POST')) {

            // var_dump($articleImageFile);
            // die;

            $blog = $blogRepository->findOneBy(['slug' => $articleSlug]);

            $em = $doctrine->getManager();
            $em->remove($blog);
            $em->flush();

            $blogs->setTitle($articleTitle);
            $blogs->setDate($articleDate);
            $blogs->setContent($articleText);
            $blogs->setSlug($articleSlug);
            $blogs->setImageName($articleImage);
            $em->persist($blogs);
            $em->flush();

            $this->addFlash('success', true);

            return $this->redirectToRoute('admin');

        }

        return $this->render('admin.html.twig', [
            'contacts' => $contacts,
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