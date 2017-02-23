<?php

namespace app\core;

/**
 * IFormModel интерфейс для моделей, обслуживающих визуальные формы.
 *
 * @package app\base
 */
interface IFormModel
{
    /**
     * Возвращает названия полей.
     *
     * @return array
     */
    public static function attributeLabels();

}