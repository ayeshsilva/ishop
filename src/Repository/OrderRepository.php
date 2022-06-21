<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function add(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Order[] Returns an array of Order objects
     */
    public function findbyUserOrder( Order $order): array
    {
        return $this->createQueryBuilder('o')
            ->select('o, u, pq, p')
            ->join('o.productQuantities', 'pq')
            ->join('o.user', 'u')
            ->join('pq.product', 'p')
            ->andWhere('o.id = pq.orders')
            ->andWhere('o.id = :id' )
            ->andWhere('u.id = :user' )
            ->setParameter('id', $order->getId())
            ->setParameter('user', $order->getUser()->getId())
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByEmailCustomerOrder(User $user)
    {
        return $this->createQueryBuilder('o')
            //->select('*')
            ->join('o.user', 'u')
            ->andWhere('u.id = :user')
            ->setParameter('user', $user->getId())
            ->getQuery()
            ->getResult()
            ;
    }

//    public function findOneBySomeField($value): ?Order
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
