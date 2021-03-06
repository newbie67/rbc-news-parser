# Тестовое задание 

Исполнитель: Алексей Зорин

1. Описание задания
2. Комментарий
3. Установка проекта

### 1. Описание задания
Спарсить (программно) первые 15 новостей с rbk.ru (блок, откуда брать новости показан на скриншоте) и вставить в базу данных (составить структуру самому) или в файл. Вывести все новости, сократив текст до 200 символов в качестве описания, со ссылкой на полную новость с кнопкой подробнее. На полной новости выводить картинку если есть в новости.

### 2. Комментарий
В указанном блоке отображается 14 новостей, остальные подтягиваются ajax-запросом, 
причём часть из этих новостей - не новости,
а разные тесты\лендинги вроде вот таких: 
 - http://cloudmts.rbc.ru/test
 - https://lockdown.rbc.ru/
 - Или же ссылки на курсы валют.

Это специальные страницы, по большей части без какой-то одинаковой структуры, поэтому написать уникальный реально работающий парсер под них невозможно.

Новостями считаются записи, ведущие на сайт rbc или его поддомены, в том числе и похожие домены (sportrbc.ru). Они должны попадать под шаблон "*rbc.ru" и иметь более-менее похожую на новость структуру.

Зато в ТЗ точно сказано, что парсить нужно 15 новостей. Поэтому до 14 новостей тянутся из ленты, а оставшееся необходимое число новостей тянется из ajax-экшена.

**Конечно же в реальности я бы парсил их RSS ленту/тянул данные из API и там не было бы всех этих проблем.
Но я думаю задание специально описано плохо, мол крутись как хочешь.**

### 3. Установка

Скопируйте docker-compose конфиг и поправьте если нужно:
```bash
copy docker-compose.yml.dist docker-compose.yml
```

Запустите контейнеры и подтяните зависимости:
```bash
docker-compose up -d
docker-compose exec php composer install
```

Выполните миграции:
```bash
docker-compose exec php php bin/console doctrine:migrations:migrate
```

Подтяните посты:
```bash
docker-compose exec php php bin/console app:loadRbcPosts
```

