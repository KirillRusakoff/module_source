<?php

//Курс доллара

// Функция для получения данных о курсе доллара
function getUSDExchangeRate() {
    // Путь к файлу кеша
    $cacheFile = 'usd_exchange_rate.cache';

    // Время жизни кеша (в секундах)
    $cacheTime = 3600; // Например, 1 час

    // Проверяем, существует ли файл кеша и не истек ли срок его действия
    if (file_exists($cacheFile) && time() - filemtime($cacheFile) < $cacheTime) {
        // Если кеш актуален, читаем данные из него
        $usd_rate = file_get_contents($cacheFile);
    } else {
        // URL API Центрального Банка России
        $url = 'https://www.cbr-xml-daily.ru/daily_json.js';

        // Выполняем HTTP-запрос
        $response = file_get_contents($url);

        // Если запрос выполнен успешно
        if ($response !== false) {
            // Преобразуем JSON в ассоциативный массив
            $data = json_decode($response, true);

            // Проверяем, получены ли данные о курсе доллара
            if (isset($data['Valute']['USD']['Value'])) {
                // Извлекаем значение курса доллара
                $usd_rate = $data['Valute']['USD']['Value'];

                // Сохраняем данные в файл кеша
                file_put_contents($cacheFile, $usd_rate);
            } else {
                // Если данные о курсе доллара не получены, возвращаем сообщение об ошибке
                return 'Данные о курсе доллара не получены';
            }
        } else {
            // Если запрос не выполнен успешно, возвращаем сообщение об ошибке
            return 'Ошибка при выполнении запроса';
        }
    }

    // Возвращаем значение курса доллара
    return $usd_rate;
}

// Получаем значение курса доллара
$usd_rate = getUSDExchangeRate();

// Выводим курс доллара
// echo 'Курс доллара ЦБ: ' . $usd_rate;

//1-----------------------------------------------------------------------------------------------------------------------------------------------------

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        if (isset($_GET['title'])) {

        $searchTitle = $_GET['title'];
        
        $searchTitle = trim($searchTitle);

        require_once './parsers/phpQuery-onefile.php';

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
            $product['title'] = "";
            $product['in_stock'] = "";
            $product['producer'] = "";
            $min_price = null;
        }

        $min_price_elements = $pq->find('.products .product_price:contains("' . $min_price . '")')->parent();

        // Проверяем, есть ли в наличии товары
        if ($min_price_elements->count() > 0) {
            // Объявим массив для хранения информации о товарах
            $products_info = array();

            // Проходим по каждому элементу и сохраняем информацию в массив
            foreach ($min_price_elements as $min_price_element) {
                $titleElement = pq($min_price_element)->find('.product_title');
                $title = $titleElement->count() > 0 ? $titleElement->text() : $_GET['title'];
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
                $product['title'] = 0;
                $product['in_stock'] = 0;
                $product['producer'] = 0;
                $min_price = 0;
            }

            //Переменные для вывода

            // echo $product['title'];
            // echo $product['in_stock'];
            // echo $product['producer'];
            // echo $min_price;

            phpQuery::unloadDocuments();
        } else {
            $product['title'] = 1;
            $product['in_stock'] = 1;
            $product['producer'] = 1;
            $min_price = -1;
        }    

//2---------------------------------------------------------------------------------------------------------------------------------------------------------------

        // if ($searchTitle === null) {
        //     $titileString = "";
        //     $thisMinPrice = null;
        // }

        if (isset($_GET['title'])) {

        // error_reporting(E_ALL & ~E_DEPRECATED);

        require_once './parsers/phpQuery-onefile.php';
        
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
                    $quantity1 = pq($quantityElement)->text();

                    // Если количество "по запросу", заменяем его на 0
                    $quantity1 = ($quantity1 === 'под заказ' || $quantity1 === 'по запросу') ? '0' : $quantity1;

                    // Заменяем "999999999" в количестве на складе
                    $quantity1 = str_replace('999999999', '', $quantity1);

                    // Получаем производителя (предполагая, что он находится вторым по счету элементом с классом 'text-center')
                    $manufacturer = pq($manufacturerElement)->text();

                    // Заменяем "999999999" в производителе
                    $manufacturer = str_replace('999999999', '', $manufacturer);

                    // Заменяем "по запросу" на "0" в производителе
                    $manufacturer = str_replace('по запросу', '0', $manufacturer);

                    // Добавляем значения в массив
                    $pricesAndDetails[$price] = [
                        'manufacturer' => $manufacturer ? $manufacturer : '-', // Если значение отсутствует, ставим "-"
                        'quantityInStock' => $quantity1
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
                $titileString = 0;
                $thisMinPrice = 0;
            }
       
        phpQuery::unloadDocuments();

        } else {
            $quantityInStock = 1;
            $manufacturer = 1;
            $titileString = 1;
            $thisMinPrice = -1;
        } 

