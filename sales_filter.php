<?php
require_once 'keyDB.php';

function validateData($data) {
    $errors = [];

    if ((!empty($data['date_min']) && !preg_match("/^\d{4}-\d{2}-\d{2}$/", $data['date_min'])) || (!empty($data['date_max']) && !preg_match("/^\d{4}-\d{2}-\d{2}$/", $data['date_max']))) {
        $errors['date'] = 'Неверное значение даты';
    }

    return $errors;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validateData($_POST);
    $form_data = array();
    foreach ($_POST as $key => $value) {
        if (!array_key_exists($key, $errors)) {
            $form_data[$key] = $value;
        } else {
            $form_data[$key] = '';
        }
    }
    setcookie('sales_filter_data', json_encode($form_data), 0, '/');
    
    if (!empty($errors)) {
        setcookie('form_sales_errors', json_encode($errors), 0, '/');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        setcookie('form_sales_errors', '', 0, '/');

        echo '
        <!DOCTYPE html>
        <html lang="ru">
            <head>
                <link href="css/stylesTables.css" rel="stylesheet" type="text/css">
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
}

function executeSql() {
    try {
        $db = new PDO(BD_NAME, BD_LOG, BD_PASS, [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $params = [];

        $sql = 'SELECT Продажи.Дата_продажи, Товары.Наименование, Товары.Цена_закупки, Товары.Цена_продажи
        FROM Продажи
        JOIN Товары ON Продажи.ID_товара = Товары.ID
        WHERE  true';

        if (!empty($_POST['date_min']) && !empty($_POST['date_max'])) {
            $sql .= ' and Продажи.Дата_продажи BETWEEN :param1 AND :param2';
            $params[':param1'] = $_POST['date_min'];
            $params[':param2'] = $_POST['date_max'];
        }

        $sql .= ' ORDER BY Продажи.Дата_продажи';

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        if ($stmt->rowCount() > 0) {
            echo '<table border="1">';
            echo '<tr><th>Дата продажи</th><th>Наименование</th><th>Цена закупки</th><th>Цена продажи</th></tr>';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . $row['Дата_продажи'] . '</td>';
                echo '<td>' . $row['Наименование'] . '</td>';
                echo '<td>' . $row['Цена_закупки'] . '</td>';
                echo '<td>' . $row['Цена_продажи'] . '</td>';
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
