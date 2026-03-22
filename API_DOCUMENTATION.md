# Documentation API REST

Base URL : `/api` (ex. `http://127.0.0.1:8000/api`).

Authentification JWT : en-tête `Authorization: Bearer <access_token>` (sauf routes publiques).

Réponses JSON : ressources Laravel au format `{ "data": ... }` (collections paginées incluent `links` et `meta`).

---

## Authentification

| Méthode | Endpoint | Description |
|--------|----------|-------------|
| POST | `/auth/register` | Inscription (`name`, `email`, `password`, `password_confirmation`, `role`: student \| company \| supervisor, `company_name` si company) |
| POST | `/auth/login` | Connexion (`email`, `password`) → `access_token` |
| POST | `/auth/logout` | Déconnexion (auth) |
| POST | `/auth/refresh` | Rafraîchir le token (auth) |
| GET | `/auth/me` | Utilisateur courant (auth) |
| POST | `/auth/password/forgot` | Demande de reset (`email`) |
| POST | `/auth/password/reset` | Reset (`email`, `token`, `password`, `password_confirmation`) |

---

## Stages (public)

| Méthode | Endpoint | Description |
|--------|----------|-------------|
| GET | `/internships` | Liste paginée (`q`, `location`, `type`, `from`, `to`, `page`) |
| GET | `/internships/{id}` | Détail (offres publiées accessibles sans compte ; brouillon réservé aux autorisés) |

---

## Étudiant (`role:student`)

| Méthode | Endpoint | Description |
|--------|----------|-------------|
| GET | `/student/profile` | Profil |
| PUT | `/student/profile` | Mise à jour (`student_number`, `phone`, `bio`) |
| POST | `/student/cv` | Upload CV (`multipart/form-data`, champ `cv`) |
| GET | `/student/cv/download` | Téléchargement du CV |
| GET | `/student/applications` | Candidatures |
| POST | `/student/internships/{internship}/apply` | Postuler (`cover_letter` optionnel) |
| POST | `/student/applications/{application}/report` | Déposer un rapport (`file`, `title` optionnel) |

---

## Entreprise (`role:company`)

| Méthode | Endpoint | Description |
|--------|----------|-------------|
| GET | `/company/profile` | Profil entreprise |
| PUT | `/company/profile` | Mise à jour profil |
| GET | `/company/internships` | Mes offres (entreprise **approuvée**) |
| POST | `/company/internships` | Créer une offre |
| PUT | `/company/internships/{internship}` | Modifier |
| DELETE | `/company/internships/{internship}` | Supprimer |
| GET | `/company/internships/{internship}/applications` | Candidatures pour une offre |
| PATCH | `/company/applications/{application}` | Changer le statut (`status`, `supervisor_id` optionnel si `accepted`) |

---

## Superviseur (`role:supervisor`)

| Méthode | Endpoint | Description |
|--------|----------|-------------|
| GET | `/supervisor/students` | Étudiants assignés |
| GET | `/supervisor/reports/pending` | Rapports à valider |
| PATCH | `/supervisor/reports/{report}/validate` | Valider / refuser (`status`: validated \| rejected, `feedback`) |
| POST | `/supervisor/evaluations` | Créer une évaluation (`application_id`, `score` 1–5, `criteria`, `comments`) |
| GET | `/supervisor/evaluations/{evaluation}` | Détail évaluation |

---

## Admin (`role:admin`)

| Méthode | Endpoint | Description |
|--------|----------|-------------|
| GET | `/admin/dashboard` | Statistiques (totaux, candidatures acceptées, répartition par offre) |
| GET | `/admin/students` | Liste paginée étudiants |
| GET | `/admin/companies` | Liste paginée entreprises |
| GET | `/admin/internships` | Liste paginée stages |
| PATCH | `/admin/companies/{company}/approval` | Approuver / refuser (`approval_status`: approved \| rejected) |

---

## Notifications & fichiers

| Méthode | Endpoint | Description |
|--------|----------|-------------|
| GET | `/notifications` | Notifications plateforme (table `platform_notifications`) |
| PATCH | `/notifications/{id}/read` | Marquer comme lue |
| GET | `/reports/{report}/download` | Télécharger un rapport (autorisations via policy) |

---

## Codes HTTP usuels

- `200` / `201` : succès  
- `401` : non authentifié  
- `403` : interdit (rôle ou policy)  
- `404` : ressource introuvable  
- `422` : erreur de validation  

Les messages d’erreur de validation suivent le format Laravel (`errors` par champ).
