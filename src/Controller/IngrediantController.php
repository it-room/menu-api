<?php

namespace App\Controller;

use App\Entity\Ingrediant;
use App\Form\IngrediantType;
use App\Repository\IngrediantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ingrediant')]
final class IngrediantController extends AbstractController
{
    #[Route(name: 'app_ingrediant_index', methods: ['GET'])]
    public function index(IngrediantRepository $ingrediantRepository): Response
    {
        return $this->render('ingrediant/index.html.twig', [
            'ingrediants' => $ingrediantRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ingrediant_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ingrediant = new Ingrediant();
        $form = $this->createForm(IngrediantType::class, $ingrediant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ingrediant);
            $entityManager->flush();

            return $this->redirectToRoute('app_ingrediant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ingrediant/new.html.twig', [
            'ingrediant' => $ingrediant,
            'form' => $form,
        ]);
    }
    #[Route('/menu/{id}', name: 'app_ingrediant_menu_add', methods: ['GET', 'POST'])]
    public function addToMenu(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $ingrediant = new Ingrediant();
        $form = $this->createForm(IngrediantType::class, $ingrediant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $menu = $entityManager->getRepository('App\Entity\Menu')->find($id);
            
            if (!$menu) {
                throw $this->createNotFoundException('Menu non trouvé');
            }

            $ingrediant->setMenu($menu);
            $entityManager->persist($ingrediant);
            $entityManager->flush();

            return $this->redirectToRoute('app_menu_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ingrediant/new.html.twig', [
            'ingrediant' => $ingrediant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ingrediant_show', methods: ['GET'])]
    public function show(Ingrediant $ingrediant): Response
    {
        return $this->render('ingrediant/show.html.twig', [
            'ingrediant' => $ingrediant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ingrediant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ingrediant $ingrediant, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(IngrediantType::class, $ingrediant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ingrediant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ingrediant/edit.html.twig', [
            'ingrediant' => $ingrediant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ingrediant_delete', methods: ['POST'])]
    public function delete(Request $request, Ingrediant $ingrediant, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ingrediant->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($ingrediant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ingrediant_index', [], Response::HTTP_SEE_OTHER);
    }
}
