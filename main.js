// Функция для выполнения запроса и обновления названия организации
function updateOrganizationName() {
    const innValue = document.getElementById('inn').value;

    // Проверяем, что ИНН не пустой
    if (innValue) {
        // Выполняем запрос к API
        fetch(`https://api.ofdata.ru/v2/company?key=aDpj4oMDkuXTNXNr&inn=${innValue}`)
            .then(response => response.json())
            .then(data => {
                console.log('OK!!!', data);

                // Проверяем, есть ли необходимые данные в ответе
                if (data && data.data && data.data.НаимСокр) {
                    // Устанавливаем значение в поле "Название организации"
                    document.getElementById('title').value = data.data.НаимСокр;
                } else {
                    console.error('Отсутствуют необходимые данные в ответе API');
                }

                // Проверяем, есть ли необходимые данные в ответе
                if (data && data.data && data.data.ЮрАдрес.АдресРФ) {
                    // Устанавливаем значение в поле "Название организации"
                    document.getElementById('real_address').value = data.data.ЮрАдрес.АдресРФ;
                } else {
                    console.error('Отсутствуют необходимые данные в ответе API');
                }
            })
            .catch(error => {
                console.error('Ошибка запроса:', error);
            });
    } else {
        // Если ИНН пустой, очищаем поле "Название организации"
        document.getElementById('title').value = '';
    }
}

// Назначаем обработчик события input на поле ИНН
document.getElementById('inn').addEventListener('input', updateOrganizationName);

//Success log in

// autoForm.addEventListener('submit', function(event) {
//     event.preventDefault();

//     const formData = $(this).serialize();

//     jQuery.ajax({
//         method: 'POST',
//         url: './register/log-in.php',
//         data: formData,
//     }).done(function(msg){
//         // Сохраняем информацию об авторизации в localStorage
//         localStorage.setItem('user_authenticated', 'true');

//         buttonOpenRegisterBlock.style = 'display:none';
//         document.querySelector('.open-connect-form').style = 'display:block';
//         registerBlock.classList.remove('register-block--active'); 
//         document.querySelector('.main-cart').classList.add('main-cart--active');
//     })
// });

//Кнопка в таблице 

const tableButton = document.querySelectorAll('.table__block button');

tableButton.forEach(function(item){
    item.addEventListener('click', function(){
        item.style = 'background-color:green;';
    });
});

// Получаем все элементы с классом 'table__block stock'
const stockElements = document.querySelectorAll('.table__block.stock');

// Итерируем по коллекции и выводим значения
stockElements.forEach(function(stockElement) {
    const stockValue = stockElement.dataset.inStock;
    // console.log(stockValue);
});

//подсчет значения в Итоговой стоимости

function updateTotalPrice(inputIndex) {
    var quantity = inputElements[inputIndex].value;
    var priceElement = priceElements[inputIndex];

    var price;

    if (priceElement.classList.contains('table__block-price--multi')) {
        // Обработка для блока с несколькими ценами
        var price1 = parseFloat(priceElement.dataset.price1);
        var price2 = parseFloat(priceElement.dataset.price2);
        var price3 = parseFloat(priceElement.dataset.price3);
        var price4 = parseFloat(priceElement.dataset.price4);
        var price5 = parseFloat(priceElement.dataset.price5);

        var descriptions = [`<div><strong>от 1шт: ${price1}</strong> <br>
        от 10шт: ${price2} <br>
        от 100шт: ${price3} <br>
        от 1000шт: ${price4} <br>
        от 10000шт: ${price5}</div>`, `<div>от 1шт: ${price1} <br>
        <strong>от 10шт: ${price2}</strong> <br>
        от 100шт: ${price3} <br>
        от 1000шт: ${price4} <br>
        от 10000шт: ${price5}</div>`, `<div>от 1шт: ${price1} <br>
        от 10шт: ${price2} <br>
        <strong>от 100шт: ${price3}</strong> <br>
        от 1000шт: ${price4} <br>
        от 10000шт: ${price5}</div>`, `<div>от 1шт: ${price1} <br>
        от 10шт: ${price2} <br>
        от 100шт: ${price3} <br>
        <strong>от 1000шт: ${price4}</strong> <br>
        от 10000шт: ${price5}</div>`, `<div>от 1шт: ${price1} <br>
        от 10шт: ${price2} <br>
        от 100шт: ${price3} <br>
        от 1000шт: ${price4} <br>
        <strong>от 10000шт: ${price5}</strong></div>`,`от 1шт: ${price1} <br>
         от 10шт: ${price2} <br>
         от 100шт: ${price3} <br>
         от 1000шт: ${price4} <br>
         от 10000шт: ${price5}`];

        var descriptionIndex = -1;

        if (quantity >= 1 && quantity <= 9) {
            price = price1;
            descriptionIndex = 0;
        } else if (quantity >= 10 && quantity <= 99) {
            price = price2;
            descriptionIndex = 1;
        } else if (quantity >= 100 && quantity <= 999) {
            price = price3;
            descriptionIndex = 2;
        } else if (quantity >= 1000 && quantity <= 9999) {
            price = price4;
            descriptionIndex = 3;
        } else if (quantity >= 10000) {
            price = price5;
            descriptionIndex = 4;
        } else if (quantity == 0) {
            price = 0;
            descriptionIndex = 5;
        }

        if (descriptionIndex !== -1) {
            // Change the description based on the interval
            priceElement.innerHTML = descriptions[descriptionIndex];
        }

    } else {
        // Обработка для блока с одной ценой
        price = parseFloat(priceElement.innerText.replace(/[^\d.]/g, ''));
    }

    // Проверка, определена ли цена
    if (isNaN(price)) {
        console.error("Цена не определена для данного блока.");
        return;
    }

    var total = quantity * price;
    var formattedTotal = total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    spanElements[inputIndex].innerText = formattedTotal;
}

