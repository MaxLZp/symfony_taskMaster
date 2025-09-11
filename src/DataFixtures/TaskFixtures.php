<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\Project;
use App\DataFixtures\ProjectFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Website project tasks
        $websiteProject = $this->getReference(ProjectFixtures::PROJECT_WEBSITE, Project::class);
        
        $tasks = [
            [
                'title' => 'Design new homepage layout',
                'description' => 'Create wireframes and mockups for the new homepage design',
                'status' => Task::STATUS_COMPLETED,
                'priority' => Task::PRIORITY_HIGH,
                'dueDate' => new \DateTimeImmutable('-2 days'),
                'project' => $websiteProject
            ],
            [
                'title' => 'Implement responsive navigation',
                'description' => 'Code the new navigation menu with mobile responsiveness',
                'status' => Task::STATUS_IN_PROGRESS,
                'priority' => Task::PRIORITY_MEDIUM,
                'dueDate' => new \DateTimeImmutable('+3 days'),
                'project' => $websiteProject
            ],
            [
                'title' => 'Content migration',
                'description' => 'Migrate all content from old website to new structure',
                'status' => Task::STATUS_TODO,
                'priority' => Task::PRIORITY_MEDIUM,
                'dueDate' => new \DateTimeImmutable('+1 week'),
                'project' => $websiteProject
            ],
            [
                'title' => 'SEO optimization',
                'description' => 'Optimize meta tags, URLs, and content for search engines',
                'status' => Task::STATUS_TODO,
                'priority' => Task::PRIORITY_LOW,
                'dueDate' => new \DateTimeImmutable('+2 weeks'),
                'project' => $websiteProject
            ]
        ];

        foreach ($tasks as $taskData) {
            $task = new Task();
            $task->setTitle($taskData['title']);
            $task->setDescription($taskData['description']);
            $task->setStatus($taskData['status']);
            $task->setPriority($taskData['priority']);
            $task->setDueDate($taskData['dueDate']);
            $task->setProject($taskData['project']);
            
            $manager->persist($task);
        }

        // Mobile app project tasks
        $mobileProject = $this->getReference(ProjectFixtures::PROJECT_MOBILE_APP, Project::class);
        
        $mobileTasks = [
            [
                'title' => 'Setup development environment',
                'description' => 'Configure React Native development tools and simulators',
                'status' => Task::STATUS_COMPLETED,
                'priority' => Task::PRIORITY_HIGH,
                'dueDate' => new \DateTimeImmutable('-1 week'),
                'project' => $mobileProject
            ],
            [
                'title' => 'Design app wireframes',
                'description' => 'Create detailed wireframes for all app screens',
                'status' => Task::STATUS_REVIEW,
                'priority' => Task::PRIORITY_HIGH,
                'dueDate' => new \DateTimeImmutable('+1 day'),
                'project' => $mobileProject
            ],
            [
                'title' => 'Implement authentication flow',
                'description' => 'Build login, registration, and password reset functionality',
                'status' => Task::STATUS_TODO,
                'priority' => Task::PRIORITY_CRITICAL,
                'dueDate' => new \DateTimeImmutable('+5 days'),
                'project' => $mobileProject
            ]
        ];

        foreach ($mobileTasks as $taskData) {
            $task = new Task();
            $task->setTitle($taskData['title']);
            $task->setDescription($taskData['description']);
            $task->setStatus($taskData['status']);
            $task->setPriority($taskData['priority']);
            $task->setDueDate($taskData['dueDate']);
            $task->setProject($taskData['project']);
            
            $manager->persist($task);
        }

        // Marketing project tasks
        $marketingProject = $this->getReference(ProjectFixtures::PROJECT_MARKETING, Project::class);
        
        $marketingTasks = [
            [
                'title' => 'Market research analysis',
                'description' => 'Analyze target audience and competitor strategies',
                'status' => Task::STATUS_TODO,
                'priority' => Task::PRIORITY_HIGH,
                'dueDate' => new \DateTimeImmutable('+3 days'),
                'project' => $marketingProject
            ],
            [
                'title' => 'Create campaign materials',
                'description' => 'Design banners, social media posts, and email templates',
                'status' => Task::STATUS_TODO,
                'priority' => Task::PRIORITY_MEDIUM,
                'dueDate' => new \DateTimeImmutable('+1 week'),
                'project' => $marketingProject
            ]
        ];

        foreach ($marketingTasks as $taskData) {
            $task = new Task();
            $task->setTitle($taskData['title']);
            $task->setDescription($taskData['description']);
            $task->setStatus($taskData['status']);
            $task->setPriority($taskData['priority']);
            $task->setDueDate($taskData['dueDate']);
            $task->setProject($taskData['project']);
            
            $manager->persist($task);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProjectFixtures::class,
        ];
    }
}
