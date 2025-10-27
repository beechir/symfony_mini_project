<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookFormType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route("book/add",name:"add")]
    public function add(ManagerRegistry $doctrine , Request $request):Response{
        $em = $doctrine->getManager();
        $book = new Book();
        $form = $this->createForm(BookFormType::class, $book);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($book) ;
            $em->flush();
            return new Response("Book added successfully");
        }

        return $this->render('book/add.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    #[Route("book/findAll",name : "findAll")]
    public function findAll(BookRepository $repository):Response{
        $books = $repository->findAll();
        return $this->render('book/index.html.twig',[
            "books"=>$books
        ]);
    }

    #[Route("book/update/{id}",name : "update")]
    public function update(ManagerRegistry $doctrine , BookRepository $repository, $id,Request $request){
        $em = $doctrine->getManager();
        $book = $repository->find($id);
        $form = $this->createForm(BookFormType::class, $book);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $em->flush();
            return $this->redirect('findAll');
        }
        return $this->render('book/update.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    #[Route("/delete/{id}", name : "delete")]
    public function delete($id , BookRepository $repository , ManagerRegistry $doctrine):Response{
        $book = $repository->find($id);
        $em= $doctrine->getManager();
        $em->remove($book);
        $em->flush();
        return new Response("Book deleted successfully");
    }
}
