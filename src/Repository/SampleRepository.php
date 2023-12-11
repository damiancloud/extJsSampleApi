<?php

namespace App\Repository;

use App\Entity\Sample;
use App\Enum\SampleStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sample>
 *
 * @method Sample|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sample|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sample[]    findAll()
 * @method Sample[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SampleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sample::class);
    }
    public function findByStatus(string $status): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.status = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getResult();
    }

    public function findByHistoryStatus(string $status): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT s
            FROM App\Entity\Sample s
            INNER JOIN s.history h
            WITH h.sample = s.id
            WHERE h.status = :status'
        )->setParameter('status', $status);

        return $query->getArrayResult();
    }

    public function findByDate(string $date, string $dateType): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.' . $dateType . '= :date')
            ->setParameter('date', new \DateTime($date))
            ->getQuery()
            ->getResult();
    }

    public function findByName(string $name): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getResult();
    }
}
