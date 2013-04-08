<?php

namespace Undf\LocationDataBundle\Entity;

use Doctrine\ORM\EntityRepository;


class CountryRepository extends EntityRepository
{
    function findCitiesByProvince($province_id, array $orderBy = null, $limit = null, $offset = null)
    {
        $criteria = array('province' => $province_id);
        return $this->findBy($criteria);
    }

    function findAsArray($country_id) {
        $dql = "SELECT c, p, ct
                FROM Undf\DataLocationBundle\Entity\Country c
                LEFT JOIN c.provinces p
                LEFT JOIN c.cities ct
                WHERE c.id = $country_id";

        $query = $this->getEntityManager()->createQuery($dql);
        return $query->getArrayResult();

    }
}
