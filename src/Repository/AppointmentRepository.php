<?php

namespace App\Repository;

use App\Entity\Appointment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Statement;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appointment>
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    public function findPastByBusiness(array $criteria){
        $currentDateTime = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT a.id
            FROM appointment a
            WHERE a.business_id = :businessId
            AND (
                (a.duration_minutes IS NOT NULL AND NOW() >= a.start_date_time_utc + (a.duration_minutes * INTERVAL \'1 minute\'))
                OR (a.duration_minutes IS NULL AND NOW() >= a.start_date_time_utc)
            );
        ';

        $stmt = $conn->executeQuery($sql, ['currentDateTime' => $currentDateTime->format('Y-m-d H:i:s'), 'businessId' => $criteria['business']->getId()]); 
        // dump($stmt->fetchAllAssociative());
        $appointmentsFetchAssoc = $stmt->fetchAllAssociative();

        $appointments = [];
        foreach($appointmentsFetchAssoc as $appointment){
            $a = $this->find($appointment['id']);
            $appointments[] = $a;
        }

        if($appointments){
            dump($appointments[0]->getId());
        };
        return $appointments;
    }

    public function findUpcomingByBusiness(array $criteria): array
    {
        // Get the current datetime in UTC
        $currentDateTime = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

        $conn = $this->getEntityManager()->getConnection();

        //POSTGRESQL
        $sql = '
            SELECT a.id
            FROM appointment a
            WHERE a.business_id = :businessId
            AND (
                (a.duration_minutes IS NOT NULL AND NOW() < a.start_date_time_utc + (a.duration_minutes * INTERVAL \'1 minute\'))
                OR (a.duration_minutes IS NULL AND NOW() < a.start_date_time_utc)
            );
        ';

        //MYSQL
        // $sql = '
        //     SELECT b.id AS business_id, a.id AS appointment_id, s.id AS service_id, a.start_date_time_utc 
        //     FROM appointment a 
        //     INNER JOIN appointment_service aps ON a.id = aps.appointment_id 
        //     INNER JOIN service s ON aps.service_id = s.id 
        //     INNER JOIN business b ON s.business_id = b.id 
        //     WHERE :currentDateTime < DATE_ADD(a.start_date_time_utc, INTERVAL a.duration_minutes MINUTE)
        //     AND b.id = :businessId
        //     ORDER BY a.start_date_time_utc ASC;
        // ';
        // dump($currentDateTime->format('Y-m-d H:i:s'), $criteria['business']->getId());
        // exit;
        $stmt = $conn->executeQuery($sql, ['currentDateTime' => $currentDateTime->format('Y-m-d H:i:s'), 'businessId' => $criteria['business']->getId()]); 
        // dump($stmt->fetchAllAssociative());
        $upcomingAppointmentsFetchAssoc = $stmt->fetchAllAssociative();

        $upcomingAppointments = [];
        foreach($upcomingAppointmentsFetchAssoc as $appointment){
            $a = $this->find($appointment['id']);
            $upcomingAppointments[] = $a;
        }

        if($upcomingAppointments){
            dump($upcomingAppointments[0]->getId());
        };
        return $upcomingAppointments;
    }

    // To use when groups will be used to get the correct values to be displayed
    // public function findUpcomingByBusiness(array $criteria): array
    // {
    //     // Get the current datetime in UTC
    //     $currentDateTime = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

    //     $conn = $this->getEntityManager()->getConnection();
    //     $sql = '
    //         SELECT b.id AS business_id, a.id AS appointment_id, s.id AS service_id, a.start_date_time_utc 
    //         FROM appointment a 
    //         INNER JOIN appointment_service aps ON a.id = aps.appointment_id 
    //         INNER JOIN service s ON aps.service_id = s.id 
    //         INNER JOIN business b ON s.business_id = b.id 
    //         WHERE :currentDateTime < DATE_ADD(a.start_date_time_utc, INTERVAL a.duration_minutes MINUTE)
    //         AND b.id = :businessId
    //         ORDER BY a.start_date_time_utc ASC;
    //     ';
    //     $stmt = $conn->executeQuery($sql, ['currentDateTime' => $currentDateTime->format('Y-m-d H:i:s'), 'businessId' => $criteria['business']->getId()]); 
    //     $appointmentIds = array_column($stmt->fetchAllAssociative(), 'appointment_id');

    //     return $this->findBy(['id' => $appointmentIds]);
    // }

    // public function findPastByBusiness($array $criteria): array
    // {
    //     $currentDateTime
    // }

    //    /**
    //     * @return Appointment[] Returns an array of Appointment objects
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

    //    public function findOneBySomeField($value): ?Appointment
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
