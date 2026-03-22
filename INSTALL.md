# Installation

## Prérequis

- PHP 8.1+ avec extensions `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`
- Composer 2
- Node.js 18+ et npm
- MySQL 8 (ou MariaDB 10.3+), ou SQLite pour un test local rapide

## Backend (Laravel)

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

### Base de données MySQL

Créez une base vide, puis dans `.env` :

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stagehub
DB_USERNAME=votre_user
DB_PASSWORD=votre_mot_de_passe
```

### Base SQLite (développement)

```
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Créez le fichier vide : `touch database/database.sqlite` (Linux/macOS) ou équivalent sous Windows.

### Migrations et données de démo

```bash
php artisan migrate --seed
```

Les fichiers sensibles (CV, rapports) sont stockés sur le disque `private` (`storage/app/private`). Créez le dossier si besoin :

```bash
mkdir -p storage/app/private
```

### Lancer l’API

```bash
php artisan serve
```

L’API est disponible sur `http://127.0.0.1:8000/api`.

### CORS

En production, définissez `FRONTEND_URL` dans `.env` et restreignez `config/cors.php` (`allowed_origins`) à l’URL du front.

## Frontend (Vue 3)

```bash
cd frontend
npm install
cp .env.example .env
npm run dev
```

Le fichier `vite.config.js` proxifie `/api` vers `http://127.0.0.1:8000` : le front peut appeler l’API en relatif (`VITE_API_URL=/api`).

### Build de production

```bash
npm run build
```

Servez le dossier `frontend/dist` avec Nginx / Apache / S3+CloudFront, et configurez le proxy inverse vers Laravel pour les routes `/api`.

## Tests rapides

1. Démarrez Laravel (`php artisan serve`) et le front (`npm run dev`).
2. Ouvrez `http://localhost:5173`, consultez les offres, connectez-vous avec un compte de démo (voir [README.md](README.md)).

## Dépannage

- **401 sur l’API** : en-tête `Authorization: Bearer <token>` manquant ou JWT expiré (`POST /api/auth/refresh`).
- **JWT** : vérifiez `JWT_SECRET` dans `.env` (régénérez avec `php artisan jwt:secret` si nécessaire).
- **Upload** : taille max PHP (`upload_max_filesize`, `post_max_size`) pour les CV et rapports.