var inputElements = document.querySelectorAll('.table__block input:not(.table__block-quallity--po1):not(.table__block-quallity--po2):not(.table__block-quallity--po3)');
var priceElements = document.querySelectorAll('.table__block-price:not(.table__block-price--po1):not(.table__block-price--po2):not(.table__block-price--po3)');
var spanElements = document.querySelectorAll('.table__block span:not(.table__block--po1):not(.table__block--po2):not(.table__block--po3)');


inputElements.forEach(function (inputElement, index) {
    inputElement.addEventListener('input', function () {
        var inputValue = inputElement.value.replace(/[^\d]/g, '');
        var quantity = parseInt(inputValue, 10);

        var stockElement = stockElements[index];
        var stockValue = stockElement.dataset.inStock;
        var stockQuantity = (inputElement.value.trim() === '' ? '' : (isNaN(stockValue) ? 0 : parseInt(stockValue, 10)));

        if (Number.isInteger(quantity) && quantity >= 0 && quantity <= stockQuantity) {
            inputElement.value = quantity;
            updateTotalPrice(index);
        } else {
            inputElement.value = stockQuantity;
            updateTotalPrice(index);
            console.error("Введите положительное целое число, не превышающее значение в блоке stock.");
        }
    });
});

//скрытие блоков с нулевыми ценами 

document.addEventListener('DOMContentLoaded', function() {
    // Получаем все элементы с классом table__block-price
    var priceBlocks = document.querySelectorAll('.table__block-price');

    // Счетчик видимых строк
    var visibleRowCount = 0;

    // Проходимся по каждому элементу
    priceBlocks.forEach(function(priceBlock) {
        // Получаем значение из блока
        var priceValue = parseFloat(priceBlock.textContent.trim());

        // Получаем родительский элемент с классом table__row
        var row = priceBlock.closest('.table__row');

        // Получаем значение блока stock в текущей строке
        var stockValue = row.querySelector('.stock').textContent.trim();

        // Проверяем, равно ли значение 0, больше 9998 или "под заказ"
        if (priceValue === 0 || priceValue > 9998 || stockValue.toLowerCase() === 'под заказ' || stockValue.toLowerCase() === '' || stockValue.toLowerCase() === 'по запросу') {
            // Проверяем, существует ли родительский элемент, и скрываем его
            if (row) {
                row.style.display = 'none';
            }
        } else {
            // Увеличиваем счетчик видимых строк
            visibleRowCount++;
        }

        // Получаем элемент с классом table__row--header
        var headerRow = document.querySelector('.table__row--header');

        // Проверяем счетчик видимых строк и скрываем/показываем заголовок
        if (visibleRowCount === 0) {
            if (headerRow) {
                headerRow.style.display = 'none';
                document.querySelector('.non-alert-block').classList.add('non-alert-block--active');
            }
        } else {
            if (headerRow) {
                document.querySelector('.non-alert-block').classList.remove('non-alert-block--active')
                document.querySelector('.cta').style.display = "none";
                document.querySelector('.suppliers').style.display = "none";
                document.querySelector('.clients').style.display = "none";
                document.querySelector('.reviews').style.display = "none";
                document.querySelector('#table').style.display = "block";
                // document.querySelector('html').style.height = "100%";
                // document.querySelector('body').style.height = "100%";
                headerRow.style.display = 'flex';

            }
        }
    });

    priceBlocks.forEach(function(priceBlock) {
        var priceValue = parseFloat(priceBlock.textContent.trim());
        var row = priceBlock.closest('.table__row');
        var firstChildValue = parseFloat(row.querySelector('.table__block:first-child').textContent.trim()); // Получаем значение первого дочернего элемента
    
        if (priceValue === -1.23 || priceValue === -1.15) {
            if (row) {
                row.style.display = 'none';
                var headerRow = document.querySelector('.table__row--header');
                if (headerRow) {
                    headerRow.style.display = 'none';
                    document.querySelector('.non-alert-block').classList.remove('non-alert-block--active');
                    document.querySelector('.cta').style.display = "block";
                    document.querySelector('.suppliers').style.display = "block";
                    document.querySelector('.clients').style.display = "block";
                    document.querySelector('.reviews').style.display = "block";
                }
            }
        }
    });
});

