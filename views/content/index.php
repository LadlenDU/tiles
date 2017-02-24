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

        var resize = true;

        function align() {
            if (resize) {
                resize = false;
                var containerWidth = $("#img_container").width();
                //alert(containerWidth);

                var rowWidth = 0;
                var rowImages = [];

                var newWidths = [];

                $("#img_container .image .shrink-wrap img").each(function (index, elem) {
                    elem = $(elem);
                    var elWrapper = elem.parents(".image");
                    rowWidth += elem.width();
                    rowImages.push(elem);
                    if (rowWidth >= containerWidth) {
                        elWrapper.addClass("last");

                        var gapWidths = (rowImages.length - 1) * gap;
                        var allImagesWidths = containerWidth - gapWidths;

                        var ratio = allImagesWidths / rowWidth;

                        var allW = 0;

                        /*$.each(rowImages, function (index, elem) {
                            var elWrapper = elem.parents(".image");

                            if (index < rowImages.length - 1) {
                                allW += ceil(elem.width() * ratio);
                                newWidths.push((ceil(elem.width() * ratio));
                            } else {
                                newWidths.push(allImagesWidths - allW);
                            }
                        });

                        $.each(rowImages, function (index, elem) {
                            var elWrapper = elem.parents(".image");
                            elWrapper.width(newWidths[index]);
                        });*/

                        $.each(rowImages, function (index, elem) {
                            var elWrapper = elem.parents(".image");

                            if (index < rowImages.length - 1) {
                                allW += Math.ceil(elem.width() * ratio);
                                elWrapper.width(Math.ceil(elem.width() * ratio));
                            } else {
                                elWrapper.width(allImagesWidths - allW);
                            }
                        });

                        rowWidth = 0;
                        rowImages = [];
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