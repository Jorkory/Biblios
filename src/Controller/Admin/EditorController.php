<?php

namespace App\Controller\Admin;

use App\Entity\Editor;
use App\Form\EditorType;
use App\Repository\EditorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

    #[Route('/admin/editor')]
class EditorController extends AbstractController
{
    #[Route('', name: 'app_admin_editor_index')]
    public function index(EditorRepository $repository): Response
    {
        $editors = $repository->findAll();

        return $this->render('admin/editor/index.html.twig', [
            'controller_name' => 'EditorController',
            'editors' => $editors,
        ]);
    }


    #[Route('/new', name: 'app_admin_editor_new')]
    #[Route('/{id}/edit', name: 'app_admin_editor_edit', methods: ['GET', 'POST'])]
    public function new(?Editor $editor, Request $request, EntityManagerInterface $manager): Response
    {
        $editor ??= new Editor();
        $form = $this->createForm(EditorType::class, $editor);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($editor);
            $manager->flush();

            return $this->redirectToRoute('app_admin_editor_index');
        }

        return $this->render('admin/editor/new.html.twig', [
            'form' => $form,
        ]);
    }
}
