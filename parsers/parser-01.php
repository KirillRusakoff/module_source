<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parser 1</title>
</head>
<body>
    <h1>Страница парсера 1</h1>
    <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $searchTitle = isset($_GET['title']) ? $_GET['title'] : null;

        if ($searchTitle === null) {
            $product['title'] = "";
            $product['in_stock'] = "";
            $product['producer'] = "";
            $min_price = null;
        }
        // error_reporting(E_ALL & ~E_DEPRECATED);

        require_once './phpQuery-onefile.php';

        $ch = curl_init('https://triema.su/shop/products/search?title=' . $searchTitle);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //запись данных в переменную (#html)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //для проверки захода с HTTP или HTTPS
        curl_setopt($ch, CURLOPT_HEADER, false); //включение заголовков вывода
        $html = curl_exec($ch);
        curl_close($ch);
        // echo $html;

        $pq = phpQuery::newDocument($html);

        $elem_price = $pq->find('.products .product_price');
        $text = $elem_price->html();
        // var_dump($text);

        // Находим все числа в строке
        preg_match_all('/\d+/', $text, $matches);

        $prices = $matches[0];

        // Фильтрация "под заказ"
        $filtered_prices = array_filter($prices, function ($price) use ($text) {
            return strpos($text, $price . ' под заказ') === false;
        });

        // Преобразование строковых значений в целые числа
        $filtered_prices = array_map('intval', $filtered_prices);

        // Проверка наличия числовых элементов перед использованием min()
        if (!empty($filtered_prices)) {
            // Нахождение минимального значения
            $min_price = min($filtered_prices);

            // Вывод результата
            // echo "Минимальная цена: $min_price\n";
        } else {
            // echo "Нет доступных числовых цен.\n";
        }

        $min_price_elements = $pq->find('.products .product_price:contains("' . $min_price . '")')->parent();

        // Проверяем, есть ли в наличии товары
        if ($min_price_elements->count() > 0) {
            // Объявим массив для хранения информации о товарах
            $products_info = array();

            // Проходим по каждому элементу и сохраняем информацию в массив
            foreach ($min_price_elements as $min_price_element) {
                $title = pq($min_price_element)->find('.product_title')->text();
                $in_stock = pq($min_price_element)->find('.product_in_stock')->text();
                $producer = pq($min_price_element)->find('.product_producer')->text();

                // Сохраняем информацию в массив
                $products_info[] = array(
                    'title' => $title,
                    'in_stock' => $in_stock,
                    'producer' => $producer
                );
            }

            // Выводим результаты (по вашему усмотрению)
            foreach ($products_info as $product) {
                // echo "Название товара: {$product['title']}\n";
                // echo "Количество на складе: {$product['in_stock']}\n";
                // echo "Производитель: {$product['producer']}\n";
            }

            // Теперь у вас есть массив $products_info с информацией о каждом товаре,
            // который вы можете использовать для дальнейших действий, например, добавления в таблицу.
            } else {
                // echo "Нет в наличии\n";
            }

            //Переменные для вывода

            echo $product['title'];
            echo $product['in_stock'];
            echo $product['producer'];
            echo $min_price;

            phpQuery::unloadDocuments();
    ?>
</body>
</html>
