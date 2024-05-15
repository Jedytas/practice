<!DOCTYPE html>
<html lang="ru">
    <head>
        <link href="css/styles.css" rel="stylesheet" type="text/css">
        <meta charset="UTF-8">
        <title>Учет оптовых продаж магазина</title>

        <script src="js/form.js" defer></script>
	<link rel="icon" href="image/favicon.png" type="image/x-icon">
    </head>
    <body>
        <div id="form">
            <h3>Запросы к базе данных оптового магазина:</h3>
            <select id="option" name="opt">
                <option value="no">Выберите таблицу</option>
                <option value="product" <?php echo isset($_COOKIE['form_selected']) && $_COOKIE['form_selected'] == 1 ? 'selected' : ''; ?>>Товары</option>
                <option value="sellers" <?php echo isset($_COOKIE['form_selected']) && $_COOKIE['form_selected'] == 2 ? 'selected' : ''; ?>>Продавцы</option>
                <option value="sales" <?php echo isset($_COOKIE['form_selected']) && $_COOKIE['form_selected'] == 3 ? 'selected' : ''; ?>>Продажи</option>
            </select><br>

            <div style="display: <?php echo isset($_COOKIE['form_selected']) && $_COOKIE['form_selected'] == 1 ? 'block' : 'none'; ?>;" id="d1">
            <?php if (isset($_COOKIE['form_product_errors'])): ?>
                <div class="errors12">
                    <?php foreach (json_decode($_COOKIE['form_product_errors'], true) as $field => $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="product_filter.php" method="post">
                <h4>Получить список товаров по закупке:</h4>

                <label for="price_min">Цена закупки от</label>
                <input type="number" name="price_min" value="<?php echo isset($_COOKIE['product_filter_data']) ? json_decode($_COOKIE['product_filter_data'])->price_min : ''; ?>">
                <label for="price_max">До</label>
                <input type="number" name="price_max" value="<?php echo isset($_COOKIE['product_filter_data']) ? json_decode($_COOKIE['product_filter_data'])->price_max : ''; ?>"><br>
                <input id="sendbutton" type="submit" value="Отправить">
            </form>

            <form action="product_filter1.php" method="post">
                <h4>Найти необходимый товар:</h4>

                <label for="product_name">Название:</label>
                <input type="text" name="product_name" value="<?php echo isset($_COOKIE['agents_filter_data']) ? json_decode($_COOKIE['agents_filter_data'])->product_name : ''; ?>"><br>
                <input id="sendbutton" type="submit" value="Отправить">
            </form>

            <form action="product_group.php" method="post">
                <h4>Получить сгруппированный список товаров по лучшей прибыльности:</h4>
                <input id="sendbutton" type="submit" value="Сгруппировать">
            </form>

            </div>


            <div style="display: <?php echo isset($_COOKIE['form_selected']) && $_COOKIE['form_selected'] == 2 ? 'block' : 'none'; ?>;" id="d2">
            <?php if (isset($_COOKIE['form_sellers_errors'])): ?>
                <div class="errors12">
                    <?php foreach (json_decode($_COOKIE['form_sellers_errors'], true) as $field => $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="sellers_filter.php" method="post">
                <h4>Получить список продавцов по первой букве фамилии:</h4>

                <label for="surname">Введите букву:</label>
                <input type="text" name="surname" value="<?php echo isset($_COOKIE['sellers_filter.php']) ? json_decode($_COOKIE['sellers_filter.php'])->surname : ''; ?>"><br>

                <input id="sendbutton" type="submit" value="Отправить">
            </form>

            <form action="sellers_filter1.php" method="post">
                <h4>Получить список продавцов с нужным комиссионным процентом:</h4>

                <label for="percent_min">Процент комиссионных от</label>
                <input type="number" name="percent_min" value="<?php echo isset($_COOKIE['sellers_filter.phpa']) ? json_decode($_COOKIE['sellers_filter.php'])->percent_min : ''; ?>">
                <input id="sendbutton" type="submit" value="Отправить">
            </form>

            </div>

            <div style="display: <?php echo isset($_COOKIE['form_selected']) && $_COOKIE['form_selected'] == 3 ? 'block' : 'none'; ?>;" id="d3">
            <?php if (isset($_COOKIE['form_sales_errors'])): ?>
                <div class="errors12">
                    <?php foreach (json_decode($_COOKIE['form_sales_errors'], true) as $field => $error): ?>
                        <p><?php echo $error; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
 
            <form action="sales_filter.php" method="post">
                <h4>Получить список продаж по фильтру:</h4>

                <label for="date_min">Дата продажи от</label>
                <input type="date" name="date_min" value="<?php echo isset($_COOKIE['sales_filter_data']) ? json_decode($_COOKIE['sales_filter_data'])->date_min : ''; ?>">

                <label for="date_max">До</label>
                <input type="date" name="date_max" value="<?php echo isset($_COOKIE['sales_filter_data']) ? json_decode($_COOKIE['sales_filter_data'])->date_max : ''; ?>"><br>

                <input id="sendbutton" type="submit" value="Отправить">
            </form>

            <form action="sales_group.php" method="post">
                <h4>Получить информацию о продажах по минимальному и максимальному значению проданных единиц:</h4>
                <input id="sendbutton" type="submit" value="Сгруппировать">
            </form>

            </div>

        </div>
    </body>
</html>