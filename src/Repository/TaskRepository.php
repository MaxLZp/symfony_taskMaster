<?php

namespace App\Repository;

use App\Entity\Task;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findByProject(int $projectId): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.project = prjectId')
            ->setParameter('prjoectId', $projectId)
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOverdueTasks(): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.dueDate < :now')
            ->andWhere('t.status != :completed')
            ->setParameter('now', new DateTimeImmutable())
            ->setParameter('completed', Task::STATUS_COMPLETED)
            ->orderBy('t.dueDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findTasksDueWithin(int $days): array
    {
        $futureDate = new DateTimeImmutable("+{$days} days");
        return $this->createQueryBuilder('t')
            ->andWhere('dueDate', '<=', ':now')
            ->andWhere('dueDate', '>=', ':now')
            ->andWhere('t.status != :completed')
            ->setParameter('now', $futureDate)
            ->setParameter('completed', Task::STATUS_COMPLETED)
            ->orderBy('t.createdDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getTaskStatistics(): array
    {
        $qb = $this->createQueryBuilder('t');
        
        return $qb
            ->select([
                'COUNT(t.id) as total',
                'SUM(CASE WHEN t.status = :completed THEN 1 ELSE 0 END) as completed',
                'SUM(CASE WHEN t.status = :inProgress THEN 1 ELSE 0 END) as inProgress',
                'SUM(CASE WHEN t.dueDate < :now AND t.status != :completed THEN 1 ELSE 0 END) as overdue'
            ])
            ->setParameter('completed', Task::STATUS_COMPLETED)
            ->setParameter('inProgress', Task::STATUS_IN_PROGRESS)
            ->setParameter('now', new \DateTimeImmutable())
            ->getQuery()
            ->getSingleResult();
    }
}
