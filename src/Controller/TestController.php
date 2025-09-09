<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TestController extends AbstractController
{
    #[Route('/test/entities', name: 'app_test_entities')]
    public function index(EntityManagerInterface $em): Response
    {
        $project = new Project();
        $project->setName('Test Project');
        $project->setDescription('This is a test project');

        $task = new Task();
        $task->setTitle('Test Task');
        $task->setDescription('This is a test task');

        $project->addTask($task);
        
        return new Response(sprintf('Project: %1$s, Tasks: %2$d', $project->getName(), $project->getTotalTasksCount()));
    }
}
