<?php
require_once 'keyDB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    setcookie('form_sales_errors', '', 0, '/');
    $form_data = array();
    foreach ($_POST as $key => $value) {
        if (!array_key_exists($key, $errors)) {
            $form_data[$key] = $value;
        }
    }
    setcookie('sales_group_data', json_encode($form_data), 0, '/');
    echo '
    <!DOCTYPE html>
    <html lang="ru">
        <head>
            <link href="css/styles.css" rel="stylesheet" type="text/css">
            <link rel="icon" href="image/favicon.png" type="image/png">
            <meta charset="UTF-8">
            <title>Учет оптовых продаж магазина</title>
        </head>
        <body>
            <div id="form">
            <h3>Результат:</h3>
        ';

        executeSql();
        echo '
                </div>
            </body>
        </html>
        ';

    echo "<script type='text/javascript'>
            if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
                window.location.href = 'index.php';
            }
        </script>";
}

function executeSql() {
    try {
        $db = new PDO(BD_NAME, BD_LOG, BD_PASS, [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        $sql = 'SELECT ID_товара, MIN(Количество_проданных_единиц) AS Минимальное_количество, MAX(Количество_проданных_единиц) AS Максимальное_количество
        FROM Продажи
        GROUP BY ID_товара;';

        $stmt = $db->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo '<table border="1">';
            echo '<tr><th>Код товара</th><th>Минимальное количество</th><th>Максимальное количество</th></tr>';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . $row['ID_товара'] . '</td>';
                echo '<td>' . $row['Минимальное_количество'] . '</td>';
                echo '<td>' . $row['Максимальное_количество'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo 'По текущему запросу не нашлось записей в БД';
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
