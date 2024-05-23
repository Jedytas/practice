<?php
require_once 'keyDB.php';

function validateData($data) {
    $errors = [];

    if (!empty($data['product_name']) && !preg_match("/^[а-яА-Яa-zA-Z\s]+$/u", $data['product_name'])) {
        $errors['Наименоваие'] = 'Название может содержать только буквы и пробелы';
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
    setcookie('product_data1', json_encode($form_data), 0, '/');

    if (!empty($errors)) {
        setcookie('form_product_errors', json_encode($errors), 0, '/');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        setcookie('form_product_errors', '', 0, '/');

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

        $sql = 'SELECT * FROM Товары WHERE true';

        if (!empty($_POST['product_name'])) {
            $sql .= ' AND Наименование = :param1';
            $params[':param1'] = $_POST['product_name'];
        }


        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        if ($stmt->rowCount() > 0) {
            echo '<table border="1">';
            echo '<tr><th>ID</th><th>Наименование</th><th>Единица измерения</th><th>Цена закупки</th><th>Цена продажи</th></tr>';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . $row['ID'] . '</td>';
                echo '<td>' . $row['Наименование'] . '</td>';
                echo '<td>' . $row['Единица_измерения'] . '</td>';
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
