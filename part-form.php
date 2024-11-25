<?php

// Функция для очистки и проверки данных
function cleanInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Проверяем, была ли отправлена форма
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверяем каждое поле на пустоту
    if (empty($_POST["part-title"]) || empty($_POST["part-qual"]) || empty($_POST["part-price"]) || empty($_POST["part-inn"]) || empty($_POST["part-name"]) || empty($_POST["part-phone"]) || empty($_POST["part-email"]) || empty($_POST["part-message"])) {
        echo "Пожалуйста, заполните все поля.";
        exit;
    } else {
        // Очищаем и проверяем каждое поле
        $partTitle = cleanInput($_POST['part-title']);
        $partQual = cleanInput($_POST['part-qual']);
        $partPrice = cleanInput($_POST['part-price']);
        $partInn = cleanInput($_POST['part-inn']);
        $partName = cleanInput($_POST['part-name']);
        $partPhone = cleanInput($_POST['part-phone']);
        $partEmail = cleanInput($_POST['part-email']);
        $partMessage = cleanInput($_POST['part-message']);

        // Формируем сообщение
        $mailMessage = "Название ($partTitle)\nКоличество ($partQual) шт.\nЖелаемая цена ($partPrice) руб.\nФИО ($partName)\nТелефон ($partPhone)\nEmail ($partEmail)\nИНН ($partInn)\nСообщение ($partMessage)";

        // Остальной код остается неизменным
        $headers = 'From: modulesource.ru';
        $headers .= "\r\nMIME-Version: 1.0";

        $message = "Здравствуйте!\n\nВаш запрос на позицию $partTitle\nпередан на подтверждение инженеру, который в самые кратчайшие сроки отправит Вам ответ на указанный email.\n\nБлагодарим за оказанное доверие! Хорошего дня!\n\nВ случае возникновения вопросов, пожалуйста, обращайтесь к нам любым удобным способом:\n\nТелефон:\n+7(499)288-04-26\n+7(999)125-14-12\n\nEmail: order@modulesource.ru\n\nС Уважением, ООО «Модуль Соурс»\nmodulesource.ru";

        $partMail = mail('costarem@yandex.ru', 'Не найден нужный товар', $mailMessage, $headers);

        if ($partMail) {
            mail($partEmail, 'Заявка на товар', $message, $headers);
            header('Location: ./index.php');
        } else {
            echo "При отправке письма произошла ошибка.";
        }
    }
} else {
    echo "Доступ запрещен.";
}


// $partTitle = $_POST['part-title'];
// $partQual = $_POST['part-qual'];
// $partPrice = $_POST['part-price'];
// $partInn = $_POST['part-inn'];
// $partName = $_POST['part-name'];
// $partPhone = $_POST['part-phone'];
// $partEmail = $_POST['part-email'];
// $partMessage = $_POST['part-message'];

// $mailMessage = "Название ($partTitle)\nКоличество ($partQual) шт.\nЖелаемая цена ($partPrice) руб.\nФИО ($partName)\nТелефон ($partPhone)\nEmail ($partEmail)\nИНН ($partInn)\nСообщение ($partMessage)";

// $headers = 'From: modulesource.ru';
// $headers .= "\r\nMIME-Version: 1.0";

// $message = "Здравствуйте!\n\nВаш запрос на позицию $partTitle\nпередан на подтверждение инженеру, который в самые кратчайшие сроки отправит Вам ответ на указанный email.\n\nБлагодарим за оказанное доверие! Хорошего дня!\n\nВ случае возникновения вопросов, пожалуйста, обращайтесь к нам любым удобным способом:\n\nТелефон:\n+7(499)288-04-26\n+7(999)125-14-12\n\nEmail: order@modulesource.ru\n\nС Уважением, ООО «Модуль Соурс»\nmodulesource.ru";

// $partMail = mail('costarem@yandex.ru', 'Не найден нужный товар', $mailMessage, $headers);

// if($partMail){
//     mail ($partEmail,'Заявка на товар', $message, $headers);
//     header('Location: ./index.php');
// }

?>