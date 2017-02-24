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

        var images = [];

        $('.upload_file').submit(function () {
            var file_data = $('.upload_file [name="file"]').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);
            $.showLoading();
            $.ajax({
                url: "<?php Html::mkLnk('/?action=loadImages') ?>",
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function (data) {
                    for (var ind in data) {
                        if ($.inArray(data[ind], images) == -1) {
                            images.push(data[ind]);
                            var url = encodeURIComponent(data[ind]);
                            var s = '<div class="image"><div class="shrink-wrap">\
                            <img alt="" src="<?php Html::mkLnk('/?action=image&src=') ?>' + url + '">\
                            </div></div>';

                            var newObj = $(s);
                            $(s).find("img").load(function () {
                                align();
                            });

                            $("#img_container").append(newObj);
                        }
                    }
                    align();
                }
            }).always(function () {
                $.hideLoading();
            });
            return false;
        });

        var gap = <?php echo Config::inst()->gallery['gap'] ?>;
        var height = <?php echo Config::inst()->gallery['image_max_height'] ?>;

        function align() {
            var containerWidth = $("#img_container").width();

            var rowWidth = 0;
            var imgRowWidth = 0;
            var rowImages = [];

            var row = 0;

            $("#img_container .image .shrink-wrap img").each(function (index, elem) {
                elem = $(elem);
                var elWrapper = elem.parents(".image");

                var left = 0;
                var top = row * (height + gap);

                rowWidth += elem.width();
                imgRowWidth += elem.width();

                rowImages.push(elem);
                if (rowWidth >= containerWidth) {
                    //$("#img_container").height((row + 1) * (height + gap));

                    elWrapper.addClass("wrap");

                    var gapWidths = (rowImages.length - 1) * gap;
                    var allImagesWidths = containerWidth - gapWidths;

                    var ratio = allImagesWidths / imgRowWidth;

                    $.each(rowImages, function (index, elem) {
                        var elWrapper = elem.parents(".image");

                        elWrapper.css("top", top + "px");

                        var width = (index != rowImages.length - 1)
                            ? Math.ceil(elem.width() * ratio)
                            : (containerWidth - left);

                        elWrapper.width(width);

                        elWrapper.css("left", left + "px");
                        left += width + gap;
                    });

                    rowWidth = 0;
                    imgRowWidth = 0;
                    rowImages = [];
                    row++;
                } else {
                    elWrapper.removeClass("wrap");
                    rowWidth += gap;

                    if ($("#img_container .image .shrink-wrap img").length - 1 == index) {
                        $.each(rowImages, function (index, elem) {
                            var elWrapper = elem.parents(".image");
                            elWrapper.css("top", top + "px");
                            var width = elem.width();
                            elWrapper.width(width);
                            elWrapper.css("left", left + "px");
                            left += width + gap;
                        });
                    }
                }
            });
        }

        align();

        $(window).resize(function () {
            align();
        });

        $("#img_container .image img").load(function () {
            align();
        });
    });
    // ]]>
</script>

Загрузить файл со ссылками на изображения:<br>
<form enctype="multipart/form-data" method="post" class="upload_file">
    <input type="file" name="file">
    <input type="submit" value="Загрузить">
</form>

<div id="img_container">
    <?php foreach ($values->images as $img): ?>
        <div class="image">
            <div class="shrink-wrap">
                <img alt="" src="<?php Html::mkLnk('/?action=image&src=' . urlencode($img)) ?>">
            </div>
        </div>
    <?php endforeach; ?>
</div>