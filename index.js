const swiper = new Swiper('#suppliers', {
    direction: 'horizontal',
    loop: true,
    speed: 4000,
    centeredSlides: true,
    centerInsufficientSlides: true,
    slidesPerView: '5',
    autoplay: {
        delay: 0,
        disableOnInteraction: false,
    },
    allowTouchMove: false,
});

const swiper2 = new Swiper('#clients', {
    direction: 'horizontal',
    loop: true,
    slidesPerView: "3",
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
});

const swiper3 = new Swiper('#reviews', {
    direction: 'horizontal',
    loop: true,
    zoom: false,
    slidesPerView: "2",
});

//если не введен парт номер в инпут

const nonBlock = document.querySelector('.non-alert-block');
const nonBlockBtn = document.querySelector('.non-alert-block button');

function validateForm() {
        var searchInput = document.getElementById('searchInput').value;

        if (searchInput.trim() === "") {
            nonBlock.classList.add('non-alert-block--active');

            return false; // Отменить отправку формы
        }

        // Если значение не пусто, форма будет отправлена
        // Можно также добавить дополнительную логику или отправить запрос на сервер
        return true;
}

nonBlockBtn.addEventListener('click', function(){
    nonBlock.classList.remove('non-alert-block--active');
});

// //Loader

const buttonLoader = document.querySelector('.main__search-button');
const loader = document.querySelector('.loader-wrapper');
const blockCta = document.querySelector('.cta');
const blockSuppliers = document.querySelector('.suppliers');
const blockClients = document.querySelector('.clients');
const blockReviews = document.querySelector('.reviews');
const blockTable = document.querySelector('#table');
const blockBody = document.querySelector('#body');
const blockMain = document.querySelector('.main__wrapper');

buttonLoader.addEventListener('click', function(){
    loader.style.display = 'flex';
    blockCta.style.display = 'none';
    blockSuppliers.style.display = 'none';
    blockClients.style.display = 'none';
    blockReviews.style.display = 'none';
    blockTable.style.display = 'none';

    // Проверяем ширину экрана
    if (window.innerWidth > 599) {
        blockBody.style.height = '100vh';
    }
});

// buttonLoader.addEventListener('click', function(){
//     loader.style.display = 'flex';
//     blockCta.style.display = 'none';
//     blockSuppliers.style.display = 'none';
//     blockClients.style.display = 'none';
//     blockReviews.style.display = 'none';
//     blockTable.style.display = 'none';
//     blockBody.style = 'height:100vh';
// });

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
            url: 'part-form.php',
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

//Транслит формы поиска и отправка

function translateToEnglish() {
            var searchInput = document.getElementById('searchInput').value;

            if (searchInput.trim() === "") {
                nonBlock.classList.add('non-alert-block--active');

                return false; // Отменить отправку формы
            }
            
            var inputElement = document.getElementById('searchInput');
            var inputValue = inputElement.value;

            // Словарь для замены русских букв на английские
            var translationMap = {
                'А': 'A',
                'В': 'B',
                'С': 'C',
                'Е': 'E',
                'Н':'H',
                'К':'K',
                'М':'M',
                'О':'O',
                'Р':'P',
                'Т':'T',
            };

            // Проход по каждому символу ввода и замена русских букв на английские
            for (var rusLetter in translationMap) {
                var engLetter = translationMap[rusLetter];
                var regExp = new RegExp(rusLetter, 'ig');
                inputValue = inputValue.replace(regExp, engLetter);
            }

            // Установка нового значения в поле ввода
            inputElement.value = inputValue;

            // Теперь можно отправить форму на сервер
            document.getElementById('search').submit();
        }
        
//работа со значениями стоимости в блоках из ПО

let xxx = [];
        
var MPOPR = document.querySelectorAll('.table__block-price--po');

MPOPR.forEach(function(item){
    let i = item.getAttribute('data-attr');
    xxx.push(i);
});

// Функция для обработки каждой строки
function processString(str) {
  // Используем регулярное выражение для извлечения чисел и точек
  const numbers = str.match(/[0-9.]+/g);
  // Преобразуем массив чисел в строку, разделяя их запятой
  return numbers.join(',');
}

// Проходим по каждой строке массива и обрабатываем ее
const processedArray = xxx.map(processString);

const [var1, var2, var3] = processedArray;

var mvar1 = var1.split(",").map(parseFloat);

// console.log(mvar1);

