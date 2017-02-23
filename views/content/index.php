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

        var gapWidth = <?php echo Config::inst()->gallery['gap'] ?>;

        function align() {
            var containerWidth = $("#img_container").width();

            var rowWidth = 0;
            var rowImgWidths = [];

            $("#img_container .image img").each(function (index, elem) {
                rowWidth += elem.width;
                rowImgWidths.push(elem.width);
                if (rowWidth >= containerWidth) {
                    elem.parent().addClass("last");

                    var gapWidths = (rowImgWidths.length - 1) * gapWidth;
                    var allImagesWidths = containerWidth - gapWidth;

                    rowWidth = 0;
                    rowImgWidths = [];
                } else {
                    elem.parent().removeClass("last");
                    rowWidth += gapWidth;
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