<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AboutController extends AbstractController
{
    #[Route('/about', name: 'app_about')]
    public function index(): Response
    {
        return $this->render('about/index.html.twig', [
            'controller_name' => 'AboutController',
        ]);
    }

    #[Route('/features', name:'app_features')]
    public function features(): Response
    {
        $features = [
            'Task Management' => 'Create, organize, and track your tasks efficiently',
            'Project Organization' => 'Group related tasks into manageable projects',
            'Team Collaboration' => 'Work together with your team members',
            'File Attachments' => 'Attach files and documents to your tasks',
            'Time Tracking' => 'Monitor time spent on tasks and projects'
        ];
        
        return $this->render('about/features.html.twig', [
            'features' => $features
        ]);
    }
}
