<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorFormType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route("/author/add", name: "author_add")]
    public function add(ManagerRegistry $doctrine, Request $request): Response
    {
        $author = new Author();
        $em = $doctrine->getManager();
        $form = $this->createForm(AuthorFormType::class, $author);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('author_findAll');  // Changed here
        }
        
        return $this->render('author/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/author/findAll", name: "author_findAll")]
    public function findAll(AuthorRepository $repository): Response
    {
        $authors = $repository->findAll();
        return $this->render('author/index.html.twig', [
            'authors' => $authors
        ]);
    }

    #[Route("/author/update/{id}", name: "author_update")]
    public function update(ManagerRegistry $doctrine, AuthorRepository $repository, $id, Request $request): Response
    {
        $author = $repository->find($id);
        $form = $this->createForm(AuthorFormType::class, $author);
        $form->handleRequest($request);
        $em = $doctrine->getManager();
        
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return $this->redirectToRoute('author_findAll');  // Changed here
        }

        return $this->render('author/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/author/delete/{id}", name: "author_delete")]
    public function delete($id, AuthorRepository $repository, ManagerRegistry $doctrine): Response
    {
        $author = $repository->find($id);
        $em = $doctrine->getManager();
        $em->remove($author);
        $em->flush();
        return new Response("Author deleted successfully");
    }

    #[Route("/author/findAll/name/{username}",name : "findByUsername")]
    public function findByUsername($username , AuthorRepository $repository):Response{
        $authors = $repository->findByUserName($username);
        
        return $this->render('author/index.html.twig',[
            "authors" => $authors
        ]);
    }

    #[Route("/author/findAll/char/{c}",name : "findByUsername")]
    public function findByChar($c , AuthorRepository $repository):Response{
        $authors = $repository->findByChar($c);
        
        return $this->render('author/index.html.twig',[
            "authors" => $authors
        ]);
    }

}