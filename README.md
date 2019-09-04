# admin-gb

### Информация

Модуль Гостевой книги заточенный для CMS IRsite.

### Установка

```
$ composer require avl/admin-gb
```
Или в секцию **require** добавить строчку **"avl/admin-gb": "^1.0"**

```json
{
    "require": {
        "avl/admin-gb": "^1.0"
    }
}
```
### Настройка

Для публикации файла настроек необходимо выполнить команду:

```
$ php artisan vendor:publish --provider="Avl\AdminGb\AdminGbServiceProvider" --force
```