//в корзину

// Извлечение данных о корзине из localStorage при загрузке страницы
var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

function addToCart(button) {
    try {
        var row = button.closest('.table__row');
        if (!row) {
            throw new Error('Unable to find parent row');
        }

        var quantityInput = row.querySelector('.table__block:nth-child(7) input');
        var name = row.querySelector('.table__block:nth-child(1)').innerText;
        var quantity = quantityInput.value;
        var fabricator = row.querySelector('.table__block:nth-child(4)').innerText;
        var deliveryDate = row.querySelector('.table__block:nth-child(3)').innerText;
        var price = row.querySelector('.table__block:nth-child(6)').innerText;
        var priceOneElement = row.querySelector('.table__block:nth-child(5)');
        var rowIndex = Array.from(row.parentNode.children).indexOf(row); // Получаем номер ряда
        var rowIdentifier;
        
        // Устанавливаем новое значение в зависимости от номера ряда
        switch (rowIndex) {
            case 1:
                rowIdentifier = "Триема";
                break;
            case 2:
                rowIdentifier = "База электроники";
                break;
            case 3:
                rowIdentifier = "Ali";
                break;
            case 4:
                rowIdentifier = "Cathy-Zhong";
                break;
            case 5:
                rowIdentifier = "Module";
                break;
            case 6:
                rowIdentifier = "LCSC";
                break;
            case 7:
                rowIdentifier = "LCSC";
                break;
            case 8:
                rowIdentifier = "LCSC";
                break;
            default:
                rowIdentifier = rowIndex; // По умолчанию используем номер ряда
                break;
        }

        if (!name || !deliveryDate || !price) {
            throw new Error('Missing required data');
        }

        // Получение цены с учетом класса table__block-price--multi или table__block-price--po
        var priceOne;
        if (priceOneElement.classList.contains('table__block-price--multi')) {
            priceOne = priceOneElement.dataset.price1;
        } else if (priceOneElement.classList.contains('table__block-price--po')) {
            priceOne = priceOneElement.dataset.meta;
        } else {
            priceOne = priceOneElement.innerText;
        }

        // Обработчик события изменения значения в input
        quantityInput.addEventListener('input', function() {
            // Обновляем значение переменной quantity при изменении input
            quantity = quantityInput.value;
        });

        // Проверка значения перед добавлением в корзину
        if (quantity.trim() === '' || parseInt(quantity) === 0 || parseInt(price) === 0) {
            throw new Error('Количество выбранного Вами товара или его стоимость равно 0');
        }
        
        // Проверка, есть ли товар уже в корзине
        var existingItem = cartItems.find(item => item.name === name && item.fabricator === fabricator);
        // var existingItem = cartItems.find(item => item.name === name);

        if (existingItem) {
            // Товар уже добавлен в корзину, вывести сообщение
            alert('Товар уже добавлен в корзину');
            return; // Прерываем выполнение функции
        }

        var item = {
            name: name,
            quantity: quantity,
            fabricator: fabricator,
            price: price,
            deliveryDate: deliveryDate,
            priceOne: priceOne,
            rowIndex: rowIdentifier
        };

        cartItems.push(item);
        

        // Сохранение данных о корзине в localStorage
        localStorage.setItem('cartItems', JSON.stringify(cartItems));

        // Обновление интерфейса (пример: изменение цвета кнопки)
        button.style.backgroundColor = 'green';

        // Уведомление о добавлении
        
        // Создание элемента для уведомления
        var notification = document.getElementById('notification');
        if (!notification) {
            notification = document.createElement('div');
            notification.id = 'notification';
            document.body.appendChild(notification);
        }

        // Установка текста уведомления
        notification.innerText = 'Добавлено ' + name + ' ' + quantity + ' шт.';

        // Показываем уведомление
        notification.style.display = 'block';

        // Задержка в 2 секунды перед скрытием уведомления
        setTimeout(function () {
            notification.style.display = 'none';
        }, 2000);
    } catch (error) {
        console.error('Error adding to cart:', error.message);
        // Обработка ошибок (пример: вывод сообщения пользователю)
        alert(error.message);
    }
}

