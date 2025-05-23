<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\User;
use App\Form\MenuType;
use App\Repository\IngrediantRepository;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/menu/')]
final class MenuController extends AbstractController
{
    #[Route(name: 'app_menu_index', methods: ['GET'])]
    public function index(MenuRepository $menuRepository): Response
    {
        return $this->render('menu/index.html.twig', [
            'menus' => $menuRepository->findAll(),
        ]);
    }

    #[Route('search/{title}', name: 'app_menu_search', methods: ['GET'])]
    public function searchByIngredient(string $title, MenuRepository $menuRepository): Response
    {
        return $this->render('menu/index.html.twig', [
            'menus' => $menuRepository->findMenuByIngrediant($title),
        ]);
    }
    
    #[Route('my', name: 'app_menu_my', methods: ['GET'])]
    public function myMenu(MenuRepository $menuRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('menu/index.html.twig', [
            'menus' => $menuRepository->findMenuByUser($user->getId()),
        ]);
    }

    #[Route('new', name: 'app_menu_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $menu = new Menu();
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($menu);
            $entityManager->flush();

            return $this->redirectToRoute('app_menu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('menu/new.html.twig', [
            'menu' => $menu,
            'form' => $form,
        ]);
    }

    #[Route('{id}', name: 'app_menu_show', methods: ['GET'])]
    public function show(
        Menu $menu,
        IngrediantRepository $ingrediantRepository,
    ): Response
    {
        $ingrediants = $ingrediantRepository->findBy([
            'menu' => $menu->getId()
        ]);

        return $this->render('menu/show.html.twig', [
            'menu' => $menu,
            'ingrediants' => $ingrediants,
        ]);
    }

    #[Route('{id}/edit', name: 'app_menu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,
                         Menu $menu,
                         SluggerInterface $slugger,
                         EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MenuType::class, $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->isSubmitted() && $form->isValid()) {
                $photoFile = $form->get('photo')->getData();

                if ($photoFile) {
                    $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                    try {
                        $photoFile->move(
                            $this->getParameter('photos_directory'), // à définir dans services.yaml
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // handle error
                    }

                    $menu->setPhoto($newFilename);
                }

                $entityManager->flush();
            }
                return $this->redirectToRoute('app_menu_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('menu/edit.html.twig', [
                'menu' => $menu,
                'form' => $form,
            ]);
        }


    #[Route('{id}', name: 'app_menu_delete', methods: ['POST'])]
    public function delete(Request $request, Menu $menu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$menu->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($menu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_menu_index', [], Response::HTTP_SEE_OTHER);
    }
}