$dataTabless = isset($_GET['title']) ? trim($_GET['title']) : null;

//Excel Ali----------------------------------------------------------------------------------------------------------------------------------------------------------------

$csvFile = './parsers/tables-one/ali-table.csv';

       if (file_exists($csvFile)) {
           $file = fopen($csvFile, 'r');
       
           $minPrice = PHP_INT_MAX;
           $minPriceDetails = null;

           $priceTable1 = $priceTable2 = $priceTable3 = $priceTable4 = $priceTable5 = $titleTable = $produserTable = $sumTable = null;
       
           while (($data = fgetcsv($file, 1000, ',')) !== false) {
               if (isset($dataTabless) && stripos($data[1], $dataTabless) !== false) {
                   if (isset($data[4]) && is_numeric($data[4])) {
                       if ($data[5] < $minPrice) {
                           $minPrice = $data[5];
                           $minPriceDetails = array(
                               'название' => $data[1],
                               'производитель' => $data[0],
                               'количество' => $data[2],
                               'цена' => $data[4],
                               'цена-2' => $data[6],
                               'цена-3' => $data[8],
                               'цена-4' => $data[10],
                               'цена-5' => $data[12],
                           );
                       }
                   }
               }
           }
       
           fclose($file);
       
           // Выводим результат
    if ($minPriceDetails !== null) {
    // Получение минимальной цены
    $priceTable1 = number_format($minPriceDetails['цена'], 2);

    // Применение формулы к $priceTable1
    $priceTable1 = number_format(($priceTable1 * 1.03 * $usd_rate / 80 * 100) * 1.4, 2, '.', '');

    $titleTable = $minPriceDetails['название'];
    $produserTable = $minPriceDetails['производитель'];
    $sumTable = $minPriceDetails['количество'];

    // Аналогично для других цен, если они существуют
    if (isset($minPriceDetails['цена-2'])) {
        $priceTable2 = number_format($minPriceDetails['цена-2'], 2);
        $priceTable2 = number_format(($priceTable2 * 1.03 * $usd_rate / 80 * 100) * 1.4, 2, '.', '');
    }

    if (isset($minPriceDetails['цена-3'])) {
        $priceTable3 = number_format($minPriceDetails['цена-3'], 2);
        $priceTable3 = number_format(($priceTable3 * 1.03 * $usd_rate / 80 * 100) * 1.4, 2, '.', '');
    }

    if (isset($minPriceDetails['цена-4'])) {
        $priceTable4 = number_format($minPriceDetails['цена-4'], 2);
        $priceTable4 = number_format(($priceTable4 * 1.03 * $usd_rate / 80 * 100) * 1.4, 2, '.', '');
    }

    if (isset($minPriceDetails['цена-5'])) {
        $priceTable5 = number_format($minPriceDetails['цена-5'], 2);
        $priceTable5 = number_format(($priceTable5 * 1.03 * $usd_rate / 80 * 100) * 1.4, 2, '.', '');
    }
} else {
    // echo "no";
}
       }       
       // Теперь можно использовать $priceTable1, $titleTable, $produserTable, $sumTable за пределами условия
       // Например, выведите их значения
    //    echo "Цена: $priceTable1, Название: $titleTable, Производитель: $produserTable, Количество: $sumTable";

//Excel Cathy-----------------------------------------------------------------------------------------------------------------------------------------------

$csvFile1 = './parsers/tables-one/Cathy-Zhong.csv';

