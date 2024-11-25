<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение данных изображения и обработка
    $imageData = $_POST['imageData'];
    $imageData = str_replace('data:image/png;base64,', '', $imageData);
    $imageData = str_replace(' ', '+', $imageData);
    $imageData = base64_decode($imageData);

    // Сохранение изображения
    $imageFileName = 'screenshot_' . uniqid() . '.png';
    
    if (file_put_contents($imageFileName, $imageData)) {
        // Отправка изображения на почту

        // Получение данных из формы
        $inn = $_POST['inn'];
        $title = $_POST['title'];
        $real_address = $_POST['real_address'];
        $recipient = $_POST['recipient'];
        $recipient_phone = $_POST['recipient-phone'];
        $email = $_POST['email'];
        $city = $_POST['city'];
        $street = $_POST['street'];
        $number_house = $_POST['number-house'];
        $corpuse = $_POST['corpuse'];
        $office = $_POST['office'];

        // Получение данных из таблицы
        $tableData = $_POST['tableData'];

        // Создаем уникальный разделитель для границ между частями письма
        $boundary = uniqid('boundary');

        // Добавляем заголовки для многократных частей
        $headers = 'From: modulesource.ru';
        $headers .= "\r\nMIME-Version: 1.0";
        $headers .= "\r\nContent-Type: multipart/mixed; boundary=\"$boundary\"";

        $headers2 = 'From: modulesource.ru';
        $headers2 .= "\r\nMIME-Version: 1.0";

        // Создаем тело письма
        $message = "Инн: $inn\nАдрес: $real_address\nНазвание организации: $title\nФИО получателя: $recipient\nТелефон получателя: $recipient_phone\nEmail: $email\nГород: $city\nУлица: $street\nНомер дома: $number_house\nКорпус: $corpuse\nНомер квартиры / Офиса: $office";

        // Добавляем данные из таблицы к текстовой части письма
        $message .= "\n\nДанные из таблицы:\n" . $tableData;

        $message2 = "Здравствуйте!\n\nВы разместили заказ на платформе modulesource.ru\n\nЗаказ передан на подтверждение менеджеру, который в кратчайшие сроки отправит Вам счет.\n\nВНИМАНИЕ:\nВ связи с постоянным движением товаров, а также по причине меняющегося курса валют, разбросом цен в зависимости от общего кол-ва и прочих факторов, ФИНАЛЬНЫЕ и КОРРЕКТНЫЕ условия поставки (цены, сроки) в счете могут отличаться от заявленных на сайте. При этом условия поставки, зафиксированные в счете, гарантированно подтверждают РЕЗЕРВ заказанных для Вас позиций, после чего Вам будет предложено произвести оплату заказа.\n\nБлагодарим за оказанное доверие! Хорошего дня!\n\nВ случае возникновения вопросов, пожалуйста, обращайтесь к нам любым удобным способом:\n\nТелефон:\n+7(499)288-04-26\n+7(999)125-14-12\n\nEmail: order@modulesource.ru\n\nС Уважением, ООО «Модуль Соурс»\nmodulesource.ru";
        
         // Создаем тело письма
        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= base64_encode($message) . "\r\n";
        $body .= "--$boundary\r\n";
        $body .= "Content-Type: image/png\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "Content-Disposition: attachment; filename=\"$imageFileName\"\r\n\r\n";
        $body .= chunk_split(base64_encode($imageData)) . "\r\n";
        $body .= "--$boundary--";

        // Отправляем письмо
        if (mail('costarem@yandex.ru', 'Заказ с сайта "Module`s Source"', $body, $headers)) {
            // Отправка ответа клиенту (если необходимо)
            mail($email, 'Успешное оформление заказа на сайте "Module`s Source"', $message2, $headers2);

            echo json_encode(['success' => true]);

        } else {
            
            echo json_encode(['error' => 'Ошибка при отправке почты']);
        }
    } else {
        echo json_encode(['error' => 'Ошибка при сохранении изображения']);
    }
} else {
    header('Content-Type: application/json');
    // Если запрос не является POST, возвращаем ошибку
    echo json_encode(['error' => 'Метод не разрешен']);
}


?>