Тестовое задание - каталог книг

![Превью](/test.png)

Реализованы: каталог с пагинацией, 
топ-10 авторов по выбранному году, форма для добавления\редактирования книги,
механизм подписки на новые книги автора с SMS уведомлением. 

Авторизированный пользователь может добавлять, редактировать и удалять книги.
Неавторизированный пользователь может только просматривать книги и подписаться
на уведомления о новых книгах выбранных авторов.



Тестовая версия доступна здесь
http://89.191.225.149:3000/yii-books/test/
# Stack
Yii2, MySQL 8, Redis (queue)

# Instructions

Composer

```
composer install
```

Migrate

```
./yii migrate
```

# Данные для входа

```
email: test@test.test
password: test
```

# Затраченное время
~ 10 часов