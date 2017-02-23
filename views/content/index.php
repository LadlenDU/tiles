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

        var gap = <?php echo Config::inst()->gallery['gap'] ?>;

        function align() {
            var rowWidth = 0;
            var containerWidth = $("#img_container").width();
            $("#img_container .image img").each(function (index, elem) {
                rowWidth += elem.width + gap;
                if (rowWidth > containerWidth) {
                    
                }
            });
        }

        //align();

        $(window).resize(function () {
            align();
        });

        $("#img_container .image img").load(function () {
            align();
        });
    });
    // ]]>
</script>

<form enctype="multipart/form-data" method="post" class="upload_file">
    <input type="file" name="file">
    <input type="submit" value="ОТПРАВИТЬ">
</form>

<div id="img_container">
    <?php foreach ($values->images as $img): ?>
        <div class="image">
            <img alt="" src="<?php Html::mkLnk('/?action=image&src=' . urlencode($img)) ?>">
        </div>
    <?php endforeach; ?>
</div>