<?php

namespace app\models;

use app\core\EntityModel;

/**
 * Author модель автора.
 *
 * @package app\models
 */
class Author extends EntityModel
{
    public function entityName()
    {
        return '\\Entities\\Author';
    }

    public function getAuthor($id)
    {
        $record = $this->find($id);
        return $record;
    }

    public function updateAuthor($id, $data)
    {
        if ($record = $this->find($id))
        {
            $record->setFirstName($data['first_name']);
            $record->setLastName($data['last_name']);
            $record->setBirthday(date('Y-m-d H:i:s', $data['birthday']));
            $this->saveToDb($record);
        }
    }

    public function restoreAuthor($id)
    {
        $record = $this->find($id);
        $record->setDeleted(null);
        $this->saveToDb($record);
    }

    public function removeAuthor($id)
    {
        if ($record = $this->find($id))
        {
            $record->setDeleted(date('Y-m-d H:i:s'));
            $this->saveToDb($record);
        }
    }

    public function addAuthor($data)
    {
        $sql = 'INSERT INTO author SET first_name = :first_name, last_name = :last_name, birthday = :birthday, id = (SELECT IF(MAX(id) IS NULL, 1, MAX(id) + 1) FROM author t)';
        $stmt = $this->queryBuilder->getConnection()->prepare($sql);
        $stmt->bindValue(':first_name', $data['first_name']);
        $stmt->bindValue(':last_name', $data['last_name']);
        //$stmt->bindValue(':birthday', date('Y-m-d', $data['birthday']));
        $stmt->bindValue(':birthday', $data['birthday']->format('Y-m-d'));
        return $stmt->execute();
    }

    public function getAuthors()
    {
        $res = $this->findAll();
        return $res;
    }
}
