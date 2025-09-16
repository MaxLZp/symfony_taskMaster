<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Project;
use App\Repository\ProjectRepository;
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

    #[Route('test/repositories', name:'app_test_repositories', methods: ['GET'])]
    public function testRepository(EntityManagerInterface $em): Response
    {
        $projectRepo = $em->getRepository(Project::class);
        $taskRepo = $em->getRepository(Task::class);
        
        // Test methods
        $activeProjects = $projectRepo->findActiveProjects();
        $overdueTasks = $taskRepo->findOverdueTasks();
        $taskStats = $taskRepo->getTaskStatistics();
        
        return $this->json([
            'active_projects_count' => count($activeProjects),
            'overdue_tasks_count' => count($overdueTasks),
            'task_statistics' => $taskStats,
            'projectTasksSummary' => $projectRepo->getProjectTaskSummary(),
        ]);
    }

    #[Route('/test/db/', name: 'app_test_db')]
    public function db_test(ProjectRepository $projectRepo, \App\Repository\TaskRepository $taskRepo): Response
    {
        $projects = $projectRepo->findAll();
        $tasks = $taskRepo->findAll();
        $overdueTasks = $taskRepo->findOverdueTasks();
        $taskStats = $taskRepo->getTaskStatistics();

        $tasksDueInAWeek = $taskRepo->findTasksDueInThisWeek();
        $completedTasks = $taskRepo->findTasksByStatus(Task::STATUS_COMPLETED);
        $activeProjects = $projectRepo->findProjectsByStatusWithTaskCount('active');

        return $this->render('test/db_test.html.twig', [
            'projects' => $projects,
            'tasks' => $tasks,
            'overdue_tasks' => $overdueTasks,
            'task_stats' => $taskStats
        ]);
    }

    #[Route('/test/db_create', name:'app_test_create')]
    public function createSample(EntityManagerInterface $em): Response
    {
        $project = new Project();
        $project->setName('TestProject'.date('H:i:s'));
        $project->setDescription('This project was created through the web interface');

        $task = new Task();
        $task->setProject($project);
        $task->setTitle('Test Task'.date('H:i:s'));
        $task->setDescription('This task was created to test database functionality');
        $task->setPriority(Task::PRIORITY_HIGH);
        $task->setDueDate(new \DateTimeImmutable('+3 days'));

        $em->persist($project);
        $em->persist($task);
        $em->flush();

        $this->addFlash('success', 'Sample project and task created successfully!');

        return $this->redirectToRoute('app_test_db');
    }
}