var mvar2 = var2.split(",").map(parseFloat);

// console.log(mvar2);

var mvar3 = var3.split(",").map(parseFloat);

// console.log(mvar3);

var inputElement1 = document.querySelector('.table__block-quallity--po1 input');
var result1 = document.querySelector('.table__block--po1 span');
var blockPrice1 = document.querySelector('.table__block-price--po1');

// Добавляем обработчик события 'input' на элемент input
inputElement1.addEventListener('input', function() {
    // Получаем текущее значение из элемента input
    var inputValue1 = inputElement1.value;

    if( inputValue1 < mvar1[0] ){
        result1.innerText = (inputValue1 * 0).toFixed(2);
        blockPrice1.innerHTML = `<div>от ${mvar1[0]}шт: ${mvar1[1]}<br>
        от ${mvar1[2]}шт: ${mvar1[3]} <br>
        от ${mvar1[4]}шт: ${mvar1[5]} <br>
        от ${mvar1[6]}шт: ${mvar1[7]} <br>
        от ${mvar1[8]}шт: ${mvar1[9]} <br>
        от ${mvar1[10]}шт: ${mvar1[11]}</div>`; 
    } else if(mvar1[0] <= inputValue1 && inputValue1 < mvar1[2]) {
        result1.innerText = (inputValue1 * mvar1[1]).toFixed(2);
        blockPrice1.innerHTML = `<div><strong>от ${mvar1[0]}шт: ${mvar1[1]}</strong> <br>
        от ${mvar1[2]}шт: ${mvar1[3]} <br>
        от ${mvar1[4]}шт: ${mvar1[5]} <br>
        от ${mvar1[6]}шт: ${mvar1[7]} <br>
        от ${mvar1[8]}шт: ${mvar1[9]} <br>
        от ${mvar1[10]}шт: ${mvar1[11]}</div>`;
    } else if(mvar1[2] <= inputValue1 && inputValue1 < mvar1[4]) {
        result1.innerText = (inputValue1 * mvar1[3]).toFixed(2);
        blockPrice1.innerHTML = `<div>от ${mvar1[0]}шт: ${mvar1[1]} <br>
        <strong>от ${mvar1[2]}шт: ${mvar1[3]}</strong> <br>
        от ${mvar1[4]}шт: ${mvar1[5]} <br>
        от ${mvar1[6]}шт: ${mvar1[7]} <br>
        от ${mvar1[8]}шт: ${mvar1[9]} <br>
        от ${mvar1[10]}шт: ${mvar1[11]}</div>`;
    } else if(mvar1[4] <= inputValue1 && inputValue1 < mvar1[6]) {
        result1.innerText = (inputValue1 * mvar1[5]).toFixed(2);
        blockPrice1.innerHTML = `<div>от ${mvar1[0]}шт: ${mvar1[1]} <br>
        от ${mvar1[2]}шт: ${mvar1[3]} <br>
        <strong>от ${mvar1[4]}шт: ${mvar1[5]}</strong> <br>
        от ${mvar1[6]}шт: ${mvar1[7]} <br>
        от ${mvar1[8]}шт: ${mvar1[9]} <br>
        от ${mvar1[10]}шт: ${mvar1[11]}</div>`;
    } else if(mvar1[6] <= inputValue1 && inputValue1 < mvar1[8]) {
        result1.innerText = (inputValue1 * mvar1[7]).toFixed(2);
        blockPrice1.innerHTML = `<div>от ${mvar1[0]}шт: ${mvar1[1]} <br>
        от ${mvar1[2]}шт: ${mvar1[3]} <br>
        от ${mvar1[4]}шт: ${mvar1[5]} <br>
        <strong>от ${mvar1[6]}шт: ${mvar1[7]}</strong> <br>
        от ${mvar1[8]}шт: ${mvar1[9]} <br>
        от ${mvar1[10]}шт: ${mvar1[11]}</div>`;
    } else if(mvar1[8] <= inputValue1 && inputValue1 < mvar1[10]) {
        result1.innerText = (inputValue1 * mvar1[9]).toFixed(2);
        blockPrice1.innerHTML = `<div>от ${mvar1[0]}шт: ${mvar1[1]} <br>
        от ${mvar1[2]}шт: ${mvar1[3]} <br>
        от ${mvar1[4]}шт: ${mvar1[5]} <br>
        от ${mvar1[6]}шт: ${mvar1[7]} <br>
        <strong>от ${mvar1[8]}шт: ${mvar1[9]}</strong> <br>
        от ${mvar1[10]}шт: ${mvar1[11]}</div>`;
    } else {
        result1.innerText = (inputValue1 * mvar1[11]).toFixed(2);
        blockPrice1.innerHTML = `<div>от ${mvar1[0]}шт: ${mvar1[1]} <br>
        от ${mvar1[2]}шт: ${mvar1[3]} <br>
        от ${mvar1[4]}шт: ${mvar1[5]} <br>
        от ${mvar1[6]}шт: ${mvar1[7]} <br>
        от ${mvar1[8]}шт: ${mvar1[9]} <br>
        <strong>от ${mvar1[10]}шт: ${mvar1[11]}</strong></div>`;
    }
});

