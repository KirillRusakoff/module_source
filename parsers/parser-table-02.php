<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parser table Cathy-Zhong</title>
</head>
<body>
    <?php

$csvFile = './tables/Cathy-Zhong.csv';

if (file_exists($csvFile)) {
    $file = fopen($csvFile, 'r');

    $minPrice = PHP_INT_MAX;
    $minPriceDetails = null;

    while (($data = fgetcsv($file, 1000, ',')) !== false) {
        if ($data[0] == 'ZTX751') {
            if (isset($data[5]) && is_numeric($data[5])) {
                if ($data[5] < $minPrice) {
                    $minPrice = $data[5];
                    $minPriceDetails = array(
                        'название' => $data[0],
                        'производитель' => $data[1],
                        'количество' => $data[2],
                        'цена' => $data[5]
                    );
                }
            }
        }
    }

    fclose($file);

    // Выводим результат
    if ($minPriceDetails !== null) {
        // Выносим значения во внешние переменные
        $price = $minPriceDetails['цена'];
        $title = $minPriceDetails['название'];
        $producer = $minPriceDetails['производитель'];
        $quantity = $minPriceDetails['количество'];

        echo "Минимальная цена: $price у товара '$title'. Производитель: $producer. Количество на складе: $quantity.";
    } else {
        echo "0";
    }
} else {
    echo "Файл не найден.";
}

    ?>
</body>
</html>