# WSHOP API

## Description du projet

Ce projet est une API REST simple en PHP pour gérer les ressources **magasins** et **utilisateurs**.

- L’API utilise le format JSON pour les entrées et sorties.
- Elle permet de gérer les magasins avec un CRUD complet :
  - `GET /stores` → Récupérer tous les magasins
  - `GET /store?id={id}` → Récupérer un magasin par ID
  - `POST /store` → Créer un magasin
  - `PUT /store?id={id}` → Mettre à jour un magasin
  - `DELETE /store?id={id}` → Supprimer un magasin
- L’API inclut un système d’authentification pour protéger les routes sensibles :
  - `POST /register` → Créer un utilisateur
  - `POST /login` → Se connecter
  - `POST /logout` → Se déconnecter
  - `GET /me` → Vérifier l’utilisateur connecté

---

## Prérequis

- PHP 8.2+
- MySQL ou MariaDB
- Serveur web local (le serveur intégré de PHP)

---

## Configuration de la base de données

1. Se connecter à MySQL :

```bash
mysql -u root -p
```

2. Créer la base de données :

```sql
CREATE DATABASE wshop CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE wshop;
```

3. Créer la table stores :

```sql
CREATE TABLE stores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address VARCHAR(255),
    city VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```


4. Créer la table users :

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```


5. Configurer la connexion à la base dans config/database.php :

```php
<?php
return [
    'host' => 'localhost',
    'port' => 3306,
    'dbname' => 'wshop',
    'user' => 'root',
    'password' => ''
];
```

## Lancer l’API

1. Aller dans le dossier racine du projet

2. Démarrer le serveur PHP intégré :

```php
php -S localhost:8000
```

L’API sera alors disponible sur http://localhost:8000.

## Differentes routes

## Authentification

1. Créer un utilisateur (inscription)

URL : /register
Méthode : POST
Headers : Content-Type: application/json
Body (JSON) :

```json
{
  "username": "testuser",
  "password": "123456"
}
```

Réponse :

```json
{
  "message": "User registered",
  "id": 1
}
```

Code HTTP : 201 Created


2. Se connecter (login)

URL : /login
Méthode : POST
Body (JSON) :

```json
{
  "username": "testuser",
  "password": "123456"
}
```

Réponse :

```json
{
  "message": "Login successful"
}
```

3. Vérifier l’utilisateur connecté

URL : /me
Méthode : GET
Réponse :

```json
{
  "id": 1,
  "username": "testuser"
}
```

4. Se déconnecter (logout)

URL : /logout
Méthode : POST
Réponse :

```json
{
  "message": "Logged out"
}
```

## Magasins (Stores)

1. Récupérer tous les magasins

URL : /stores
Méthode : GET
Réponse : JSON avec tous les magasins


2. Récupérer un magasin par ID

URL : /store?id={id}
Méthode : GET
Réponse : JSON du magasin demandé

3. Créer un magasin

URL : /store
Méthode : POST
Body (JSON) :

```json
{
  "name": "Magasin Test",
  "address": "123 Rue Exemple",
  "city": "Paris"
}
```


Réponse :

```json
{
  "id": 1
}
```

4. Mettre à jour un magasin

URL : /store?id={id}
Méthode : PUT
Body (JSON) :

```json
{
  "name": "Magasin Modifié",
  "city": "Lyon"
}
```


Réponse :

```json
{
  "success": true
}
```

5. Supprimer un magasin

URL : /store?id={id}
Méthode : DELETE

Réponse :

```json
{
  "success": true
}
```

Notes

Vérifier que les paramètres de connexion à la base de données dans config/database.php correspondent à vos identifiants MySQL.
Garder le serveur PHP lancé pendant les tests de l’API.
Utiliser Postman ou curl pour envoyer les requêtes.
Les routes protégées nécessitent une session active (cookies).
Les champs obligatoires doivent toujours être fournis dans les requêtes POST et PUT.