var inputElement2 = document.querySelector('.table__block-quallity--po2 input');
var result2 = document.querySelector('.table__block--po2 span');
var blockPrice2 = document.querySelector('.table__block-price--po2');

// Добавляем обработчик события 'input' на элемент input
inputElement2.addEventListener('input', function() {
    // Получаем текущее значение из элемента input
    var inputValue2 = inputElement2.value;

     if( inputValue2 < mvar2[0] ){
        result2.innerText = (inputValue2 * 0).toFixed(2);
        blockPrice2.innerHTML = `<div>от ${mvar2[0]}шт: ${mvar2[1]}<br>
        от ${mvar2[2]}шт: ${mvar2[3]} <br>
        от ${mvar2[4]}шт: ${mvar2[5]} <br>
        от ${mvar2[6]}шт: ${mvar2[7]} <br>
        от ${mvar2[8]}шт: ${mvar2[9]} <br>
        от ${mvar2[10]}шт: ${mvar2[11]}</div>`; 
    } else if(mvar2[0] <= inputValue2 && inputValue2 < mvar2[2]) {
        result2.innerText = (inputValue2 * mvar2[1]).toFixed(2);
        blockPrice2.innerHTML = `<div><strong>от ${mvar2[0]}шт: ${mvar2[1]}</strong> <br>
        от ${mvar2[2]}шт: ${mvar2[3]} <br>
        от ${mvar2[4]}шт: ${mvar2[5]} <br>
        от ${mvar2[6]}шт: ${mvar2[7]} <br>
        от ${mvar2[8]}шт: ${mvar2[9]} <br>
        от ${mvar2[10]}шт: ${mvar2[11]}</div>`;
    } else if(mvar2[2] <= inputValue2 && inputValue2 < mvar2[4]) {
        result2.innerText = (inputValue2 * mvar2[3]).toFixed(2);
        blockPrice2.innerHTML = `<div>от ${mvar2[0]}шт: ${mvar2[1]} <br>
        <strong>от ${mvar2[2]}шт: ${mvar2[3]}</strong> <br>
        от ${mvar2[4]}шт: ${mvar2[5]} <br>
        от ${mvar2[6]}шт: ${mvar2[7]} <br>
        от ${mvar2[8]}шт: ${mvar2[9]} <br>
        от ${mvar2[10]}шт: ${mvar2[11]}</div>`;
    } else if(mvar2[4] <= inputValue2 && inputValue2 < mvar2[6]) {
        result2.innerText = (inputValue2 * mvar2[5]).toFixed(2);
        blockPrice2.innerHTML = `<div>от ${mvar2[0]}шт: ${mvar2[1]} <br>
        от ${mvar2[2]}шт: ${mvar2[3]} <br>
        <strong>от ${mvar2[4]}шт: ${mvar2[5]}</strong> <br>
        от ${mvar2[6]}шт: ${mvar2[7]} <br>
        от ${mvar2[8]}шт: ${mvar2[9]} <br>
        от ${mvar2[10]}шт: ${mvar2[11]}</div>`;
    } else if(mvar2[6] <= inputValue2 && inputValue2 < mvar2[8]) {
        result2.innerText = (inputValue2 * mvar2[7]).toFixed(2);
        blockPrice2.innerHTML = `<div>от ${mvar2[0]}шт: ${mvar2[1]} <br>
        от ${mvar2[2]}шт: ${mvar2[3]} <br>
        от ${mvar2[4]}шт: ${mvar2[5]} <br>
        <strong>от ${mvar2[6]}шт: ${mvar2[7]}</strong> <br>
        от ${mvar2[8]}шт: ${mvar2[9]} <br>
        от ${mvar2[10]}шт: ${mvar2[11]}</div>`;
    } else if(mvar2[8] <= inputValue2 && inputValue2 < mvar2[10]) {
        result2.innerText = (inputValue2 * mvar2[9]).toFixed(2);
        blockPrice2.innerHTML = `<div>от ${mvar2[0]}шт: ${mvar2[1]} <br>
        от ${mvar2[2]}шт: ${mvar2[3]} <br>
        от ${mvar2[4]}шт: ${mvar2[5]} <br>
        от ${mvar2[6]}шт: ${mvar2[7]} <br>
        <strong>от ${mvar2[8]}шт: ${mvar2[9]}</strong> <br>
        от ${mvar2[10]}шт: ${mvar2[11]}</div>`;
    } else {
        result2.innerText = (inputValue2 * mvar2[11]).toFixed(2);
        blockPrice2.innerHTML = `<div>от ${mvar2[0]}шт: ${mvar2[1]} <br>
        от ${mvar2[2]}шт: ${mvar2[3]} <br>
        от ${mvar2[4]}шт: ${mvar2[5]} <br>
        от ${mvar2[6]}шт: ${mvar2[7]} <br>
        от ${mvar2[8]}шт: ${mvar2[9]} <br>
        <strong>от ${mvar2[10]}шт: ${mvar2[11]}</strong></div>`;
    }
});

