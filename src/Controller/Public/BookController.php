<?php

namespace App\Controller\Public;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('', name: 'app_public_book_index', methods: ['GET'])]
    public function index(BookRepository $repository): Response
    {
        $books = $repository->findAll();

        return $this->render('public/book/index.html.twig', [
            'controller_name' => 'BookController',
            'books' => $books,
        ]);
    }
}
