# Diari de desenvolupament — Prova tècnica iLERNA

Aquest fitxer recull el procés real de desenvolupament de la API. L'he mantingut perquè quedi clar que el codi s'ha anat construint pas a pas, no és un copiar-enganxar ni una generació automàtica.

---

## Dia 1 — Configuració inicial

He creat el projecte Laravel des de zero dins del directori `laravel`:

```bash
docker run --rm \
    -v "$(pwd):/app:Z" \
    -w /app \
    composer:latest create-project laravel/laravel .
```

He tingut un petit inconvenient amb la versió de PHP del Docker i l'he actualitzat a la 8.4 perquè la instal·lació funcionés correctament.

He integrat un docker-compose que ja tenia fet, amb PHP, Nginx, MySQL i phpMyAdmin, sota la carpeta `./docker`. He deixat el `docker-compose.yml` a l'arrel de `laravel` per poder configurar els ports dels contenidors.

Un cop funciona el `docker-compose build`, ja es pot accedir per Nginx a `localhost:8083`.

---

## Dia 1 — Model i migracions

He creat el model `Estudiant` amb la seva factory i migració:

```bash
docker-compose exec app php artisan make:model Estudiant -mf
```

He implementat l'esquema de la taula `estudiants` amb els camps obligatoris (nom, email, telèfon) i opcionals (adreça, número de document d'identitat). També he configurat el factory per generar dades de prova.

---

## Dia 1 — Controlador i validacions

He creat el controlador de l'API:

```bash
docker-compose exec app php artisan make:controller EstudiantController --api
```

I els FormRequests per separar la lògica de validació:

```bash
docker-compose exec app php artisan make:request CrearEstudiant
docker-compose exec app php artisan make:request ActualitzarEstudiant
```

Al principi vaig tenir un problema amb la cache d'OPcache dins del contenidor Docker, però després de reiniciar-lo vaig començar a rebre respostes correctes al crear un estudiant:

```json
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
```

Els primers tests ja funcionaven:

```bash
docker-compose exec app php artisan test

   PASS  Tests\Unit\ExampleTest
  ✓ that true is true

   PASS  Tests\Feature\EstudiantApiTest
  ✓ llistar tots els estudiants
  ✓ llistar usuaris quan esta vuit
  ✓ crear estudiant amb dades valides

   PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response

  Tests:    5 passed (48 assertions)
```

---

## Dia 1 — CRUD complet

He acabat les funcions del controlador: llistar, veure, actualitzar i eliminar. He comprovat que funcionaven amb curls i he anat afegint tests per cada operació.

Exemple de resposta al consultar un estudiant:

```bash
GET /api/estudiants/1
```

```json
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
```

Exemple d'actualització:

```json
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
```

Els tests d'actualitzar i borrar també passaven.

---

## Dia 2 — Refactorització i millores

Després de revisar el codi amb criteri i he aplicat les següents millores:

1. **Route model binding**: he eliminat els `try/catch` repetits i els `findOrFail` innecessaris del controlador. Laravel ja resol el model automàticament.
2. **Maneig centralitzat d'excepcions**: he mogut la gestió dels 404 i 422 a `bootstrap/app.php`.
3. **Trait `ApiResponse`**: he creat un helper per unificar les respostes JSON.
4. **Paginació**: he canviat `Estudiant::all()` per `paginate(20)`.
5. **`EstudiantResource`**: he creat un resource per controlar quins camps s'exposen.
6. **Validacions de negoci**: he afegit regex per al telèfon espanyol i una regla personalitzada `DniNie` que valida format i lletra del DNI/NIE.
7. **Soft deletes**: he afegit `SoftDeletes` al model i a la migració perquè en un CRM no s'han d'esborrar registres permanentment.
8. **GitHub Actions**: he configurat un pipeline que executa tests i Pint en cada push i pull request.
9. **OpenAPI / Swagger**: he instal·lat `l5-swagger` i documentat els endpoints.
10. **README net**: he separat aquest diari de desenvolupament en un fitxer apart (`DEVLOG.md`) perquè el `README.md` quedi professional i directe.

---

## Estat actual

- **Tests**: 26 passed (103 assertions).
- **Pint**: sense errors d'estil.
- **CI/CD**: GitHub Actions executa tests i Pint automàticament.
- **Documentació**: disponible a `/api/documentation` gràcies a Swagger.
