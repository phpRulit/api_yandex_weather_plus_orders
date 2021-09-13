[Техническое задание.odt](https://github.com/phpRulit/api_yandex_weather_plus_orders/files/7137043/default.odt)

# tz_api_yandex_weather_plus_orders
Выводим погоду на сайте + обработка поступивших заказов

SPA (Laravel + Vue JS + Vuex + Docker)

Миграции заданы изначально...

Можно было использовать REST API, сильно к этому не придерайтесь, с resource так же умею работать...

Очереди поставлены только на отправку писем...

Установить docker, docker-compose.

1. Развернуть приложение - make init;
2. В другой вкладке применить миграции - make api-migration; Заполнить тестовыми данными таблицы БД - make api-seed.

# В api/.env добавить:
- свой api ключ разработчика Yandex, для вывода погоды на сайте (тестовый режим, читать документацию...);
- свои настройки для отправки писем.

front: http://localhost:8093 back: http://localhost:8094