function goToCart() {
    // Переход на страницу cart.php с передачей данных через URL
    var params = encodeURIComponent(JSON.stringify(cartItems));
    window.location.href = 'cart.php?items=' + params;
}

//кнопка удалить в корзине

// Обработчик событий для кнопки "Удалить"
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('table__block--delete')) {
        // Находим ближайший родительский элемент с классом table__row
        var row = event.target.closest('.table__row');

        // Удаляем ряд из DOM
        row.remove();

        // Обновляем данные в cartItems и localStorage
        updateCartItems();
    }
});

function updateCartItems() {
    // Обновление данных в массиве и localStorage
    cartItems = [];

    // Получаем все ряды таблицы
    var rows = document.querySelectorAll('.table__row:not(:first-child)');

    // Проходимся по каждому ряду и добавляем данные в массив
    rows.forEach(function(row) {
        var name = row.querySelector('.table__block:nth-child(2)').innerText;
        var quantity = row.querySelector('.table__block:nth-child(4)').innerText;
        var fabricator = row.querySelector('.table__block:nth-child(3)').innerText;
        var price = row.querySelector('.table__block:nth-child(7)').innerText;
        var priceOne = row.querySelector('.table__block:nth-child(6)').innerText;
        var deliveryDate = row.querySelector('.table__block:nth-child(5)').innerText;
        var rowIndex = row.querySelector('.table__block:nth-child(1)').innerText;

        var item = {
            rowIndex: rowIndex,
            name: name,
            quantity: quantity,
            fabricator: fabricator,
            price: price,
            priceOne: priceOne,
            deliveryDate: deliveryDate
        };

        cartItems.push(item);
    });
    
    // Сохранение данных о корзине в localStorage
    localStorage.setItem('cartItems', JSON.stringify(cartItems));
    
    // Обновление общей стоимости и максимального срока поставки на странице
    updateTotalsOnPage();
    

}

// Form Cart Radio Buttons

document.addEventListener('DOMContentLoaded', function() {
    const legalPersonRadio = document.getElementById('legal-person');
    const physicalPersonRadio = document.getElementById('physical-person');
    const formFields = document.querySelectorAll('.form-data input[type="text"], .form-data input[type="number"], .form-data input[type="email"]');
    const hiddenSaleDiv = document.querySelector('.hidden-sale');
    let blurred = true;
    let initialFieldsHidden = false;

    // Переключение между юридическими и физическими лицами
    legalPersonRadio.addEventListener('change', toggleBlur);
    physicalPersonRadio.addEventListener('change', toggleBlur);

    function toggleBlur() {
        if (blurred) {
            document.querySelector('.form-data').classList.remove('blur');
            blurred = false;
        }

        if (this === physicalPersonRadio) {
            if (this.checked) {
                for (let i = 0; i < 3; i++) {
                    formFields[i].style.display = 'none';
                }
                hiddenSaleDiv.style.display = 'block';
                initialFieldsHidden = true;
            }
        } else {
            if (initialFieldsHidden) {
                for (let i = 0; i < 3; i++) {
                    formFields[i].style.display = 'block';
                }
                hiddenSaleDiv.style.display = 'none';
                initialFieldsHidden = false;
            }
        }

        // Пересчет общей стоимости при изменении типа лица
        document.getElementById('totalPrice').innerText = ''; // Очистить общую стоимость перед пересчетом
        document.getElementById('maxDelivery').innerText = ''; // Очистить максимальный срок доставки перед пересчетом
        document.getElementById('totalWithExtra').innerText = ''; // Очистить общую стоимость с доп. суммой перед пересчетом
        document.getElementById('deliverySum').innerText = ''; // Очистить стоимость доставки перед пересчетом
        updateTotalsOnPage(); // Вызвать функцию для пересчета общей стоимости и других значений на странице
    }
});

