# API d'Estudiants — Prova tècnica iLERNA

Aquest és un projecte senzill d'API REST feta amb Laravel per gestionar un CRUD d'estudiants. L'objectiu de la prova era crear els endpoints bàsics d'un model Estudiant amb els camps obligatoris (nom, email, telèfon) i opcionals (adreça, número de document d'identitat), tot aplicant validacions i una estructura neta.

Crec que s'han demostrat els punts clau: CRUD complet, validacions per FormRequest, tests d'integració, Docker per aixecar-ho tot de forma automàtica i una resposta JSON uniforme.

## On està cada cosa

- `laravel/app/Http/Controllers/EstudiantController.php` — Logica dels endpoints.
- `laravel/app/Http/Requests/` — Validacions per crear i actualitzar.
- `laravel/app/Models/Estudiant.php` — Model i camps omplibles.
- `laravel/database/migrations/` — Esquema de la base de dades.
- `laravel/database/factories/EstudiantFactory.php` — Dades de prova per tests i seeders.
- `laravel/database/seeders/DatabaseSeeder.php` — Crea 10 estudiants de prova.
- `laravel/tests/Feature/EstudiantApiTest.php` — Tests del CRUD i validacions.
- `laravel/docker-compose.yml` — Docker amb PHP, nginx, MySQL i phpMyAdmin.
- `Makefile` — Comandos per instal·lar i testejar automàticament.

## Requisits

Només cal tenir instal·lat:
- Docker
- Docker Compose
- Make (opcional, però recomanat)

## Instal·lació automàtica

Des de l'arrel del repositori:

```bash
make install
```

Això aixeca els contenidors, instal·la dependencies, genera la clau de l'aplicació i executa migracions amb dades de prova.

Un cop acabi:
- API: http://localhost:8083
- phpMyAdmin: http://localhost:8082

## Tests

```bash
make test
```

## Endpoints principals

| Mètode | URL | Descripció |
|--------|-----|------------|
| GET | `/api/estudiants` | Llistar estudiants (paginat) |
| POST | `/api/estudiants` | Crear un estudiant |
| GET | `/api/estudiants/{id}` | Veure un estudiant |
| PUT | `/api/estudiants/{id}` | Actualitzar un estudiant |
| DELETE | `/api/estudiants/{id}` | Eliminar un estudiant (soft delete) |
| GET | `/api/health` | Health check |

## Proves ràpides amb curl

Després de `make install`, pots provar l'API directament copiant i pegant aquests comandos:

### Llistar tots els estudiants
```bash
curl -X GET http://localhost:8083/api/estudiants
```

### Crear un estudiant
```bash
curl -X POST http://localhost:8083/api/estudiants \
  -H "Content-Type: application/json" \
  -d '{"nom":"Gerard Revert","email":"gerard@ilerna.com","telefon":"690203376","adreca":"Carrer Major 123","numero_document_identitat":"12345678A"}'
```

### Veure un estudiant (canvia el 1 per l'id que et retorni)
```bash
curl -X GET http://localhost:8083/api/estudiants/1
```

### Actualitzar un estudiant
```bash
curl -X PUT http://localhost:8083/api/estudiants/1 \
  -H "Content-Type: application/json" \
  -d '{"nom":"Gerard Actualitzat","email":"gerard@ilerna.com","telefon":"690203376"}'
```

### Eliminar un estudiant
```bash
curl -X DELETE http://localhost:8083/api/estudiants/1
```

### Endpoint de salut
```bash
curl -X GET http://localhost:8083/api/health
```

## Aturar els contenidors

```bash
make down
```


## Estructura del projecte

- `app/Http/Controllers/EstudiantController.php` — Endpoints del CRUD.
- `app/Http/Requests/` — Validacions per crear i actualitzar.
- `app/Http/Resources/EstudiantResource.php` — Format de resposta JSON.
- `app/Rules/DniNie.php` — Regla personalitzada per validar DNI/NIE.
- `app/Traits/ApiResponse.php` — Helper per respostes JSON uniformes.
- `app/Models/Estudiant.php` — Model amb soft deletes.
- `database/migrations/` — Esquema de la base de dades.
- `database/factories/EstudiantFactory.php` — Dades de prova.
- `database/seeders/DatabaseSeeder.php` — Seeder inicial.
- `tests/Feature/EstudiantApiTest.php` — Tests d'integració.
- `docker-compose.yml` — Docker amb PHP, Nginx, MySQL i phpMyAdmin.

---

# CHANGELOG
### Es poden revisar tambe els comits a git.

---

Tests integrats amb CI amb github action per  automatitzacions de proves i control de calitat.

---

Fer servir variables d'entorn a docker-compose per evitar injectar credencials.

---

Filtre de api trait per unificat totes les respostes en casos de exit i error sempre dona resposta estructurada

---

Filtre i generador de DNI , tambe llimpiesa de codi als requests i implementacio del generador dni i filtre telefon

---

Millora de codi, he creat el resource de estudiant, aixi  nomes retorna la estructura de json valida actuant com a primer filtre.


---

Canviar les migracions per tenir limits al telefon, al dni, i permetre softdeletes seguin amb la millora de codi dels testos

---

Afegir validacios del DNI i telefon als testos per incloure un regex de format

---

Millores al model per created at i controlador reutilitzan resources en comptes de resposta hardcodejada

---

Neteja general de codi (eliminar codi redundant com try catch i pasar-ho al app) i implementacio de instalacio automatica amb makefile i documentacio.

---

Els testos de validacio de dades ja son tots correctes ara nomes queden els testos de estudiants especifics

---

llistar un estudiant especific, Actualitzar i borra funciona, els testos nous de actualitzar i borrar tambe pasen 


api/estudiants/1
{
  "success": true,
  "data": {
    "id": 1,
    "nom": "gerard",
    "email": "gerard@ilerna.com",
    "telefon": "690203376",
    "adreca": "Carrer Major",
    "numero_document_identitat": "12345678A",
    "created_at": "2026-06-21T23:36:39.000000Z",
    "updated_at": "2026-06-21T23:44:39.000000Z"
  }
}

Estudiant actualitzat
{
  "success": true,
  "message": "Estudiant actualitzat correctament",
  "data": {
    "id": 1,
    "nom": "gerard revert",
    "email": "gerard@ilerna.com",
    "telefon": "690203376",
    "adreca": "Carrer Major",
    "numero_document_identitat": "12345678A",
    "created_at": "2026-06-21T23:36:39.000000Z",
    "updated_at": "2026-06-21T23:43:55.000000Z"
  }
}


--- 

Controladors acabats

---

Els primers testos de la api ja funcionen

docker-compose exec app php artisan test

   PASS  Tests\Unit\ExampleTest
  ✓ that true is true

   PASS  Tests\Feature\EstudiantApiTest
  ✓ llistar tots els estudiants                                                                                    0.24s  
  ✓ llistar usuaris quan esta vuit                                                                                 0.01s  
  ✓ crear estudiant amb dades valides                                                                              0.02s  

   PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response                                                                  0.02s  

  Tests:    5 passed (48 assertions)
  Duration: 0.46s

---

He tingut un petit inconvenient amb la cache de OPcache amb docker i he reiniciat el contenidor ja torna a funcionar i la api ara dona resposta correcta al crear un estudiant ara ja puc continuar amb les altres funcions del controlador

{
  "success": true,
  "message": "Estudiant creat correctament",
  "data": {
    "nom": "gerard",
    "email": "gerard@ilerna.coim",
    "telefon": "690203376",
    "updated_at": "2026-06-21T22:57:59.000000Z",
    "created_at": "2026-06-21T22:57:59.000000Z",
    "id": 2
  }
}

---

He fet les primeres funcions del controlador i tambe el primer endpoint de prova health a /api/health
tambe he fet el migrate per continuar amb les proves de crear estudiants 

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