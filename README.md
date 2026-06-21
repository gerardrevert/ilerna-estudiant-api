



/-/-/-/-/-/CHANGELOG/-/-/-/-/-/


---

He implementat el esquema de la taula estudiants a les migracions i he configurat el model estudiant, tambe el factory per estudiants de prova i les seves validacions 

---

He creat el Model i migracions de estudiant php artisan 

docker-compose exec app php artisan make:model Estudiant -mf

El controlador de estudiant

docker-compose exec app php artisan make:controller EstudiantController --api

Els request de estudiant 

docker-compose exec app php artisan make:request CrearEstudiant
docker-compose exec app php artisan make:request ActualitzarEstudiant

---

He tingut que actualitzar la versio de php a la 8.4 per que no em funcionava la instalacio del docker.

Un cop funciona el docker-compose build, ja es pot accedir per nginx a localhost:8083

---

He ficat un docker que tenia fet per poder fer funcionar la bbdd en local i ho he integrat al projecte sota la carpeta ./docker

Tambe he ficat el docker-compose fora per poder configurar ports dels contenidors.

---

He creat el directori amb un laravel nou fen servir la comanda per terminal

docker run --rm \
    -v "$(pwd):/app:Z" \
    -w /app \
    composer:latest create-project laravel/laravel .