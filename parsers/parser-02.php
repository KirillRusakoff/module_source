<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parser 2</title>
</head>
<body>
    <h1>Страница парсера 2</h1>
    <?php

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $searchTitle = isset($_GET['title']) ? $_GET['title'] : null;

        if ($searchTitle === null) {
            $titileString = "";
            $thisMinPrice = null;
        }

        // error_reporting(E_ALL & ~E_DEPRECATED);

        require_once './phpQuery-onefile.php';
        
        $ch = curl_init('https://elbase.ru/search?wrd=' . $searchTitle);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $html = curl_exec($ch);
        curl_close($ch);
        
        $pq = phpQuery::newDocument($html);
        
        $elem_prices = $pq->find('.table-preseach .card-text');
        
        $max_availability = 0;
        $max_availability_link = '';
        
        foreach ($elem_prices as $elem_price) {
            $text = pq($elem_price)->text();
        
            // Фильтруем только текст, содержащий "Наличие:"
            if (strpos($text, 'Наличие:') !== false) {
                preg_match('/Наличие: (\d+)/', $text, $matches);
        
                if (isset($matches[1]) && $matches[1] !== 'под заказ') {
                    $availability = (int)$matches[1];
        
                    // Если текущее наличие больше максимального, обновляем значения
                    if ($availability > $max_availability) {
                        $max_availability = $availability;
        
                        // Получаем ссылку (href) из родительской карточки
                        $max_availability_link = pq($elem_price)->parents('.card')->attr('href');
                    }
                }
            }
        }
        
        // echo "Наибольшее наличие - $max_availability\n";
        // echo "Ссылка на карточку - $max_availability_link\n";

        phpQuery::unloadDocuments();

        $chI = curl_init('https://elbase.ru' . $max_availability_link);
            curl_setopt($chI, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chI, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($chI, CURLOPT_HEADER, false);
            $htmlI = curl_exec($chI);
            curl_close($chI);

            // var_dump($htmlI);
        
            $pq = phpQuery::newDocument($htmlI);

            $elem_title = $pq->find('h1');

            $titileString = $elem_title->text();
        
            // Найдем элементы с ценами
            $elem_pricesI = $pq->find('.table tbody tr');

            // Создадим массив для хранения цен, производителей и количеств на складе
            $pricesAndDetails = [];

            foreach ($elem_pricesI as $elem_priceI) {
                $priceElement = pq($elem_priceI)->find('td.text-end');
                $manufacturerElement = pq($elem_priceI)->find('td.text-center')->eq(1); // Получаем второй по счету элемент с классом 'text-center'
                $quantityElement = pq($elem_priceI)->find('td.text-center')->eq(3); // Получаем четвертый по счету элемент с классом 'text-center'

                // Убедимся, что у нас есть и цена, и производитель, и количество
                if ($priceElement->length && $manufacturerElement->length && $quantityElement->length) {
                    $price = pq($priceElement)->text();

                    // Удаляем "999999999"
                    $price = str_replace('999999999', '', $price);

                    // Заменяем "по запросу" на "999999"
                    $price = str_replace('по запросу', '999999', $price);

                    // Заменяем запятые в числах на точки
                    $price = str_replace(',', '.', $price);

                    // Форматируем число с не более чем двумя знаками после запятой
                    $price = number_format((float)$price, 2, '.', '');

                    // Получаем количество товара на складе (предполагая, что оно находится четвертым по счету элементом с классом 'text-center')
                    $quantity = pq($quantityElement)->text();

                    // Если количество "по запросу", заменяем его на 0
                    $quantity = ($quantity === 'под заказ' || $quantity === 'по запросу') ? '0' : $quantity;

                    // Заменяем "999999999" в количестве на складе
                    $quantity = str_replace('999999999', '', $quantity);

                    // Получаем производителя (предполагая, что он находится вторым по счету элементом с классом 'text-center')
                    $manufacturer = pq($manufacturerElement)->text();

                    // Заменяем "999999999" в производителе
                    $manufacturer = str_replace('999999999', '', $manufacturer);

                    // Заменяем "по запросу" на "0" в производителе
                    $manufacturer = str_replace('по запросу', '0', $manufacturer);

                    // Добавляем значения в массив
                    $pricesAndDetails[$price] = [
                        'manufacturer' => $manufacturer ? $manufacturer : '-', // Если значение отсутствует, ставим "-"
                        'quantityInStock' => $quantity
                    ];
                }
            }

            // Если массив $pricesAndDetails не пуст, находим минимальное значение в массиве цен
            $thisMinPrice = !empty($pricesAndDetails) ? min(array_keys($pricesAndDetails)) : null;

            // Устанавливаем значения по умолчанию для $manufacturer и $quantityInStock
            $manufacturer = '';
            $quantityInStock = '';

            // Если $thisMinPrice не равно null, получаем детали для минимальной цены
            if ($thisMinPrice !== null) {
                $detailsForMinPrice = $pricesAndDetails[$thisMinPrice];

                // Получаем производителя для минимальной цены
                $manufacturer = $detailsForMinPrice['manufacturer'];

                // Получаем количество товара на складе для минимальной цены
                $quantityInStock = $detailsForMinPrice['quantityInStock'];

                // echo "Наименьшее значение: $thisMinPrice\n";
                // echo "Производитель: $manufacturer\n";
                // echo "Количество товара на складе: $quantityInStock\n";
            } else {
                // echo "Массив цен пуст. Нет данных для вывода.\n";
            }
       
        phpQuery::unloadDocuments();
        ?>
</body>
</html>