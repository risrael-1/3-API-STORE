Description du projet

Ce projet est une API REST simple en PHP pour gérer la ressource “magasin”.
- L’API utilise le format JSON pour les entrées et sorties.
- Actuellement, seule la route create est implémentée (POST /stores).
- Les prochaines mises à jour incluront le CRUD complet avec filtrage et tri.

Prérequis

- PHP 8.2+
- MySQL ou MariaDB
- Composer (optionnel, pour des packages futurs)
- Serveur web local (le serveur intégré de PHP est recommandé)

Configuration de la base de données

1. Se connecter à MySQL :

mysql -u root -p

2. Créer la base de données :

CREATE DATABASE wshop CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE wshop;

3. Créer la table stores :

CREATE TABLE stores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address VARCHAR(255),
    city VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

Lancer l’API

1. Aller dans le dossier api/ du projet :

cd api


2. Démarrer le serveur PHP intégré :

php -S localhost:8000

L’API sera alors disponible sur http://localhost:8000.


Points de terminaison de l’API

Créer un magasin

URL : /stores
Méthode : POST
Headers : Content-Type: application/json
Corps (JSON) :

{
  "name": "Magasin Test",
  "address": "123 Rue Exemple",
  "city": "Paris"
}

Réponse :

{
  "message": "Store created",
  "id": 1
}

Code HTTP : 201 Created

Erreurs :
400 Bad Request si des champs obligatoires sont manquants

Notes

- Vérifier que les paramètres de connexion à la base de données dans config/database.php correspondent à vos identifiants MySQL.
- Garder le serveur PHP lancé pendant les tests de l’API.
- Utiliser Postman ou PowerShell/curl pour envoyer les requêtes.