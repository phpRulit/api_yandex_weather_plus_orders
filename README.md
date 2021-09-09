[Техническое задание.odt](https://github.com/phpRulit/api_yandex_weather_plus_orders/files/7137043/default.odt)

SPA (Laravel + Vue JS + Vuex + Docker)

!!! ПОКА В РАЗРАБОТКЕ (размещу полностью ориентировочно 10.09.2021)


Установить docker, docker-compose.

1. Развернуть приложение - make init;
2. В другой вкладке применить миграции - make api-migration; Заполнить тестовыми данными таблицы БД - make api-seed.

В api/.env добавить свой api ключ разработчика Yandex, для вывода погоды на сайте (тестовый режим, читать документацию...).

front: http://localhost:8093 back: http://localhost:8094


# tz_api_yandex_weather_plus_orders
Выводим погоду на сайте + обработка поступивших заказов
