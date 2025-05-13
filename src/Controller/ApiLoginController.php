<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\MenuRepository;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;


class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User     $user,
                          JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        if (null === $user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }


        $token = $jwtManager->create($user); // somehow create an API token for $user

        return $this->json([
            //'user'  => $user->getUserIdentifier(),
            'token' => $token,
            //'username' => $user->getUsername(),
        ]);
    }

    #[Route('/api/mesMenu', name: 'api_mes_menu', methods: ['GET'])]
    public function mesMenu(
        NormalizerInterface $normalizer,
        MenuRepository      $menuRepository,
    ): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Si tu veux seulement les menus de l'utilisateur :
        $menus = $menuRepository->findBy(['userlink' => $user->getId()]);

        //$menus = $menuRepository->findAll();

        $data = $normalizer->normalize($menus, null, [
            'groups' => ['menu:read', 'ingrediant:read']
        ]);

        return $this->json([
            'email' => $user->getEmail(),
            'menus' => $data,
        ]);

    }
}
