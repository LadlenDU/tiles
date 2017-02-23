<?php

namespace app\core;

/**
 * IDatabase интерфейс для работы с БД.
 *
 * @package app\base
 */
interface IDatabase
{
    /**
     * Запрос выбирающий данные.
     *
     * @param string $sql строка запроса SQL
     * @param string[] $values массив значений для подстановки в $sql
     * @return string[] массив с результатами
     */
    public function rawSelectQuery($sql, $values);

    /**
     * Запрос SQL.
     *
     * @param string $sql строка запроса SQL
     * @param string[] $values массив значений для подстановки в $sql
     * @param string[] $valuesBlob массив, где ключ - имя поля, значение - путь к файлу для сохранения в BLOB
     * @return bool true при успехе или false в случае ошибки
     */
    public function rawQuery($sql, $values, $valuesBlob);

    /**
     * Экранирование специальных символов для значений.
     *
     * @param string $val Строка для экранирования.
     * @return string
     */
    public function quote($val);

    /**
     * Экранирование специальных символов для названий (таблиц, столбцов).
     *
     * @param string $name Строка для экранирования.
     * @return string
     */
    public function quoteName($name);

    /**
     * Возвращает идентификатор последней операции по вставке.
     *
     * @return mixed
     */
    public function lastInsertId();

    /**
     * Старт транзакции.
     *
     * @return bool признак успешности операции
     */
    public function beginTransaction();

    /**
     * Конец транзакции.
     *
     * @return bool признак успешности операции
     */
    public function commitTransaction();

    /**
     * Откат транзакции.
     *
     * @return bool признак успешности операции
     */
    public function rollbackTransaction();

    /**
     * Возвращает максимальные длины текстовых колонок в таблице.
     *
     * @param string $table название таблицы
     * @param string[] $columns названия колонок
     * @return string[] длина колонок (в символах)
     */
    public function getTextColumnMaximumLength($table, $columns);
}