if (file_exists($csvFile1)) {
    $file1 = fopen($csvFile1, 'r');

    $minPrice1 = PHP_INT_MAX;
    $minPriceDetails1 = null;

    $price = $title = $producer = $quantity44 = null;

    while (($data1 = fgetcsv($file1, 1000, ',')) !== false) {
        if (isset($dataTabless) && stripos($data1[0], $dataTabless) !== false) {
            if (isset($data1[5]) && is_numeric($data1[5])) {
                if ($data1[5] < $minPrice1) {
                    $minPrice1 = $data1[5];
                    $minPriceDetails1 = array(
                        'название' => $data1[0],
                        'производитель' => $data1[1],
                        'количество' => $data1[2],
                        'цена' => $data1[5]
                    );
                }
            }
        }
    }

    fclose($file1);

    // Выводим результат
    if ($minPriceDetails1 !== null) {
        // Выносим значения во внешние переменные
        $price = $minPriceDetails1['цена'];
        $price4 = number_format($price * 1.03 * (($usd_rate * 1.07) / 80 * 100) * 1.4, 2, '.', '');
        $title = $minPriceDetails1['название'];
        $producer = $minPriceDetails1['производитель'];
        $quantity44 = $minPriceDetails1['количество'];

        // echo "Минимальная цена: $price у товара '$title'. Производитель: $producer. Количество на складе: $quantity.";
    } else {
        // echo "Минимальная цена не найдена для значения $dataTabless в таблице Cathy-Zhong.csv.";
    }
} else {
    // echo "Файл не найден.";
}

//Excel Module -------------------------------------------------------------------------------------------------------------------

$csvFile2 = './parsers/tables-one/Module.csv';

// Устанавливаем начальные значения переменных
$price2 = $title2 = $producer2 = $quantity2 = 0;

// Проверяем, было ли передано название товара через GET запрос
if ($dataTabless !== null) {
    // Открываем файл CSV для чтения
    $file2 = fopen($csvFile2, 'r');

    // Проходим по файлу построчно
    while (($row = fgetcsv($file2)) !== false) {
        // Проверяем, содержит ли название товара текущая строка
        if (stripos($row[0], $dataTabless) !== false) {
            // Если содержит, записываем данные о товаре
            $title2 = $row[0];
            $producer2 = $row[1];
            $quantity2 = $row[2];
            // Заменяем запятую на точку в цене и ограничиваем до 2 знаков после точки
            // $price2 = number_format(str_replace(',', '.', $row[3]), 2);
            $price2 = $row[3];
            break; // Завершаем цикл, так как нашли нужный товар
        }
    }
    fclose($file2); // Закрываем файл CSV
}


// echo "Цена: $price2 у товара '$title2'. Производитель: $producer2. Количество на складе: $quantity2.";

//Create file csv---------------------------------------------------------------------------------------------------------------------------------------------------

// Инициализируем переменные по умолчанию

$POP1 = $POT1 = $POQ1 = $POP2 = $POT2 = $POQ2 = $POP3 = $POT3 = $POQ3 = 0;
$POPR1 = $POPR2 = $POPR3 = '0'; // строки для цен и интервалов
$POMPR1 = $POMPR2 = $POMPR3 = 0;