var inputElement3 = document.querySelector('.table__block-quallity--po3 input');
var result3 = document.querySelector('.table__block--po3 span');
var blockPrice3 = document.querySelector('.table__block-price--po3');

// Добавляем обработчик события 'input' на элемент input
inputElement3.addEventListener('input', function() {
    // Получаем текущее значение из элемента input
    var inputValue3 = inputElement3.value;

    if( inputValue3 < mvar3[0] ){
        result3.innerText = (inputValue3 * 0).toFixed(2);
        blockPrice3.innerHTML = `<div>от ${mvar3[0]}шт: ${mvar3[1]}<br>
        от ${mvar3[2]}шт: ${mvar3[3]} <br>
        от ${mvar3[4]}шт: ${mvar3[5]} <br>
        от ${mvar3[6]}шт: ${mvar3[7]} <br>
        от ${mvar3[8]}шт: ${mvar3[9]} <br>
        от ${mvar3[10]}шт: ${mvar3[11]}</div>`; 
    } else if(mvar3[0] <= inputValue3 && inputValue3 < mvar3[2]) {
        result3.innerText = (inputValue3 * mvar3[1]).toFixed(2);
        blockPrice3.innerHTML = `<div><strong>от ${mvar3[0]}шт: ${mvar3[1]}</strong> <br>
        от ${mvar3[2]}шт: ${mvar3[3]} <br>
        от ${mvar3[4]}шт: ${mvar3[5]} <br>
        от ${mvar3[6]}шт: ${mvar3[7]} <br>
        от ${mvar3[8]}шт: ${mvar3[9]} <br>
        от ${mvar3[10]}шт: ${mvar3[11]}</div>`;
    } else if(mvar3[2] <= inputValue3 && inputValue3 < mvar3[4]) {
        result3.innerText = (inputValue3 * mvar3[3]).toFixed(2);
        blockPrice3.innerHTML = `<div>от ${mvar3[0]}шт: ${mvar3[1]} <br>
        <strong>от ${mvar3[2]}шт: ${mvar3[3]}</strong> <br>
        от ${mvar3[4]}шт: ${mvar3[5]} <br>
        от ${mvar3[6]}шт: ${mvar3[7]} <br>
        от ${mvar3[8]}шт: ${mvar3[9]} <br>
        от ${mvar3[10]}шт: ${mvar3[11]}</div>`;
    } else if(mvar3[4] <= inputValue3 && inputValue3 < mvar3[6]) {
        result3.innerText = (inputValue3 * mvar3[5]).toFixed(2);
        blockPrice3.innerHTML = `<div>от ${mvar3[0]}шт: ${mvar3[1]} <br>
        от ${mvar3[2]}шт: ${mvar3[3]} <br>
        <strong>от ${mvar3[4]}шт: ${mvar3[5]}</strong> <br>
        от ${mvar3[6]}шт: ${mvar3[7]} <br>
        от ${mvar3[8]}шт: ${mvar3[9]} <br>
        от ${mvar3[10]}шт: ${mvar3[11]}</div>`;
    } else if(mvar3[6] <= inputValue3 && inputValue3 < mvar3[8]) {
        result3.innerText = (inputValue3 * mvar3[7]).toFixed(2);
        blockPrice3.innerHTML = `<div>от ${mvar3[0]}шт: ${mvar3[1]} <br>
        от ${mvar3[2]}шт: ${mvar3[3]} <br>
        от ${mvar3[4]}шт: ${mvar3[5]} <br>
        <strong>от ${mvar3[6]}шт: ${mvar3[7]}</strong> <br>
        от ${mvar3[8]}шт: ${mvar3[9]} <br>
        от ${mvar3[10]}шт: ${mvar3[11]}</div>`;
    } else if(mvar3[8] <= inputValue3 && inputValue3 < mvar3[10]) {
        result3.innerText = (inputValue3 * mvar3[9]).toFixed(2);
        blockPrice3.innerHTML = `<div>от ${mvar3[0]}шт: ${mvar3[1]} <br>
        от ${mvar3[2]}шт: ${mvar3[3]} <br>
        от ${mvar3[4]}шт: ${mvar3[5]} <br>
        от ${mvar3[6]}шт: ${mvar3[7]} <br>
        <strong>от ${mvar3[8]}шт: ${mvar3[9]}</strong> <br>
        от ${mvar3[10]}шт: ${mvar3[11]}</div>`;
    } else {
        result3.innerText = (inputValue3 * mvar3[11]).toFixed(2);
        blockPrice3.innerHTML = `<div>от ${mvar3[0]}шт: ${mvar3[1]} <br>
        от ${mvar3[2]}шт: ${mvar3[3]} <br>
        от ${mvar3[4]}шт: ${mvar3[5]} <br>
        от ${mvar3[6]}шт: ${mvar3[7]} <br>
        от ${mvar3[8]}шт: ${mvar3[9]} <br>
        <strong>от ${mvar3[10]}шт: ${mvar3[11]}</strong></div>`;
    }
});

