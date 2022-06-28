<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }

    /**
     * @return User[] Returns an array of User objects
     */
    public function getCustomers(): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere("JSON_CONTAINS(u.roles, :role) = 1")
            ->setParameter('role', '"ROLE_CUSTOMER"')
            ->orderBy('u.id', 'desc')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return User[] Returns an array of User objects
     */
    public function search($words = null): array
    {

        $query = $this->createQueryBuilder('u');
        if (str_contains($words, '@')) {
            $query->andWhere("u.email LIKE :email")
                ->setParameter('email', "%" . $words . "%");
        } else if ($words != null) {
            $query->andWhere("MATCH_AGAINST(u.email, u.firstName, u.lastName) AGAINST (:words boolean)>0")
                ->setParameter('words', $words);
        }
        $query->orderBy('u.id', 'desc');
        return $query->getQuery()
            ->getResult();
    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