if(isset($_GET['title'])) {
    $product_name = $_GET['title'];
    $csv_filename = 'parsers/request-PO/' . time() . '.csv';
    
    // Записываем название продукта в CSV файл
    file_put_contents($csv_filename, $product_name);
    
    // echo "CSV файл для товара '$product_name' успешно создан.";

    // Опрос папки parsers/tables/ несколько раз с задержкой в 10 секунд
    $num_checks = 3; // количество опросов
    $delay_seconds = 15; // задержка между опросами (в секундах)

    for ($i = 1; $i <= $num_checks; $i++) {
        // Путь к папке с файлами CSV
        $csv_files_directory = 'parsers/tables/';

        // Получаем список файлов в папке
        $files = scandir($csv_files_directory);

        // Выводим список файлов
        // echo "<br><br>Список файлов в папке parsers/tables/ (Опрос $i):<br>";
        foreach ($files as $file) {
            // Игнорируем ссылки на текущую и родительскую директории
            if ($file != '.' && $file != '..') {
                // echo $file . "<br>";
            }
        }

        // Если это не последний опрос, ждем указанное количество секунд
        if ($i < $num_checks) {
            sleep($delay_seconds);
        }
    }

    // Парсинг найденного файла CSV
    if(isset($files[2])) { // Проверяем, есть ли второй файл в списке (индекс 2, так как индексация начинается с 0)
        // Получаем путь к файлу CSV
        $csv_filename_table = $csv_files_directory . $files[2];

        // Читаем содержимое файла CSV
        $csv_data = file_get_contents($csv_filename_table);

        // Парсим данные из CSV файла
        $lines = explode("\n", $csv_data);

        // Парсим данные о каждом товаре
        foreach ($lines as $index => $line) {
            // Игнорируем пустые строки
            if (empty($line)) continue;

            // Разбиваем строку на части, используя запятую в качестве разделителя
            $parts = explode("~", $line);

            // Получаем данные о производителе, названии товара и количестве на складе
            $manufacturer = $parts[0];
            $product_name = $parts[1];
            $quantity = $parts[2];
            if (isset($parts[4])) {
                $metaPrice = number_format($parts[4] * 1.03 * ($usd_rate * 1.07) / 80 * 100 * 1.5, 2);
            } else {
                $metaPrice = 0;
            }


            // Парсим цены и интервалы
            $prices = '';
            if (count($parts) > 3) {
                for ($i = 3; $i < count($parts); $i += 2) {
                    $price = $parts[$i + 1];
                    $modified_price = $price * 1.03 * ($usd_rate * 1.07) / 80 * 100 * 1.5;
                    // Ограничиваем цену до 2 знаков после точки
                    $modified_price = number_format($modified_price, 2);
                    $prices .= "от {$parts[$i]}шт: $modified_price<br>";
                }
            }

            // Присваиваем данные соответствующим переменным в зависимости от индекса строки
            switch ($index) {
                case 0:
                    $POP1 = $manufacturer;
                    $POT1 = $product_name;
                    $POQ1 = $quantity;
                    $POPR1 = $prices ?: '0'; // если цен нет, присваиваем "0"
                    $POMPR1 = $metaPrice;
                    break;
                case 1:
                    $POP2 = $manufacturer;
                    $POT2 = $product_name;
                    $POQ2 = $quantity;
                    $POPR2 = $prices ?: '0'; // если цен нет, присваиваем "0"
                    $POMPR2 = $metaPrice;
                    break;
                case 2:
                    $POP3 = $manufacturer;
                    $POT3 = $product_name;
                    $POQ3 = $quantity;
                    $POPR3 = $prices ?: '0'; // если цен нет, присваиваем "0"
                    $POMPR3 = $metaPrice;
                    break;
            }
        }

        // Проверяем значения $POQ1, $POQ2, и $POQ3 и устанавливаем соответствующие значения $POPR1, $POPR2, и $POPR3 в случае, если $POQ равно 0
        if ($POQ1 == 0) {
            $POPR1 = '0';
        }
        if ($POQ2 == 0) {
            $POPR2 = '0';
        }
        if ($POQ3 == 0) {
            $POPR3 = '0';
        }

        //Выводим данные о каждом товаре
        // echo "<br><br>Данные о товарах из файла $files[2]:<br>";
        // echo "Первый товар: Производитель - $POP1, Название товара - $POT1, Количество на складе - $POQ1, Цены и интервалы:<br>$POPR1<br>";
        // echo "Второй товар: Производитель - $POP2, Название товара - $POT2, Количество на складе - $POQ2, Цены и интервалы:<br>$POPR2<br>";
        // echo "Третий товар: Производитель - $POP3, Название товара - $POT3, Количество на складе - $POQ3, Цены и интервалы:<br>$POPR3<br>";
        // echo "$POMPR1<br>";
        // echo "$POMPR2<br>";
        // echo "$POMPR3<br>";

        // Удаляем файл после успешного парсинга
        unlink($csv_filename_table);
    }
}
    

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ООО «Модуль Соурс» - надежный поставщик оригинальных электронных компонентов</title>
    <meta name="description" content="Надежный поставщик оригинальных электронных компонентов. Мы предлагаем широкий ассортимент продукции, включая полупроводники, резисторы, конденсаторы, диоды, индукторы и многое другое. Наша компания гарантирует высокое качество продукции и оперативные поставки по конкурентоспособным ценам.">
    <meta name="keywords" content="Электронные компоненты, Module`s Source, радиодетали, доставка электронных компонентов">
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="./main.css">
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="120x120" href="/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicons/favicon-16x16.png">
    <link rel="manifest" href="/img/favicons/site.webmanifest">
    <link rel="mask-icon" href="/img/favicons/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="/img/favicons/favicon.ico">
    <meta name="msapplication-TileColor" content="#95cfd7">
    <meta name="msapplication-config" content="/img/favicons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
