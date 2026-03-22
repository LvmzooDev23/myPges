# Plateforme de gestion de stages (StageHub)

Stack : **Laravel 10** (API REST + JWT), **Vue 3** (SPA), **MySQL**, **TailwindCSS**.

## Arborescence

```
myPges/
├── backend/                 # API Laravel
│   ├── app/
│   │   ├── Enums/
│   │   ├── Http/
│   │   │   ├── Controllers/Api/
│   │   │   ├── Middleware/
│   │   │   ├── Requests/
│   │   │   └── Resources/
│   │   ├── Models/
│   │   ├── Policies/
│   │   ├── Repositories/
│   │   └── Services/
│   ├── config/
│   ├── database/migrations/
│   ├── routes/api.php
│   └── storage/app/private/ # CV & rapports (disque private)
├── frontend/                # SPA Vue 3 + Vite
│   ├── src/
│   │   ├── api/
│   │   ├── components/
│   │   ├── layouts/
│   │   ├── router/
│   │   ├── stores/
│   │   └── views/
│   └── vite.config.js       # proxy /api → Laravel
├── API_DOCUMENTATION.md
└── INSTALL.md
```

## Documentation

- [INSTALL.md](INSTALL.md) — installation et configuration.
- [API_DOCUMENTATION.md](API_DOCUMENTATION.md) — endpoints REST.

## Comptes de démonstration (après `php artisan db:seed`)

| Rôle        | Email               | Mot de passe |
|------------|---------------------|--------------|
| Admin      | admin@example.com   | password     |
| Étudiant   | student@example.com | password   |
| Entreprise | company@example.com | password   |
| Superviseur| supervisor@example.com | password |

L’entreprise démo est **déjà approuvée** ; une offre de stage publiée est créée.

## Schéma base de données

Tables principales : `users` (rôle), `students`, `companies`, `supervisors`, `internships`, `applications`, `internship_reports`, `evaluations`, `platform_notifications` (notifications métier ; nom choisi pour éviter le conflit avec le canal « database » de Laravel).

## Licence

Projet généré à des fins de démonstration / formation.
