<?php
/* @var $this app\core\View */
/* @var $values \app\core\Container */

use app\helpers\Html;
use app\core\Config;

$this->title = Html::createTitle('главная страница');
?>
<script type="text/javascript">
    // <![CDATA[
    $(document).ready(function () {
        $('#book_list').DataTable(app.dataTableInfo);
    });
    // ]]>
</script>

<form enctype="multipart/form-data" method="post" class="upload_file">
    <input type="file" name="file">
    <input type="submit" value="ОТПРАВИТЬ">
</form>

<div>
    <?php foreach ($values->images as $img): ?>
        <div class="image">
            <img alt="" src="<?php Html::mkLnk('/?action=image&src=' . urlencode($img)) ?>">
        </div>
    <?php endforeach; ?>
</div>