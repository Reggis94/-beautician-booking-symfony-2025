<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Service>
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    public function findAllByBusiness(array $criterias = []){
        dump($criterias['business']);
        return $this->getEntityManager()->createQueryBuilder()->select('s')
            ->from(Service::class, 's')
            ->andWhere('s.business = :business')
            ->setParameter('business', $criterias['business'])
            ->getQuery()
            ->getResult();
    }

    public function findAllNotDeletedLastVersionByBusiness(array $criterias = []){
        dump($criterias['business']);
        $services = $this->getEntityManager()->createQueryBuilder()->select('s')
            ->from(Service::class, 's')
            ->andWhere('s.business = :business')
            ->andWhere('s.deletedAt IS NULL')
            ->andWhere('s.original IS NULL')
            ->setParameter('business', $criterias['business'])
            ->getQuery()
            ->getResult();

        $servicesLatestVersion = [];

        foreach($services as $service){
            $servicesLatestVersion[] = $this->findLatestVersion($service);
        }
        return $servicesLatestVersion;
    }

    public function findLatestVersion(Service $service){
        $latestChildren = $this->getEntityManager()->createQueryBuilder()->select('s')
            ->from(Service::class, 's')
            ->where('s.original = :original')
            ->setParameter('original', $service)
            ->orderBy('s.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

            if($latestChildren){
                return $latestChildren;
            }

            $original = $this->find($service->getId());
            return $original;
    }

    //    /**
    //     * @return Service[] Returns an array of Service objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Service
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