function updateTotalsOnPage() {
    // Рассчитываем общую стоимость и максимальный срок поставки
    var totalPrice = 0;
    var maxDelivery = 0;

    // Проходимся по каждому элементу в массиве cartItems и обновляем значения
    cartItems.forEach(function(item) {
        var itemPriceWithoutSpaces = item.price.replace(/\s/g, '');
        totalPrice += parseFloat(itemPriceWithoutSpaces); // Преобразуем строку в число
        var deliveryParts = item.deliveryDate.split('-');
        maxDelivery = Math.max(maxDelivery, parseInt(deliveryParts[deliveryParts.length - 1]));
    });

    // Добавляем фиксированную сумму 1000 к общей стоимости
    var totalWithExtra = (totalPrice > 0) ? (totalPrice + 1000) : 0;

    // Если выбраны физические лица, рассчитываем общую стоимость с учетом скидки 18%
    if (document.getElementById('physical-person').checked) {
        // totalPrice *= 0.82;
        // totalWithExtra *= 0.82;
        totalWithExtra = totalPrice * 0.82 + 1000;
        totalWithExtra = parseFloat(totalWithExtra.toFixed(2));
    }
    
    

    // Обновляем значения на странице с правильным форматированием
    // document.getElementById('totalPrice').innerText = 'Стоимость заказа: ' + totalPrice.toLocaleString('ru-RU') + ' руб. (включая НДС 20%)';
    document.getElementById('totalPrice').innerText = 'Стоимость заказа: ' + totalPrice.toLocaleString('ru-RU') + ((document.getElementById('physical-person').checked) ? ' руб.' : ' руб. (включая НДС 20%)');
    document.getElementById('maxDelivery').innerText = 'Срок поставки: ' + maxDelivery + ' ' + getDeliveryText(maxDelivery);
    // document.getElementById('totalWithExtra').innerText = 'Общая стоимость: ' + totalWithExtra.toLocaleString('ru-RU') + ' руб. (включая НДС 20%)';
    document.getElementById('totalWithExtra').innerText = 'Общая стоимость: ' + totalWithExtra.toLocaleString('ru-RU') + ((document.getElementById('physical-person').checked) ? ' руб.' : ' руб. (включая НДС 20%)');

    // Добавляем блок кода для обновления блока с id 'deliverySum'
    var deliverySumElement = document.getElementById('deliverySum');

    if (totalPrice === 0) {
        // Если totalPrice равно 0, устанавливаем значение блока deliverySum
        deliverySumElement.innerText = 'Cтоимость доставки: 0';
    } else {
        // Если totalPrice больше 0, устанавливаем значение блока deliverySum с учетом НДС и дополнительной суммы
        // deliverySumElement.innerText = 'Cтоимость доставки: ' + (1000).toLocaleString('ru-RU') + ' руб. (включая НДС 20%)';
        deliverySumElement.innerText = 'Cтоимость доставки: ' + ((document.getElementById('physical-person').checked) ? '1000 руб.' : (1000).toLocaleString('ru-RU') + ' руб. (включая НДС 20%)');
    }
}

updateTotalsOnPage();

function getDeliveryText(days) {
    // Возвращает правильный текст для отображения срока поставки
    // Вам, возможно, придется настроить это в соответствии с вашими требованиями
    return (days === 1) ? 'неделя' : (days < 5) ? 'недели' : 'недели';
}

// изменение количества в корзине и пересчет суммы

document.addEventListener("DOMContentLoaded", function() {
    var tableRows = document.querySelectorAll(".table__row");

    tableRows.forEach(function(row) {
        var quantityInput = row.querySelector(".table__block-data-quallity input");
        var priceOneElement = row.querySelector(".table__block-data-one");
        var totalPriceBlock = row.querySelector(".table__block-data-price");

        if (quantityInput && priceOneElement && totalPriceBlock) {
            var priceOne = parseFloat(priceOneElement.textContent);

            // Проверка на NaN и замена на 150
            if (isNaN(priceOne)) {
                priceOne = 151.8;
            }

            quantityInput.addEventListener("input", function() {
                var quantity = parseInt(quantityInput.value);

                // Проверка на отрицательное значение
                if (quantity < 1) {
                    quantity = 1;
                    quantityInput.value = quantity;
                }

                var total = quantity * priceOne;

                // Если в инпуте стерли значение, устанавливаем цену в 0.00
                if (isNaN(quantity)) {
                    quantity = "1";
                    quantityInput.value = quantity;
                    total = priceOne * 1;
                }

                totalPriceBlock.textContent = total.toFixed(2);
                
                
                updateCartItems();
                
                // // Обновление общей стоимости и максимального срока поставки на странице
                // updateTotalsOnPage();
                
                // // Сохранение данных о корзине в localStorage
                // localStorage.setItem('cartItems', JSON.stringify(cartItems));
                
            });
        }
    });
});




  