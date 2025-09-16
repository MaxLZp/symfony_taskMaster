<?php

namespace App\Repository;

use App\Entity\Project;
use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function findActiveProjects(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.status = :status')
            ->setParameter('status', 'active')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findProjectWithTasksCount(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p', 'COUNT(t.id) as taskCount')
            ->leftJoin('p.tasks', 't')
            ->groupBy('p.id')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findProjectWithTasks(int $id): ?Project
    {
        return $this->createQueryBuilder('p')
            ->select('p', 't')
            ->leftJoin('p.tasks', 't')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function findProjectsWithRecentTasks(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p', 't')
            ->innerJoin('p.tasks', 't')
            ->andWhere('t.createdAt >= :weekAgo')
            ->setParameter('weekAgo', new \DateTimeImmutable('-1 week'))
            ->orderBy('p.name', 'ASC')
            ->addOrderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getProjectTaskSummary(): array
    {
        return $this->createQueryBuilder('p')
            ->select([
                'p.id',
                'p.name',
                'COUNT(t.id) as TotalTasks',
                'SUM(CASE WHEN t.status = :completed THEN 1 ELSE 0 END) as completedTasks'
            ])
            ->leftJoin('p.tasks', 't')
            ->setParameter('completed', Task::STATUS_COMPLETED)
            ->groupBy('p.id')
            ->getQuery()
            ->getResult();
    }

    public function findProjectsByStatusWithTaskCount(string $status): array
    {
        return $this->createQueryBuilder('p')
            ->select(['p', 'COUNT(t.id) as tasksCount'])
            ->andWhere('p.status = :status')
            ->setParameter('status', $status)
            ->groupBy('p.id')
            ->leftJoin('p.tasks', 't')
            ->getQuery()
            ->getResult();
    }
}
