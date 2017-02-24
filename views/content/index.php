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
        var height = <?php echo Config::inst()->gallery['image_max_height'] ?>;

        var resize = true;

        function align() {
            if (resize) {
                resize = false;
                var containerWidth = $("#img_container").width();
                //alert(containerWidth);

                var rowWidth = 0;
                var imgRowWidth = 0;
                var rowImages = [];

                var row = 0;

                $("#img_container .image .shrink-wrap img").each(function (index, elem) {
                    elem = $(elem);
                    var elWrapper = elem.parents(".image");
                    rowWidth += elem.width();
                    imgRowWidth += elem.width();

                    //elWrapper.css("clear", (rowImages.length == 0) ? "left" : "none");

                    rowImages.push(elem);
                    if (rowWidth >= containerWidth) {
                        $("#img_container").height((row + 1) * (height + gap));

                        elWrapper.addClass("last");

                        var gapWidths = (rowImages.length - 1) * gap;
                        var allImagesWidths = containerWidth - gapWidths;

                        var ratio = allImagesWidths / imgRowWidth;

                        var allW = 0;

                        var left = 0;

                        $.each(rowImages, function (index, elem) {
                            var elWrapper = elem.parents(".image");

                            elWrapper.css("top", (row * (height + gap)) + "px");

                            var width = (index < rowImages.length - 1) ? Math.ceil(elem.width() * ratio) : (allImagesWidths - allW);
                            allW += width;
                            elWrapper.width(width);

                            elWrapper.css("left", left + "px");
                            left += width + gap;
                        });

                        rowWidth = 0;
                        imgRowWidth = 0;
                        rowImages = [];
                        row++;
                    } else {
                        elWrapper.removeClass("last");
                        rowWidth += gap;
                    }
                });
                resize = true;
            }
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
    <?php foreach ($values->images as $img): ?><div class="image"><div class="shrink-wrap"><img alt="" src="<?php Html::mkLnk('/?action=image&src=' . urlencode($img)) ?>"></div></div><?php endforeach; ?>
</div>