// Получаем доступ к элементу input
var inputElement1 = document.querySelector('.table__block-quallity--po1 input');

// Устанавливаем значение placeholder
inputElement1.placeholder = "кол-во " + mvar1[0];

// Получаем доступ к элементу input
var inputElement2 = document.querySelector('.table__block-quallity--po2 input');

// Устанавливаем значение placeholder
inputElement2.placeholder = "кол-во " + mvar2[0];

// Получаем доступ к элементу input
var inputElement3 = document.querySelector('.table__block-quallity--po3 input');

// Устанавливаем значение placeholder
inputElement3.placeholder = "кол-во " + mvar3[0];



// Sort table price

var sortOrder = 1; // 1 - по возрастанию, -1 - по убыванию

function sortTable() {
    var table, rows, switching, i, x, y, shouldSwitch;
    table = document.getElementById("table");
    switching = true;
    
    // Инвертируем sortOrder для изменения направления сортировки
    sortOrder *= -1;

    while (switching) {
        switching = false;
        rows = table.getElementsByClassName("table__row");
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByClassName("table__block-price")[0];
            y = rows[i + 1].getElementsByClassName("table__block-price")[0];

            // Получаем цены, учитывая класс table__block-price--multi
            var xPrice, yPrice;
            if (x.classList.contains("table__block-price--po")) {
                xPrice = parseFloat(x.dataset.meta);
            } else if (x.classList.contains("table__block-price--multi")) {
                xPrice = parseFloat(x.dataset.price1);
            } else {
                xPrice = parseFloat(x.textContent.trim());
            }
            if (y.classList.contains("table__block-price--multi")) {
                yPrice = parseFloat(y.dataset.price1);
            } else if (y.classList.contains("table__block-price--po")) {
                yPrice = parseFloat(y.dataset.meta);
            } else {
                yPrice = parseFloat(y.textContent.trim());
            }
            
            if (sortOrder === 1) {
                // console.log("xPrice:", xPrice, "yPrice:", yPrice); // Добавляем вывод в консоль
                if (xPrice > yPrice) {
                    shouldSwitch = true;
                    break;
                }
            } else {
                // console.log("xPrice:", xPrice, "yPrice:", yPrice); // Добавляем вывод в консоль
                if (xPrice < yPrice) {
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
        }
    }
}












