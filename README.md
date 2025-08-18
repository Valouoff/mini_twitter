# 🐦 Mini Twitter  

Un projet d'application web inspirée de Twitter, permettant aux utilisateurs de publier des tweets, interagir avec ceux des autres et gérer leur profil. L'application inclut également un espace d'administration pour la gestion des utilisateurs et du contenu.  

---

## 🚀 Présentation  

Mini Twitter est une application développée avec **Symfony** et **MySQL**, offrant une expérience proche d'un réseau social classique.  
Elle met en avant la gestion des utilisateurs, des tweets, des commentaires, des likes/retweets, et un panneau d'administration pour modérer et administrer le site.  

---

## ✨ Fonctionnalités clés  

### 👤 Côté Utilisateurs  
- Inscription et authentification sécurisée (Symfony Security)  
- Création, édition et suppression de tweets  
- Possibilité d’aimer et de retweeter les tweets  
- Commentaires sur les tweets  
- Système de suivi (follow/unfollow) entre utilisateurs  
- Gestion du profil (avatar, bio, informations personnelles)  
- Fil d’actualité personnalisé avec les tweets des personnes suivies  

### 🔑 Côté Administrateur  
- Tableau de bord d’administration  
- Gestion des utilisateurs (activation/désactivation, suppression)  
- Suppression et modération des tweets inappropriés  
- Gestion des rôles (Utilisateur, Admin)  

---

## 🗄️ Base de données (MySQL)  

Voici les principales tables utilisées :  

- **user** : stocke les informations des utilisateurs (id, username, email, password, avatar, roles, date d’inscription)  
- **tweet** : contient les tweets (id, contenu, date, user_id)  
- **comment** : gère les commentaires liés aux tweets (id, contenu, date, user_id, tweet_id)  
- **like** : enregistre les likes des utilisateurs sur les tweets (id, user_id, tweet_id)  
- **retweet** : gère les retweets (id, user_id, tweet_id)  
- **follow** : table de relation pour le système de suivi (follower_id, followed_id)  

---

## 🛠️ Technologies utilisées  

- **Symfony 7** – Framework PHP principal  
- **Doctrine ORM** – Gestion de la base de données  
- **MySQL** – Système de gestion de base de données  
- **Twig** – Moteur de templates  
- **Tailwind CSS** – Framework CSS pour le design responsive  
- **PHP 8.2+**  
- **Composer** – Gestionnaire de dépendances PHP  
- **Git** – Gestion de version  

---

## ⚙️ Installation et lancement du projet  

### Pré-requis  
- PHP >= 8.2  
- Composer  
- MySQL  
- Node.js & npm (pour compiler Tailwind CSS)  

### Étapes d’installation  

```bash
# Cloner le dépôt
git clone https://github.com/username/mini-twitter.git
cd mini-twitter

# Installer les dépendances PHP
composer install

# Installer les dépendances front-end
npm install
npm run dev

# Configurer l'environnement
cp .env .env.local
# Modifier le fichier .env.local avec vos identifiants MySQL

# Créer la base de données
php bin/console doctrine:database:create

# Appliquer les migrations
php bin/console doctrine:migrations:migrate

# Lancer le serveur Symfony
symfony server:start
```

🌳 Arborescence principale du projet


```mini-twitter/
│── assets/              # Fichiers front-end (CSS, JS, Tailwind)
│── config/              # Configuration Symfony
│── migrations/          # Fichiers de migration Doctrine
│── public/              # Racine publique (index.php, assets compilés)
│── src/                 # Code source Symfony (Controllers, Entities, Services)
│   ├── Controller/      
│   ├── Entity/          
│   ├── Form/            
│   └── Repository/      
│── templates/           # Vues Twig
│── translations/        # Fichiers de traduction
│── var/                 # Fichiers temporaires
│── vendor/              # Dépendances installées par Composer
│── .env                 # Configuration de l'environnement
│── composer.json        # Dépendances PHP
│── package.json         # Dépendances JS
```

🔑 Accès utilisateur

Utilisateur standard : peut s’inscrire et accéder directement aux fonctionnalités publiques

Administrateur : accès au panneau d’administration (via rôle ROLE_ADMIN)

👥 Auteurs

Sylvie PORTEBOEUF / Killian BOUHIER / Valentin GUILLAIS / Maxime VERTUEUX

📜 Licence

Ce projet est sous licence MIT – vous êtes libre de l’utiliser, le modifier et le partager.
