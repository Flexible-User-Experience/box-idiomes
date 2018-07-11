<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Tariff;
use AppBundle\Enum\TariffTypeEnum;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * Class TariffRepository.
 *
 * @category Repository
 */
class TariffRepository extends EntityRepository
{
    /**
     * @return QueryBuilder
     */
    public function findAllSortedByYearAndPriceQB()
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.year', 'DESC')
            ->addOrderBy('t.price', 'ASC')
        ;
    }

    /**
     * @return Query
     */
    public function findAllSortedByYearAndPriceQ()
    {
        return $this->findAllSortedByYearAndPriceQB()->getQuery();
    }

    /**
     * @return array
     */
    public function findAllSortedByYearAndPrice()
    {
        return $this->findAllSortedByYearAndPriceQ()->getResult();
    }

    /**
     * @return QueryBuilder
     */
    public function findCurrentPrivateLessonTariffQB()
    {
        return $this->createQueryBuilder('t')
            ->where('t.type = :type')
            ->setParameter('type', TariffTypeEnum::TARIFF_PRIVATE_LESSON_PER_HOUR)
            ->orderBy('t.year', 'DESC')
            ->setMaxResults(1)
        ;
    }

    /**
     * @return Query
     */
    public function findCurrentPrivateLessonTariffQ()
    {
        return $this->findCurrentPrivateLessonTariffQB()->getQuery();
    }

    /**
     * @return Tariff
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findCurrentPrivateLessonTariff()
    {
        $result = $this->findCurrentPrivateLessonTariffQ()->getOneOrNullResult();

        if (is_null($result)) {
            $today = new \DateTime();
            $result = new Tariff();
            $result
                ->setName('default empty tariff')
                ->setYear(intval($today->format('Y')))
                ->setType(TariffTypeEnum::TARIFF_PRIVATE_LESSON_PER_HOUR)
                ->setPrice(0)
            ;
        }

        return $result;
    }
}
