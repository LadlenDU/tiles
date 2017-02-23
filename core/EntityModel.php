<?php

namespace app\core;

use app\helpers\Db;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\DBAL\DriverManager;

abstract class EntityModel extends EntityRepository
{
    protected $queryBuilder;

    public function __construct()
    {
        $conn = DriverManager::getConnection(Config::inst()->database['connection']);
        $this->queryBuilder = $conn->createQueryBuilder();

        $metadata = new ClassMetadata($this->entityName());
        parent::__construct(Db::em(), $metadata);
    }

    protected function saveToDb($record)
    {
        $this->getEntityManager()->persist($record);
        $this->getEntityManager()->flush();
    }

    /**
     * Возвращает название сущности БД.
     *
     * @return string
     */
    abstract public function entityName();
}