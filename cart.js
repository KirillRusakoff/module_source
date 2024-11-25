const finbtn = document.querySelector('#finally-button');
const finBlock = document.querySelector('.finally-alert-block');
const finBlockBtn = document.querySelector('.finally-alert-block button');

finbtn.addEventListener('click', function() {
    // Проверка наличия товаров в корзине
    const tableRows = document.querySelectorAll('.table__row:not(.table__row.table__row--first)');
    if (tableRows.length === 0) {
        alert("У вас нет товаров для оформления.");
        return;
    }
    
    const formData = new FormData(document.querySelector('.form-data'));
    
    // Проверка заполненности обязательных полей
    if (!validateForm()) {
        highlightRequiredFields();
        return;
    }

    // Получение данных из таблицы
    const tableData = getTableData();
    formData.append('tableData', tableData);

    // Отправка изображения
    html2canvas(document.querySelector('#cartTable')).then(canvas => {
        const imageData = canvas.toDataURL('image/png');
        formData.append('imageData', imageData);

        // Отправка данных на сервер
        fetch('./imager.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            // Обработка ответа от сервера (если необходимо)
            console.log(data);
        })
        .catch(error => {
            console.error('Ошибка:', error);

            if (error.text) {
                return error.text().then(errorMessage => {
                    console.log('Текст ошибки:', errorMessage);
                });
            } else {
                console.log('Ошибка не содержит текста.');
            }
        });
    });

    finBlock.classList.add('finally-alert-block--active');
});

// Дополнительные события для блока с подтверждением
finBlockBtn.addEventListener('click', function(){
    finBlock.classList.remove('finally-alert-block--active');
    clearCart();

    function clearCart() {
    // Находим все ряды с классом 'table__row' и удаляем их
    var rows = document.querySelectorAll('.table__row:not(.table__row--first)');
    rows.forEach(function(row) {
        row.remove();
    });

    // Очищаем все input в форме с классом 'form-data'
    var formDataInputs = document.querySelectorAll('.form-data input');
    formDataInputs.forEach(function(input) {
        input.value = ''; // Очищаем значение инпута
    });

    // Обновляем данные в cartItems и localStorage
    updateCartItems();
}
});

const formElement = document.querySelector('.form-data');

// Функция для проверки заполненности обязательных полей
function validateForm() {
    const requiredInputs = formElement.querySelectorAll('[data-required]');
    
    for (const input of requiredInputs) {
        // Проверяем, что поле видимо и не скрыто
        if (input.offsetParent !== null && input.value.trim() === '') {
            return false;
        }
    }

    return true;
}


// Функция для выделения незаполненных обязательных полей
function highlightRequiredFields() {
    const requiredInputs = formElement.querySelectorAll('[data-required]');
    
    for (const input of requiredInputs) {
        if (input.value.trim() === '') {
            input.style.outline = '2px solid red';
        }
    }

    setTimeout(() => {
        for (const input of requiredInputs) {
            input.style.outline = ''; // Сброс выделения через 2 секунды
        }
    }, 2000);

    // Показываем блок с сообщением
    const errorMessageBlock = document.createElement('div');
    errorMessageBlock.textContent = 'Заполните обязательные поля!';
    errorMessageBlock.style.color = 'white';
    errorMessageBlock.style.background = 'red';
    errorMessageBlock.style.padding = '10px';
    errorMessageBlock.style.position = 'fixed';
    errorMessageBlock.style.top = '10px';
    errorMessageBlock.style.left = '50%';
    errorMessageBlock.style.transform = 'translateX(-50%)';
    errorMessageBlock.style.zIndex = '9999';

    document.body.appendChild(errorMessageBlock);

    setTimeout(() => {
        errorMessageBlock.remove(); // Скрытие блока с сообщением через 2 секунды
    }, 2000);
}

// Функция для получения данных из таблицы
function getTableData() {
    const tableRows = document.querySelectorAll('.table__row');
    let tableData = '';

    tableRows.forEach(row => {
        const cells = row.querySelectorAll('.table__block-data:not(.table__block-data.table__block-data-one)');
        
        cells.forEach(cell => {
            let value = '';
            // Проверяем, есть ли в ячейке input
            const inputs = cell.querySelectorAll('.table__block-data input');
            if (inputs.length > 0) {
                inputs.forEach(input => {
                    value += input.value + ' '; // Добавляем значение input к переменной value
                });
            } else {
                value = cell.textContent.trim(); // Если нет input, то берем текстовое содержимое ячейки
            }
            tableData += value + '\t'; // Или используйте другой разделитель
        });
        tableData += '\n';
    });

    return tableData;
}



document.getElementById('search').addEventListener('submit', function(event) {
    event.preventDefault(); // Предотвращаем стандартное действие отправки формы

    // Получаем данные из формы
    var formData = new FormData(this);

    // Собираем параметры запроса GET
    var queryParams = new URLSearchParams(formData).toString();

    // Перенаправляем пользователя на index.php с параметрами запроса
    window.location.href = 'index.php?' + queryParams;
});

//Loader

const buttonLoader = document.querySelector('.main__search-button');
const loader = document.querySelector('.loader-wrapper');
const blockBody = document.querySelector('#body');
const blockMain = document.querySelector('.main');

buttonLoader.addEventListener('click', function(){
    loader.style.display = 'flex';
    blockMain.style.display = 'none';
    
    // Проверяем ширину экрана
    if (window.innerWidth > 599) {
        blockBody.style.height = '100vh';
    }
});

//None part form

const partButton = document.querySelectorAll('.hidden-form-text__button');
const partForm = document.querySelector('.hidden-form-text');
const partFormCross = document.querySelector('.hidden-form-text__middle-cross');


partButton.forEach(function(item){
    item.addEventListener('click', function(){
        partForm.classList.add('hidden-form-text--active');
    })
});

partFormCross.addEventListener('click', function(){
    partForm.classList.remove('hidden-form-text--active');
});

const formAlert = document.querySelector('.form-alert-block');
const formAlertButtonCross = document.querySelector('.form-alert-block__middle button');

$(document).ready(function(){
    $('.hidden-form-text__middle').submit(function(e){
        e.preventDefault(); // Предотвращаем отправку формы по умолчанию

        // Отправка данных формы через AJAX
        $.ajax({
            type: 'POST',
            url: 'part-form2.php',
            data: $(this).serialize(), // Сериализуем данные формы
            success: function(response){
                // Добавьте обработку успешного ответа здесь (если необходимо)
                partForm.classList.remove('hidden-form-text--active');
                formAlert.classList.add('form-alert-block--active');
                document.querySelector('.non-alert-block').classList.remove('non-alert-block--active');
            },
            error: function(error){
                // Обработка ошибок при отправке AJAX-запроса
                console.log('Ошибка при отправке формы: ', error);
            }
        });
    });
});

formAlertButtonCross.addEventListener('click', function(){
    formAlert.classList.remove('form-alert-block--active');
});

//Polic

const politicButton = document.querySelector('.politic-button');
const politicBlock = document.querySelector('.hidden-politic');
const politicCross = document.querySelector('.hidden-politic__cross');

politicButton.addEventListener('click', function(){
    politicBlock.classList.add('hidden-politic--active');
});

politicCross.addEventListener('click', function(){
    politicBlock.classList.remove('hidden-politic--active');
});