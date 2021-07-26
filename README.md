Приложение работает в докер контейнере. Доступно по ссылке http://localhost:8080/


## Установка
```make setup```

## Запуск
```make up```

## Остановка
```make down```

## Запустить тесты
```make test```

## Bash в контейнер php-fpm
```make bash```

## API

POST http://localhost:8080/division

Content-Type: application/json

```{"dividend": 1.5, "divider": 3 }```

response
```{"result": 0.5}```