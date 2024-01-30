# Инструкция по развёртывания проекта
1. Загрузить файлы на хост
2. Создать вручную пустую БД
3. В файле connection.php указать параметры для подключения к БД (хост, логин, пароль, имя БД)
4. Запустить скрипт import.php (скрипт создаст в БД таблицу и запишет в неё данные из categories.json)
   или можно запустить JS функцию upload_data() из консоли, которая сделает ajax-запрос в файл import.php и выполнит те же действия
6. Запустить index.php

# Описание файлов
1. connection.php - параметры подключения к БД
2. import.php - скрипт для импорта данных из файла categories.json
3. categories.json - файл с json-данными
4. index.php - основной скрипт запуска сайта
5. list_menu.php - скрипт, выводящий на страницу список меню
6. head.php - шаблон "шапки" сайта
7. ajax.php - содержит в себе скрипты для обработки ajax-запросов
8. functions.js - функции для JS
