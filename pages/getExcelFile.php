<?php
    // Obtener los datos enviados desde JavaScript
    $data = json_decode(file_get_contents('php://input'), true);

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="downloaded.xls"');
?>
<table border="1">
    <!-- <caption><tr><th>
        <?php#echo $data['title'];?>
    </th></tr></caption> -->
    <thead>
        <?php
            echo $data['thead'];
        ?>
    </thead>
    <tbody>
        <?php
            echo $data['tbody'];
        ?>
    </tbody>
</table>
