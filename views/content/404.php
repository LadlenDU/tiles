<?php
/* @var $this app\core\View */

use app\helpers\Html;

$this->title = Html::createTitle(_('страница не найдена'));
?>
<div class="global_message"><?php Html::_h('Ошибка 404. Страница не найдена.') ?>
    <br>
    <a href="<?php Html::mkLnk('/') ?>"><?php Html::_h('Домашняя страница.') ?></a>
</div>