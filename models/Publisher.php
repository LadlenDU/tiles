<?php

namespace app\models;

use app\core\EntityModel;

/**
 * Publisher модель издательства.
 *
 * @package app\models
 */
class Publisher extends EntityModel
{
    public function entityName()
    {
        return '\\Entities\\Publisher';
    }

    public function getPublisher($id)
    {
        $res = $this->createQueryBuilder('p')
            ->select('p.name')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        return $res;
    }

    public function updatePublisher($id, $name)
    {
        return $this->queryBuilder
            ->update('publisher')
            ->set('name', ':name')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->setParameter('name', $name)
            ->execute();
    }

    public function restorePublisher($id)
    {
        $record = $this->find($id);
        $record->setDeleted(null);
        $this->getEntityManager()->persist($record);
        $this->getEntityManager()->flush();
    }

    public function removePublisher($id)
    {
        $this->queryBuilder
            ->update('publisher')
            ->set('deleted', ':deleted')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->setParameter('deleted', date('Y-m-d H:i:s'))
            ->execute();
    }

    public function addPublisher($name)
    {
        /*$em = $this->getEntityManager();


        $em->select
        $qb->expr()->in(
            'o.id',
            $qb2->select('o2.id')
                ->from('Order', 'o2')
                ->join('Item',
                    'i2',
                    \Doctrine\ORM\Query\Expr\Join::WITH,
                    $qb2->expr()->andX(
                        $qb2->expr()->eq('i2.order', 'o2'),
                        $qb2->expr()->eq('i2.id', '?1')
                    )
                )
                ->getDQL()
        )*/

        //SELECT IF(MAX(field_to_increment) IS NULL, 1, MAX(field_to_increment) + 1) FROM table t)

        #$sql = 'INSERT INTO publisher SET name = :name';
        $sql = 'INSERT INTO publisher SET name = :name, id = (SELECT IF(MAX(id) IS NULL, 1, MAX(id) + 1) FROM publisher t)';
        $stmt = $this->queryBuilder->getConnection()->prepare($sql);
        $stmt->bindValue(':name', $name);
        $result = $stmt->execute();

        /*$this->queryBuilder
            ->insert('publisher')
            ->values(
                array(
                    #'name' => ':name',
                    'name' => '?',
                )
            )
            #->setParameter('name', $name)
            ->setParameter(0, $name);*/

        /*        $qb = $this->createQueryBuilder('p');
                $qb->ins
                $ttt = $qb->select('IF(MAX(p.id) IS NULL, 1, MAX(p.id) + 1)')
                ->getDQL();*/

        /*->where('u.id = :id')
        ->setParameter('id', $uId)
        ->getQuery()
        ->getOneOrNullResult();*/

        /*$publisher = new \Entities\Publisher();
        $publisher->setName($name);
        $this->getEntityManager()->persist($publisher);
        $this->getEntityManager()->flush();

        $qb = $this->createQueryBuilder('p')->i*/
    }

    public function getPublishers()
    {
        $res = $this->findAll();
        return $res;
    }
}
