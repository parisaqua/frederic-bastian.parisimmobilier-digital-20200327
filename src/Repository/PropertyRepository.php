<?php

namespace App\Repository;

use Doctrine\ORM\Query;
use App\Entity\Property;
use App\Entity\PropertySearch;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    /**
     * Liste de tous les biens triés par date de création pour admin
     *
     * @return Property
     */
    
     /**
      * Query pour paginator dans la page d'admin des biens
      *
      * @return Query
      */
     public function findAllQuery(): Query {
        return $this->findAll()
        ->getQuery()
    ;
    }

    /**
      * Query pour paginator dans la page d'admin de mes biens
      *
      * @return Query
      */
      public function findAllMYQuery(UserInterface $user): Query {
        
        return $this->findAll('p')
             ->andWhere('p.manager = :val')
             ->setParameter('val', $user)
             ->getQuery()
     ;
     }
    
    /**
     * Query pour trier tous les biens par date de création
     *
     * @return QueryBuilder
     */ 
    public function findAll(): QueryBuilder {
        return $this->createQueryBuilder('p')
        ->orderBy('p.updated_at', 'DESC')
        
    ;
    }

    /**
     * Liste des derniers biens disponibles
     * 
     * @return Property[]
     */
    public function findLatest(): array
    {
        return $this->findVisibleQuery()
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Liste des biens disponibles
     *
     * @return Query
     */
    public function findAllVisibleQuery(PropertySearch $search): Query {
        
        $query = $this->findVisibleQuery();

        if($search->getMaxSurface()) {
            $query = $query
                ->andWhere('p.surface <= :maxSurface')
                ->setParameter('maxSurface', $search->getMaxSurface());
        }

        if($search->getMinSurface()) {
            $query = $query
                ->andWhere('p.surface >= :minSurface')
                ->setParameter('minSurface', $search->getMinSurface());
        }

        if ($search->getLat() && $search->getLng() && $search->getDistance()) {
            $query = $query
                ->select('p')
                ->andWhere('(6353 * 2 * ASIN(SQRT( POWER(SIN((p.lat - :lat) *  pi()/180 / 2), 2) +COS(p.lat * pi()/180) * COS(:lat * pi()/180) * POWER(SIN((p.lng - :lng) * pi()/180 / 2), 2) ))) <= :distance')
                ->setParameter('lng', $search->getLng())
                ->setParameter('lat', $search->getLat())
                ->setParameter('distance', $search->getDistance());
        }

        if($search->getOptions()->count() > 0) {
            $k = 0;
            foreach($search->getOptions() as $option) {
                $k++;
                $query = $query
                    ->andWhere(":option$k MEMBER OF p.options")
                    ->setParameter("option$k", $option);
            }
        }

        return $query->getQuery();
    }

    /**
     * Undocumented function
     *
     * @return QueryBuilder
     */
    public function findVisibleQuery(): QueryBuilder {
        return $this->createQueryBuilder('p')
        // ->select('p', 'pics')
        // ->leftJoin('p.pictures', 'pics')
        ->andWhere('p.sold = false')
        ->orderBy('p.updated_at', 'DESC');
    }
    
    
    // /**
    //  * @return Property[] Returns an array of Property objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Property
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
