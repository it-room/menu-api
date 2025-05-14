<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\User;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Ingrediant;

#[Route('/api/menu')]
class ApiMenuController extends AbstractController
{
    #[Route('/my', name: 'api_mes_menu', methods: ['GET'])]
    public function myMenu(
        MenuRepository $menuRepository,
        NormalizerInterface $normalizer
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            return $this->json(['message' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $menus = $menuRepository->findMenuByUser($user->getId());

        return $this->json(
            $normalizer->normalize($menus, null, ['groups' => 'menu:read']),
            JsonResponse::HTTP_OK
        );
    }
    
    #[Route('/{menuId}/ingredients', name: 'api_menu_add_ingredients', methods: ['POST'])]
    public function addIngredients(
        int $menuId,
        Request $request,
        EntityManagerInterface $em,
        NormalizerInterface $normalizer
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            return $this->json(['message' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $menu = $em->getRepository(Menu::class)->find($menuId);
        if (!$menu) {
            return $this->json(['message' => 'Menu not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        if ($menu->getUserlink() !== $user) {
            return $this->json(['message' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['ingredients']) || !is_array($data['ingredients'])) {
            return $this->json(['message' => 'Missing ingredients array'], JsonResponse::HTTP_BAD_REQUEST);
        }

        foreach ($data['ingredients'] as $ingredientId) {
            $ingredient = $em->getRepository(Ingrediant::class)->find($ingredientId);
            if ($ingredient) {
                $menu->addIngrediant($ingredient);
            }
        }

        $em->flush();

        return $this->json(
            $normalizer->normalize($menu, null, ['groups' => 'menu:read']),
            JsonResponse::HTTP_OK
        );
    }
    #[Route('/{menuId}/ingredients/titles', name: 'api_menu_add_ingredients_titles', methods: ['POST'])]
    public function addIngredientsByTitles(
        int $menuId,
        Request $request,
        EntityManagerInterface $em,
        NormalizerInterface $normalizer
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            return $this->json(['message' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $menu = $em->getRepository(Menu::class)->find($menuId);
        if (!$menu) {
            return $this->json(['message' => 'Menu not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        if ($menu->getUserlink() !== $user) {
            return $this->json(['message' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['titles']) || !is_array($data['titles'])) {
            return $this->json(['message' => 'Missing titles array'], JsonResponse::HTTP_BAD_REQUEST);
        }

        foreach ($data['titles'] as $title) {
            $ingredient = new Ingrediant();
            $ingredient->setTitre($title);
            $ingredient->setMenu($menu);
            $em->persist($ingredient);
        }

        $em->flush();

        return $this->json(
            $normalizer->normalize($menu, null, ['groups' => 'menu:read']),
            JsonResponse::HTTP_OK
        );
    }

    #[Route('/{menuId}/ingredients', name: 'api_menu_update_ingredients', methods: ['PUT'])]
    public function updateIngredients(
        int $menuId,
        Request $request,
        EntityManagerInterface $em,
        NormalizerInterface $normalizer
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            return $this->json(['message' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $menu = $em->getRepository(Menu::class)->find($menuId);
        if (!$menu) {
            return $this->json(['message' => 'Menu not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        if ($menu->getUserlink() !== $user) {
            return $this->json(['message' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['ingredients']) || !is_array($data['ingredients'])) {
            return $this->json(['message' => 'Missing ingredients array'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Remove all existing ingredients
        foreach ($menu->getIngrediants() as $ingredient) {
            $menu->removeIngrediant($ingredient);
        }

        // Add new ingredients
        foreach ($data['ingredients'] as $ingredientId) {
            $ingredient = $em->getRepository(Ingrediant::class)->find($ingredientId);
            if ($ingredient) {
                $menu->addIngrediant($ingredient);
            }
        }

        $em->flush();

        return $this->json(
            $normalizer->normalize($menu, null, ['groups' => 'menu:read']),
            JsonResponse::HTTP_OK
        );
    }

    #[Route('/{menuId}/ingredients/{ingredientId}', name: 'api_menu_remove_ingredient', methods: ['DELETE'])]
    public function removeIngredient(
        int $menuId,
        int $ingredientId,
        EntityManagerInterface $em,
        NormalizerInterface $normalizer
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            return $this->json(['message' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $menu = $em->getRepository(Menu::class)->find($menuId);
        if (!$menu) {
            return $this->json(['message' => 'Menu not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        if ($menu->getUserlink() !== $user) {
            return $this->json(['message' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $ingredient = $em->getRepository(Ingrediant::class)->find($ingredientId);
        if (!$ingredient) {
            return $this->json(['message' => 'Ingredient not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $menu->removeIngrediant($ingredient);
        $em->flush();

        return $this->json(
            $normalizer->normalize($menu, null, ['groups' => 'menu:read']),
            JsonResponse::HTTP_OK
        );
    }
    #[Route('/add', name: 'api_menu_add', methods: ['POST'])]
    public function addMenu(
        Request $request,
        EntityManagerInterface $em,
        NormalizerInterface $normalizer
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            return $this->json(['message' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['titre'])) {
            return $this->json(['message' => 'Missing title'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $menu = new Menu();
        $menu->setTitre($data['titre']);
        $menu->setUserlink($user);
        if (isset($data['photo'])) {
            $menu->setPhoto($data['photo']);
        }

        $em->persist($menu);
        $em->flush();
        return $this->json([
            'id' => $menu->getId(),
            'menu' => $normalizer->normalize($menu, null, ['groups' => 'menu:read'])
        ], JsonResponse::HTTP_CREATED);
    }

    #[Route('/update/{id}', name: 'api_menu_update', methods: ['PUT'])]
    public function updateMenu(
        Menu $menu,
        Request $request,
        EntityManagerInterface $em,
        NormalizerInterface $normalizer
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user instanceof UserInterface || $menu->getUserlink() !== $user) {
            return $this->json(['message' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);
        if (isset($data['titre'])) {
            $menu->setTitre($data['titre']);
        }
        if (isset($data['photo'])) {
            $menu->setPhoto($data['photo']);
        }

        $em->flush();

        return $this->json(
            $normalizer->normalize($menu, null, ['groups' => 'menu:read'])
        );
    }

    #[Route('/delete/{id}', name: 'api_menu_delete', methods: ['DELETE'])]
    public function deleteMenu(
        Menu $menu,
        EntityManagerInterface $em
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user instanceof UserInterface || $menu->getUserlink() !== $user) {
            return $this->json(['message' => 'Unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $em->remove($menu);
        $em->flush();

        return $this->json(['message' => 'Menu deleted']);
    }
}
