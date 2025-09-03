<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api', name:'api_')]
class ApiController extends AbstractController
{
    #[Route('/health', name: 'health')]
    public function app_health(): JsonResponse
    {
        return $this->json([
            'status' => 200,
        ]);
    }
}
