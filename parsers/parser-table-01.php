<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parser table ALI</title>
</head>
<body>
    <?php

       $csvFile = './tables/ali-table.csv';

       if (file_exists($csvFile)) {
           $file = fopen($csvFile, 'r');
       
           $minPrice = PHP_INT_MAX;
           $minPriceDetails = null;
       
           while (($data = fgetcsv($file, 1000, ',')) !== false) {
               if ($data[1] == 'ZTX751') {
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
               $priceTable1 = $minPriceDetails['цена'];
               $titleTable = $minPriceDetails['название'];
               $produserTable = $minPriceDetails['производитель'];
               $sumTable = $minPriceDetails['количество'];
               $priceTable2 = $minPriceDetails['цена-2'];
               $priceTable3 = $minPriceDetails['цена-3'];
               $priceTable4 = $minPriceDetails['цена-4'];
               $priceTable5 = $minPriceDetails['цена-5'];
           } else {
               echo "0";
           }
       }
       
       // Теперь можно использовать $priceTable1, $titleTable, $produserTable, $sumTable за пределами условия
       // Например, выведите их значения
       echo "Цена: $priceTable1, Название: $titleTable, Производитель: $produserTable, Количество: $sumTable";
    ?>
</body>
</html>