# PeerSync-Plateforme-de-Tutorat-ENAA
# PeerSync - Plateforme de Tutorat ENAA

PeerSync est une plateforme de tutorat développée en PHP permettant aux apprenants de demander de l’aide académique et aux tuteurs d’aider les étudiants selon leurs compétences.

---

# Fonctionnalités

## Authentification
** Inscription des apprenants
** Connexion sécurisée
** Gestion des sessions
** Logout

## Dashboard
** Sidebar moderne avec Tailwind CSS
** Affichage du profil utilisateur
** Affichage des demandes d’aide

## Gestion des demandes d’aide
** Création de tickets d’aide
** Affichage des tickets
** Gestion du statut des demandes :
  **  EN_ATTENTE
  ** ASSIGNE
  ** RESOLUE

## Compétences
**Gestion des compétences des apprenants
** Tags technologiques :
  **  PHP
  ** JavaScript
  ** SQL
  ** Laravel
  ** etc.

---

# Technologies utilisées

** PHP 8
** MySQL
** Tailwind CSS
** HTML5
** PDO
** Enum PHP

---

# Structure du projet

```txt
PeerSync/
│
├── config/
│   └── Database.php
│
├── public/
│   ├── dashboard.php
│   ├── profil.php
│   ├── create_request.php
│   └── request_detail.php
│
├── scripts/
│   ├── login_process.php
│   ├── register_process.php
│   ├── create_request_process.php
│   └── logout.php
│
├── src/
│   ├── Entity/
│   │   └── HelpRequest.php
│   │
│   ├── Enum/
│   │   └── Status.php
│   │
│   └── Repository/
│       └── UserRepository.php
│
└── README.md