<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/book')]
class BookController extends AbstractController
{
    #[Route('', name: 'app_admin_book_index', requirements: ['page' => '\d+'])]
    public function index(BookRepository $repository, Request $request): Response
    {
        $books = Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($repository->findAllMaxPerPage()),
            $request->get('page', 1),
            maxPerPage: 10
        );

        return $this->render('admin/book/index.html.twig', [
            'controller_name' => 'BookController',
            'books' => $books,
        ]);
    }

    #[Route('/{id}', 'app_admin_book_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?Book $book): Response
    {
        return $this->render('admin/book/show.html.twig', [
            'book' => $book,
        ]);
    }

    #[IsGranted('ROLE_AJOUT_DE_LIVRE')]
    #[Route('/new', name: 'app_admin_book_new', methods: ['GET', 'POST'])]
    #[Route('/{id}/edit', name: 'app_admin_book_edit',requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function new(?Book $book, Request $request, EntityManagerInterface $manager): Response
    {
        if ($book) {
            $this->isGranted('ROLE_EDITION_DE_LIVRE');
        }

        $book ??= new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($book);
            $manager->flush();

            return $this->redirectToRoute('app_admin_book_show', ['id' => $book->getId()]);
        }

        return $this->render('admin/book/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[isGranted('ROLE_ADMIN')]
    #[Route('/{id}/delete', name: 'app_admin_book_delete',requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(Book $book, Request $request, EntityManagerInterface $manager): Response
    {
        if ($book->getId()) {
            $manager->remove($book);
            $manager->flush();
        }
        return $this->redirectToRoute('app_admin_book_index');
    }
}
