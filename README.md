# API d'Estudiants — Prova tècnica iLERNA

[![Tests](https://github.com/gerardrevert/ilerna-estudiant-api/actions/workflows/tests.yml/badge.svg)](https://github.com/gerardrevert/ilerna-estudiant-api/actions/workflows/tests.yml)

API REST desenvolupada amb Laravel per gestionar un CRUD d'estudiants. Inclou validacions, paginació, soft deletes, tests d'integració, documentació OpenAPI/Swagger i un pipeline de CI/CD amb GitHub Actions.

El que he prioritzat:
**Codi net i mantenible:** he separat validacions en FormRequests, he centralitzat els 404/422 a bootstrap/app.php i he usat route model binding per no repetir findOrFail per tot arreu.
**Validacions de negoci:** a més de les bàsiques, he afegit validació de telèfon espanyol i una regla pròpia per al DNI/NIE que comprova fins i tot la lletra.
**Paginació i soft deletes:** el listat està paginat i els eliminats són soft deletes, que per un CRM em sembla imprescindible.
**Documentació:** he integrat Swagger amb OpenAPI, accessible a /api/documentation, així qualsevol equip pot veure els endpoints sense haver de mirar codi.
**CI/CD:** he muntat un workflow de GitHub Actions que executa tests automàticament, genera la documentació i passa Laravel Pint en cada push i pull request.
**Procés transparent:** he deixat un DEVLOG.md amb el procés real de desenvolupament, perquè quedi clar que s'ha anat construint pas a pas.

## Requisits

- Docker
- Docker Compose
- Make (opcional)

## Instal·lació

Des de l'arrel del repositori:

```bash
make install
```

Això aixecarà els contenidors, instal·larà dependències, generarà la clau de l'aplicació i executarà migracions amb dades de prova.

Un cop acabat:

- API: http://localhost:8083
- Documentació Swagger: http://localhost:8083/api/documentation
- phpMyAdmin: http://localhost:8082

## Tests i qualitat de codi

```bash
make test
```

Per comprovar l'estil de codi:

```bash
cd laravel && vendor/bin/pint --test
```

Per corregir l'estil automàticament:

```bash
cd laravel && vendor/bin/pint
```

## Documentació de l'API

La documentació completa amb tots els endpoints, paràmetres i exemples de resposta està disponible a:

```
/api/documentation
```

També es pot regenerar amb:

```bash
cd laravel && php artisan l5-swagger:generate
```

## Endpoints

| Mètode | URL | Descripció |
|--------|-----|------------|
| GET | `/api/estudiants` | Llistar estudiants (paginat) |
| POST | `/api/estudiants` | Crear un estudiant |
| GET | `/api/estudiants/{id}` | Veure un estudiant |
| PUT | `/api/estudiants/{id}` | Actualitzar un estudiant |
| DELETE | `/api/estudiants/{id}` | Eliminar un estudiant (soft delete) |
| GET | `/api/health` | Health check |

## Exemples d'ús amb curl

### Llistar estudiants

```bash
curl -X GET http://localhost:8083/api/estudiants
```

### Crear un estudiant

```bash
curl -X POST http://localhost:8083/api/estudiants \
  -H "Content-Type: application/json" \
  -d '{
    "nom": "Nom Exemple",
    "email": "exemple@ilerna.com",
    "telefon": "690203376",
    "adreca": "Carrer Major 123",
    "numero_document_identitat": "12345678Z"
  }'
```

### Veure un estudiant

```bash
curl -X GET http://localhost:8083/api/estudiants/1
```

### Actualitzar un estudiant

```bash
curl -X PUT http://localhost:8083/api/estudiants/1 \
  -H "Content-Type: application/json" \
  -d '{
    "nom": "Nom Actualitzat",
    "email": "exemple@ilerna.com",
    "telefon": "690203376"
  }'
```

### Eliminar un estudiant

```bash
curl -X DELETE http://localhost:8083/api/estudiants/1
```

### Health check

```bash
curl -X GET http://localhost:8083/api/health
```

## Aturar els contenidors

```bash
make down
```

## Estructura del projecte

- `app/Http/Controllers/EstudiantController.php` — Endpoints del CRUD amb anotacions OpenAPI.
- `app/Http/Requests/` — Validacions per crear i actualitzar.
- `app/Http/Resources/EstudiantResource.php` — Format de resposta JSON.
- `app/OpenApi/Schemas/EstudiantSchema.php` — Esquema de dades per Swagger.
- `app/Rules/DniNie.php` — Regla personalitzada per validar DNI/NIE.
- `app/Traits/ApiResponse.php` — Helper per respostes JSON uniformes.
- `app/Models/Estudiant.php` — Model amb soft deletes.
- `database/migrations/` — Esquema de la base de dades.
- `database/factories/EstudiantFactory.php` — Dades de prova vàlides.
- `database/seeders/DatabaseSeeder.php` — Seeder inicial.
- `tests/Feature/EstudiantApiTest.php` — Tests d'integració.
- `.github/workflows/tests.yml` — Pipeline de CI/CD.
- `docker-compose.yml` — Docker amb PHP, Nginx, MySQL i phpMyAdmin.

## Procés de desenvolupament

Si vols veure com s'ha construït el projecte pas a pas, consulta el fitxer [`DEVLOG.md`](DEVLOG.md).
