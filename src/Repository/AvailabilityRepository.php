<?php

namespace App\Repository;

use App\Entity\Availability;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Statement;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Availability>
 */
class AvailabilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Availability::class);
    }

    //Native SQL query to get availabilty of business on a specific day
    public function findAvailabilityByDayNameAndBusinessId(string $dayName, int $businessId){
        
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT a.start_time, a.end_time, a.interval_minutes 
            FROM availability AS a 
            INNER JOIN day d ON d.id = a.day_id 
            INNER JOIN business b ON b.id = a.business_id 
            WHERE d.name = :dayName AND b.id = :businessId;
        ';
        $stmt = $conn->executeQuery($sql, ['dayName' => $dayName, 'businessId' => $businessId]);
        return $stmt->fetchAllAssociative();
    }


    //    /**
    //     * @return Availability[] Returns an array of Availability objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Availability
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
