<?php

namespace App\Repository;

use App\Entity\Meal;
use App\Entity\User;
use App\Entity\MealUser;
use Doctrine\ORM\Query\Expr\Join;
use App\Controller\DiaryController;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<MealUser>
 *
 * @method MealUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method MealUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method MealUser[]    findAll()
 * @method MealUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MealUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MealUser::class);
    }

    public function save(MealUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MealUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function mealSearch(?string $searchedValue, User $user, ?string $type, bool $favourite, ?string $origin): array
    {
        $queryBuilder = $this->createQueryBuilder('mu');
        $queryBuilder
            ->where('mu.user = :user_id')
            ->setParameter('user_id', $user->getId());
        if ($searchedValue || $type || $origin || $favourite === true) {
            $queryBuilder->join('mu.meal', 'm');
        }
        if ($searchedValue) {
            $queryBuilder
                ->andWhere('m.name LIKE :searchedValue')
                ->setParameter('searchedValue', '%' . $searchedValue . '%');
        }
        if ($type) {
            $queryBuilder
                ->andWhere('m.type = :type')
                ->setParameter('type', $type);
        }
        if ($favourite === true) {
            $queryBuilder
                ->andWhere('m.isFavourite = :favourite')
                ->setParameter('favourite', $favourite);
        }
        if ($origin) {
            $queryBuilder
                ->andWhere('m.origin = :origin')
                ->setParameter('origin', $origin);
        }
        $queryBuilder
            ->orderBy('mu.date', 'DESC')
            ->setMaxResults(DiaryController::MEAL_LIMIT);

        return $queryBuilder->getQuery()
            ->getResult();
    }

    public function recipeSearch(?string $searchedValue, User $user, ?string $type, bool $favourite, ?string $origin): array
    {
        $queryBuilder = $this->createQueryBuilder('mu');
        $queryBuilder
            ->where('mu.user = :user_id')
            ->setParameter('user_id', $user->getId())
            ->join('mu.meal', 'm');
        if ($searchedValue) {
            $queryBuilder
                ->andWhere('m.name LIKE :searchedValue')
                ->setParameter('searchedValue', '%' . $searchedValue . '%');
        }
        if ($type) {
            $queryBuilder
                ->andWhere('m.type = :type')
                ->setParameter('type', $type);
        }
        if ($favourite === true) {
            $queryBuilder
                ->andWhere('m.isFavourite = :favourite')
                ->setParameter('favourite', $favourite);
        }
        if ($origin) {
            $queryBuilder
                ->andWhere('m.origin = :origin')
                ->setParameter('origin', $origin);
        }
        $queryBuilder
            ->andWhere('m.isRecipe = :isRecipe')
            ->setParameter('isRecipe', true)
            ->orderBy('mu.date', 'DESC')
            ->setMaxResults(DiaryController::MEAL_LIMIT);

        return $queryBuilder->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return MealUser[] Returns an array of MealUser objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?MealUser
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
