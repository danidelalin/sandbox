<?php

namespace Undf\LocationDataBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CityRepository extends EntityRepository
{

    public function findCitiesByProvince($province_id, array $orderBy = null, $limit = null, $offset = null)
    {
        $criteria = array('province' => $province_id);
        return $this->findBy($criteria);
    }

    public function findNearestCity($latitude, $longitude)
    {
        $minDistanceQuery = $this->createQueryBuilder('m')
                ->select('MIN(DISTANCE(:latitude,:longitude,m.latitude,m.longitude))')
                ->getDQL();

        $query = $this->createQueryBuilder('c')
                ->select('c')
                ->where("DISTANCE(:latitude,:longitude,c.latitude,c.longitude) = ($minDistanceQuery)")
                ->setParameter('latitude', $latitude)
                ->setParameter('longitude', $longitude)
                ->getQuery();
        try {
            return $query->getSingleResult();
        } catch (NoResultException $e) {
            return false;
        }
    }

    public function findAll()
    {
        $query = $this->createQueryBuilder('c')
                ->getQuery();
        $query->useResultCache(true, 3600, 'globals_cities');
        return $query->getResult();
    }

}
