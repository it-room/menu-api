<?php

namespace App\Controller;


use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;


class ApiLoginController extends AbstractController
  {
       #[Route('/api/login', name: 'api_login', methods: ['POST'])]
     public function index(#[CurrentUser] ?User $user,
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
  }
