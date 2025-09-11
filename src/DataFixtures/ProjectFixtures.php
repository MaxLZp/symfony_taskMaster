<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProjectFixtures extends Fixture
{
    const PROJECT_WEBSITE = 'project-website';
    const PROJECT_MOBILE_APP = 'mobile-app';
    const PROJECT_MARKETING = 'project-marketing';
    public function load(ObjectManager $manager): void
    {
        $project = new Project();
        $project->setName('Website redesign');
        $project->setDescription('Complete overhaul of company website with modern design and improved user experience');
        $project->setStatus('active');
        $manager->persist($project);
        $this->addReference(self::PROJECT_WEBSITE, $project);

        // Mobile App Project
        $mobileProject = new Project();
        $mobileProject->setName('Mobile App Development');
        $mobileProject->setDescription('Native mobile application for iOS and Android platforms');
        $mobileProject->setStatus('active');
        $manager->persist($mobileProject);
        $this->addReference(self::PROJECT_MOBILE_APP, $mobileProject);

        // Marketing Campaign Project
        $marketingProject = new Project();
        $marketingProject->setName('Q4 Marketing Campaign');
        $marketingProject->setDescription('Digital marketing campaign for the holiday season');
        $marketingProject->setStatus('active');
        $manager->persist($marketingProject);
        $this->addReference(self::PROJECT_MARKETING, $marketingProject);

        // Completed Project Example
        $completedProject = new Project();
        $completedProject->setName('Server Migration');
        $completedProject->setDescription('Migration from old hosting provider to new cloud infrastructure');
        $completedProject->setStatus('completed');
        $manager->persist($completedProject);

        $manager->flush();
    }
}
