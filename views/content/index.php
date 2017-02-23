<?php
/* @var $this app\core\View */

use app\helpers\Html;

$this->title = Html::createTitle('главная страница');
?>
<script type="text/javascript">
    // <![CDATA[
    $(document).ready(function () {
        $('#book_list').DataTable(app.dataTableInfo);
    });
    // ]]>
</script>
<table id="book_list" class="display" width="100%" cellspacing="0">
    <thead>
    <tr>
        <th>Название</th>
        <th>Автор</th>
        <th>Издательство</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>Название</th>
        <th>Автор</th>
        <th>Издательство</th>
    </tr>
    </tfoot>
    <tbody>
    <!--<tr>
        <td>Tiger Nixon</td>
        <td>System Architect</td>
        <td>Edinburgh</td>
        <td>61</td>
        <td>2011/04/25</td>
        <td>$320,800</td>
    </tr>
    <tr>
        <td>Garrett Winters</td>
        <td>Accountant</td>
        <td>Tokyo</td>
        <td>63</td>
        <td>2011/07/25</td>
        <td>$170,750</td>
    </tr>-->
    </tbody>
</table>