</head>
<body id="body">
    <div class="form-alert-block">
        <div class="form-alert-block__middle">
            <h3>Ваша заявка отправлена!</h3>
            <span>В ближайшее время с Вами свяжутся</span>
            <button class="form-alert-block__close">Закрыть</button>
        </div>
    </div>
    <div class="register-block" id="register">
        <div  class="register-block__wrapper">
            <form action="register/log-in.php" method="post" class="auto-form">
                <h2>Авторизация:</h2>
                <button class="cross" type="button">&#10006;</button>
                <input type="email" name="login" placeholder="Введите логин">
                <input type="password" name="password" placeholder="Введите пароль:">
                <input type="submit" value="Войти" class="button">
                <p>У Вас нет аккаунта? <button type="button" id="on-register">Зарегистрируйтесь</button>.</p>
            </form>
            <form action="register/sing-up.php" method="post" class="register-form">
                <h2>Регистрация:</h2>
                <button class="cross" type="button">&#10006;</button>
                <input type="number" name="inn" id="inn" placeholder="Введите ИНН">
                <input type="text" name="title" id="title" placeholder="Название организации">
                <input type="text" name="real_address" placeholder="Юридический адрес">
                <input type="text" name="full_name" placeholder="ФИО">
                <input type="tel" name="phone" placeholder="Номер телефона">
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Придумайте пароль">
                <input type="submit" value="Зарегистрироваться" class="button">
                <p>У Вас есть аккаунт? <button type="button" id="on-auto">Войдите</button>.</p>
            </form>
        </div>
    </div>
    <div class="non-alert-block">
        <div class="non-alert-block__middle">
            <h3>Ничего не найдено!</h3>
            <span>Оставьте нам <span type="button" class="hidden-form-text__button" style="text-decoration:underline;cursor:pointer;">заявку</span>, и специалист пришлет вам предложение как можно скорее</span>
            <button type="button" class="non-alert-block__close">Закрыть</button>
        </div>
    </div>
    <div id="notification" class="notification"></div>
    <div class="hidden-form-text">
        <form class="hidden-form-text__middle" method="post" action="part-form.php">
            <img class="hidden-form-text__middle-cross" src="./img/icons/cross.svg">
            <input type="text" name="part-title" placeholder="Наименование*" required>
            <input type="text" name="part-qual" placeholder="Кол-во*" required>
            <input type="text" name="part-price" placeholder="Желаемая цена,RUB">
            <input type="text" name="part-inn" placeholder="ИНН организации*" required>
            <input type="text" name="part-name" placeholder="ФИО*" required>
            <input type="number" name="part-phone" placeholder="Телефон*" required>
            <input type="email" name="part-email" placeholder="Почта*" required>
            <textarea name="part-message"></textarea>
            <input type="submit" value="Отправить" class="button">
            <span style="font-size: 0.6250vw;color: #a1a1a1;">* - поля являются обязательными для заполнения</span>
        </form>
    </div>
    <header class="header">
        <div class="container">
            <div class="header__wrapper">
                <a class="logo" href="index.php">
                    <img src="./img/logo-2.png" alt="Логотип">
                </a>
                <div class="header__contacts">
                    <a href="tel:+74992880426" class="header__contact">+7(499)288-04-26</a>
                    <a href="mailto:order@modulesource.ru" class="header__contact">order@modulesource.ru</a>
                    <a href="tel:+79991251412" class="header__contact">+7(999)125-14-12 </a>
                    <div class="header__contacts-icons">
                        <a href="https://wa.me/79991251412"><img src="./img/icons/whatsapp.svg" alt="whatsapp" target="_blank"></a>
                        <!--<a href="+79991251412"><img src="./img/icons/viber.svg" alt="viber"></a>-->
                        <a href="https://t.me/ModuleSourse"><img src="./img/icons/telegram.svg" alt="talegram" target="_blank"></a>
                        <a href="https://u.wechat.com/kE04T42lUL3OrVrBo9jPiN8"><img src="./img/icons/wechat.svg" alt="wechat" target="_blank"></a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <section class="middle">
        <div class="container">
            <div class="middle__wrapper">
                <button class="header__button hidden-form-text__button" style="height:2.6042vw;line-height:1.8750vw;padding-left:1.0417vw;padding-right:1.0417vw;">Оставить заявку</button>
                <div class="middle__inner">
                    <form class="main__search" method='get' id="search" onsubmit="return validateForm()">
                    <input type="text" id="searchInput" name="title" placeholder="Введите название детали">
                    <button type="button" onclick="translateToEnglish()"  class="main__search-button" style="border:none !important;outline:none !important;">
                        <img src="./img/icons/search.svg" alt="поиск">
                    </button>
                    </form>
                    <div class="header__buttons">
                        <!-- <button type="button" class="header__button" id="open-form">Войти</button>
                        <div class="open-connect-form"><a style="color:red;"><img src="./img/icons/exit.svg" alt="exit"></a></div> -->
                        <button onclick="goToCart()" class="header__button" id="1">
                            <img src="./img/icons/cart-2.svg" alt="cart">
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <main class="main">
        <div class="container">
            <div class="main__wrapper">
                <div class = "loader-wrapper" style="display:none;flex-direction:column;align-items:center;gap:1.1vw;">
                    <!--<a class="logo" href="index.php">-->
                    <!--    <img src="./img/logo-2.png" alt="Логотип">-->
                    <!--</a>-->
                    <span>Идет загрузка. Пожалуйста, подождите...</span>
                    <div class="loader"></div>
                </div>
                <form style="display:none;" class="main__search">
                    <input type="text" name="title" placeholder="Введите название детали">
                    <button type="submit" class="main__search-button">
                        <img src="./img/icons/search.svg" alt="поиск">
                    </button>
                </form>
                <div class="table" id="table" style="width:100%;">
                    <div class="table__row table__row--header" style='border-top:none;'>
                        <div style="font-weight:600" class="table__block">Наименование</div>
                        <div style="font-weight:600" class="table__block">Доступно</div>
                        <div style="font-weight:600" class="table__block">Срок поставки</div>
                        <div style="font-weight:600" class="table__block">Производитель</div>
                        <div style="font-weight:600;width:13.4vw;" class="table__block">Цена, RUB
                            <img src="./img/icons/sort.svg" alt="стрелки сортировки" onclick="sortTable()" style="cursor:pointer;width:0.8vw;height:0.8vw;">
                        </div>
                        <div style="font-weight:600" class="table__block">Итого, RUB</div>
                        <div style="font-weight:600" class="table__block">Кол-во</div>
                        <div style="font-weight:600" class="table__block"> </div>
                    </div>
                    <div class="table__row" id="product1">
                        <div class="table__block"> <?php echo $product['title'];  ?> </div>
                        <div class="table__block double stock" data-in-stock="<?php echo $product['in_stock']; ?>"> <?php echo $product['in_stock'];  ?> </div>
                        <div class="table__block"> 1-2 недели </div>
                        <div class="table__block"> <?php echo $product['producer']; ?> </div>
                        <div class="table__block table__block-price"> <?php echo number_format($min_price * 1.15, 2, '.', ''); ?> </div>
                        <div class="table__block"> <span>0</span> </div>
                        <div class="table__block table__block-quallity"> <input type="number" placeholder="кол-во 1"> </div>
                        <div class="table__block table__block-cart"> <button onclick="addToCart(this)" type="button"><img src="./img/icons/cart.svg"></button> </div>
                    </div>
                    <div class="table__row" id="product2">
                        <div class="table__block"> <?php echo $titileString;?> </div>
                        <div class="table__block double stock" data-in-stock="<?php echo $quantityInStock; ?>"> <?php echo $quantityInStock;?> </div>
                        <div class="table__block"> 1-2 недели </div>
                        <div class="table__block"> <?php  echo $manufacturer; ?> </div>
                        <div class="table__block table__block-price"> <?php echo number_format($thisMinPrice + ($thisMinPrice * 20 / 100) * 1.15, 2, '.', ''); ?> </div>
                        <div class="table__block"> <span>0</span> </div>
                        <div class="table__block table__block-quallity"> <input type="number" placeholder="кол-во 1"> </div>
                        <div class="table__block table__block-cart"> <button onclick="addToCart(this)" type="button"><img src="./img/icons/cart.svg"></button> </div>
                    </div>
                    <div class="table__row" id="product3">
                        <div class="table__block"> <?php echo $titleTable; ?> </div>
                        <div class="table__block stock" data-in-stock="<?php echo $sumTable; ?>"> <?php echo $sumTable;  ?> </div>
                        <div class="table__block"> 2-4 недели </div>
                        <div class="table__block"> <?php echo $produserTable;  ?> </div>
                        <div class="table__block table__block-price table__block-price--multi" style="text-align:left;line-height: 1.4;" data-price1="<?php echo $priceTable1; ?>"
                        data-price2="<?php echo $priceTable2; ?>"
                        data-price3="<?php echo $priceTable3; ?>"
                        data-price4="<?php echo $priceTable4; ?>"
                        data-price5="<?php echo $priceTable5; ?>">
                        <?php echo "от 1 шт: " . $priceTable1 . "<br>";
                              echo "от 10 шт: " . $priceTable2 . "<br>";
                              echo "от 100 шт: " . $priceTable3 . "<br>";
                              echo "от 1000 шт: " . $priceTable4 . "<br>";
                              echo "от 10000 шт: " . $priceTable5; ?>
                        </div>
                        <div class="table__block"> <span>0</span> </div>
                        <div class="table__block table__block-quallity"> <input type="number" placeholder="кол-во 1"> </div>
                        <div class="table__block table__block-cart"> <button onclick="addToCart(this)" type="button"><img src="./img/icons/cart.svg"></button> </div>
                    </div>
                    <div class="table__row" id="product4">
                        <div class="table__block"> <?php echo $title; ?> </div>
                        <div class="table__block stock" data-in-stock="<?php echo $quantity44; ?>"> <?php echo $quantity44; ?> </div>
                        <div class="table__block"> 4-5 недель </div>
                        <div class="table__block"> <?php echo $producer; ?> </div>
                        <div class="table__block table__block-price"> <?php echo $price4 ?> </div>
                        <div class="table__block"> <span>0</span> </div>
                        <div class="table__block table__block-quallity"> <input type="number" placeholder="кол-во 1"> </div>
                        <div class="table__block table__block-cart"> <button onclick="addToCart(this)" type="button"><img src="./img/icons/cart.svg"></button> </div>
                    </div>
                    <div class="table__row" id="product8">
                        <div class="table__block"> <?php echo $title2; ?> </div>
                        <div class="table__block stock" data-in-stock="<?php echo $quantity2; ?>"> <?php echo $quantity2; ?> </div>
                        <div class="table__block"> 1 неделя </div>
                        <div class="table__block"> <?php echo $producer2; ?> </div>
                        <div class="table__block table__block-price"> <?php echo $price2 ?> </div>
                        <div class="table__block"> <span>0</span> </div>
                        <div class="table__block table__block-quallity"> <input type="number" placeholder="кол-во 1"> </div>
                        <div class="table__block table__block-cart"> <button onclick="addToCart(this)" type="button"><img src="./img/icons/cart.svg"></button> </div>
                    </div>
                    <div class="table__row" id="product5">
                        <div class="table__block"> <?php echo $POT1; ?> </div>
                        <div class="table__block stock table__block-stock--po" data-in-stock="<?php echo $POQ1; ?>"> <?php echo $POQ1; ?> </div>
                        <div class="table__block"> 3-4 недели </div>
                        <div class="table__block"> <?php echo $POP1; ?> </div>
                        <div class="table__block table__block-price table__block-price--po table__block-price--po1" style="text-align:left;line-height: 1.4;" data-meta="<?php echo $POMPR1; ?>" data-attr="<?php echo  $POPR1; ?>"> <?php echo  $POPR1; ?> </div>
                        <div class="table__block table__block--po1"> <span>0</span> </div>
                        <div class="table__block table__block-quallity table__block-quallity--po1"> <input type="number" placeholder="кол-во 1"> </div>
                        <div class="table__block table__block-cart"> <button onclick="addToCart(this)" type="button"><img src="./img/icons/cart.svg"></button> </div>
                    </div>
                    <div class="table__row" id="product6">
                        <div class="table__block"> <?php echo $POT2; ?> </div>
                        <div class="table__block stock table__block-stock--po" data-in-stock="<?php echo $POQ2; ?>"> <?php echo $POQ2; ?> </div>
                        <div class="table__block"> 3-4 недели </div>
                        <div class="table__block"> <?php echo $POP2; ?> </div>
                        <div class="table__block table__block-price table__block-price--po table__block-price--po2" style="text-align:left;line-height: 1.4;" data-meta="<?php echo $POMPR2; ?>" data-attr="<?php echo  $POPR2; ?>"> <?php echo  $POPR2;  ?> </div>
                        <div class="table__block table__block--po2"> <span>0</span> </div>
                        <div class="table__block table__block-quallity table__block-quallity--po2"> <input type="number" placeholder="кол-во 1"> </div>
                        <div class="table__block table__block-cart"> <button onclick="addToCart(this)" type="button"><img src="./img/icons/cart.svg"></button> </div>
                    </div>
                    <div class="table__row" id="product7">
                        <div class="table__block"> <?php echo $POT3; ?> </div>
                        <div class="table__block stock table__block-stock--po" data-in-stock="<?php echo $POQ3; ?>"> <?php echo $POQ3; ?> </div>
                        <div class="table__block"> 3-4 недели </div>
                        <div class="table__block"> <?php echo $POP3; ?> </div>
                        <div class="table__block table__block-price table__block-price--po table__block-price--po3" style="text-align:left;line-height: 1.4;" data-meta="<?php echo $POMPR3; ?>" data-attr="<?php echo  $POPR3; ?>"> <?php echo $POPR3; ?> </div>
                        <div class="table__block table__block--po3"> <span>0</span> </div>
                        <div class="table__block table__block-quallity table__block-quallity--po3"> <input type="number" placeholder="кол-во 1"> </div>
                        <div class="table__block table__block-cart"> <button onclick="addToCart(this)" type="button"><img src="./img/icons/cart.svg"></button> </div>
                    </div>
                </div>
            </div>    
        </div>
    </main>
    <section class="cta">
        <div class="container">
            <div class="cta__wrapper">
                <div class="cta__block">
                    <h3>Поставки электронных компонентов, радиодеталей</h3>
                    <p>
                        - Исключительно оригинального производства<br> 
                        - Напрямую от производителей<br> 
                        - Со складов официальных дистрибьюторов, находящихся в Америке, Европе, Азии
                    </p>
                </div>
                <div class="cta__block">
                    <h3>Доставка</h3>
                    <p>
                        - Надежная доставка электронных компонентов из-за рубежа.<br>
                        - Быстрая доставка радиодеталей, от 2-х недель.
                    </p>
                </div>
                <div class="cta__block">
                    <h3>Условия оплаты</h3>
                    <p>
                        - Предоставляем персональные условия по оплате.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="suppliers">
        <div class="container">
            <div class="swiper" id="suppliers">
                <div class="swiper-wrapper suppliers__wrapper">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/01.jpg" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/02.jpg" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/03.png" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/04.png" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/05.png" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/06.png" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/07.png" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/08.png" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/09.jpg" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/10.jpg" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/11.png" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/12.png" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/13.png" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/14.png" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/15.png" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/16.png" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/17.png" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/18.png" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/19.png" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/20.png" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/21.png" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/22.png" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/23.png" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/24.png" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/25.png" alt="">
                   <img class="swiper-slide suppliers__slide" src="./img/logo-part/26.png" alt="">
                </div>
            </div>
        </div>
    </section> 
    <section class="clients">
        <div class="container">
            <h2 class="clients__title">Наши заказчики</h2>
            <div class="swiper" id="clients">
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-wrapper clients__wrapper">
                    <img class="swiper-slide clients__slide" src="./img/clients/cl-01.png" alt="">
                    <img class="swiper-slide clients__slide" src="./img/clients/cl-06.png" alt="">
                    <img class="swiper-slide clients__slide" src="./img/clients/cl-02.png" alt="">
                    <img class="swiper-slide clients__slide" src="./img/clients/cl-04.jpg" alt="">
                    <img class="swiper-slide clients__slide" src="./img/clients/cl-03.png" alt="">
                    <img class="swiper-slide clients__slide" src="./img/clients/cl-05.webp" alt="">
                </div>
            </div>
        </div>
    </section> 
    <section class="reviews">
        <div class="container">
            <h2 class="reviews__title">Наши отзывы</h2>
            <div class="swiper" id="reviews">
                <div class="swiper-wrapper reviews__wrapper">
                    <div class="swiper-slide reviews__slide">
                        <div class="swiper-zoom-container" data-swiper-zoom="1.5">
                            <img src="./img/reviews/reviews-02.png" alt="">
                        </div>
                    </div>
                    <div class="swiper-slide reviews__slide">
                        <div class="swiper-zoom-container" data-swiper-zoom="1.5">
                            <img src="./img/reviews/reviews-03.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>   
    <footer class="footer">
        <div class="container">
            <div class="footer__wrapper">
                <p class="footer__copyright">ООО "Модуль Соурс"</p>
                <div class="footer__blocks">
                    <ul class="footer__links">
                        <li>ИНН 9718223264</li>
                        <!-- <li><a href="#!">О компании</a></li> -->
                        <!-- <li><a href="#!">Оформить заказ</a></li> -->
                        <!-- <li><a href="#!">Для поставщиков</a></li> -->
                        <!-- <li><a href="#!">Для покупателя</a></li> -->
                    </ul>
                    <ul class="footer__links">
                        <li>КПП 590601001</li>
                        <!--<li><a href="requisites.php">Реквизиты</a></li>-->
                        <!-- <li><a href="#!">Отзывы</a></li> -->
                        <!-- <li><a href="#!">Ответы на вопросы</a></li> -->
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://hammerjs.github.io/dist/hammer.min.js"></script>
    <script src="./libs/jquery-3.7.1.min.js"></script>
    <script src="./index.js"></script>
    <script src="./main.js"></script>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
       (function(m,e,t,r,i,k,a){m[i]=m[i]function(){(m[i].a=m[i].a[]).push(arguments)};
       m[i].l=1*new Date();
       for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
       k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
       (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
    
       ym(96933838, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true
       });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/96933838" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
</body>
</html>