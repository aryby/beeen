# 📺 Site IPTV Légal - Laravel 12

Une application web complète pour la vente d'abonnements IPTV avec système de revendeurs, back-office administrateur et interface moderne.

## 🚀 Installation et Démarrage

### Prérequis
- PHP 8.2+
- Composer
- Node.js 20.19+ ou 22.12+
- SQLite (ou MySQL/PostgreSQL)

### Installation
```bash
# 1. Cloner le projet
git clone <votre-repo>
cd beeen

# 2. Installer les dépendances
composer install
npm install

# 3. Configuration
cp .env.example .env
php artisan key:generate

# 4. Base de données
php artisan migrate
php artisan db:seed

# 5. Compiler les assets
npm run build

# 6. Lancer le serveur
php artisan serve
```

## 🔐 Comptes par Défaut

### Administrateur
- **Email:** `admin@iptv.com`
- **Mot de passe:** `admin123`
- **Accès:** `http://localhost:8000/admin/dashboard`

## 📋 Fonctionnalités Implémentées

### ✅ Module Visiteur (Frontend Public)
- **Page d'Accueil** : Présentation du service, avantages clés, offres d'abonnements
- **Page Abonnements** : Cartes d'abonnements avec prix dynamiques, checkout sécurisé
- **Page Tutoriels** : Guides catégorisés par appareil avec recherche et filtres
- **Page Contact** : Formulaire avec reCAPTCHA et envoi automatique d'emails
- **Pages Légales** : CGV, mentions légales, politique de confidentialité (configurables)

### ✅ Module Revendeur
- **Page Publique** : Présentation des packs revendeur
- **Dashboard Revendeur** : Gestion des crédits, génération de codes IPTV
- **Système de Crédits** : Achat de packs, consommation pour génération de codes
- **Historique** : Suivi complet des transactions

### ✅ Module Administration (Back-Office)
- **Tableau de Bord** : Statistiques temps réel, graphiques revenus, activité
- **Gestion Abonnements** : CRUD complet avec aperçu en temps réel
- **Gestion Commandes** : Liste, filtres, validation, statuts
- **Gestion Revendeurs** : Attribution/suppression crédits, historique
- **Gestion Messages** : Inbox centralisée, réponses, statuts
- **Paramètres Globaux** : SMTP, PayPal, reCAPTCHA, contenu légal

### ✅ Système d'Emails
- **Confirmation Commande** : Email automatique avec identifiants IPTV
- **Confirmation Contact** : Accusé de réception pour les clients
- **Notification Admin** : Nouveaux messages de contact
- **Templates Markdown** : Design professionnel et responsive

### ✅ Intégrations
- **Bootstrap 5** : Interface moderne et responsive
- **Chart.js** : Graphiques du tableau de bord
- **reCAPTCHA** : Protection anti-spam
- **Laravel Breeze** : Authentification sécurisée
- **DomPDF** : Génération de factures (prêt)

## 🗂️ Structure de la Base de Données

### Tables Principales
- `users` - Utilisateurs (clients, revendeurs, admins)
- `subscriptions` - Formules d'abonnements IPTV
- `orders` - Commandes clients avec codes IPTV
- `resellers` - Comptes revendeurs avec crédits
- `reseller_packs` - Packs de crédits pour revendeurs
- `reseller_transactions` - Historique des transactions revendeurs
- `tutorials` - Tutoriels d'installation
- `tutorial_steps` - Étapes détaillées des tutoriels
- `messages` - Messages de contact et support
- `testimonials` - Témoignages clients
- `settings` - Paramètres configurables du site

## 🔧 Configuration

### Paramètres SMTP (Admin > Paramètres)
```
smtp_host: votre-serveur-smtp.com
smtp_port: 587
smtp_username: votre-email@domaine.com
smtp_password: votre-mot-de-passe
smtp_encryption: tls
```

### Paramètres PayPal (Admin > Paramètres)
```
paypal_client_id: votre-client-id-paypal
paypal_client_secret: votre-client-secret
paypal_sandbox: true (pour les tests)
```

### reCAPTCHA (Admin > Paramètres)
```
recaptcha_site_key: votre-site-key
recaptcha_secret_key: votre-secret-key
```

## 🌐 URLs Principales

### Frontend Public
- **Accueil** : `/`
- **Abonnements** : `/subscriptions`
- **Tutoriels** : `/tutorials`
- **Contact** : `/contact`
- **Revendeurs** : `/resellers`

### Espaces Privés
- **Connexion** : `/login`
- **Inscription** : `/register`
- **Dashboard Client** : `/customer/dashboard`
- **Dashboard Revendeur** : `/reseller/dashboard`
- **Administration** : `/admin/dashboard`

## 🎨 Design et UX

### Responsive Design
- Mobile-first avec Bootstrap 5
- Dark/Light mode automatique
- Animations et transitions fluides
- Interface intuitive et moderne

### Couleurs Principales
- **Primaire** : #0d6efd (Bleu)
- **Succès** : #198754 (Vert)
- **Warning** : #ffc107 (Jaune)
- **Danger** : #dc3545 (Rouge)
- **Info** : #0dcaf0 (Cyan)

## 🔒 Sécurité

### Mesures Implémentées
- **CSRF Protection** : Tous les formulaires
- **XSS Protection** : Échappement automatique
- **Rate Limiting** : Protection contre le spam
- **reCAPTCHA** : Formulaires publics
- **Rôles Utilisateurs** : Admin, Revendeur, Client
- **Middleware** : Contrôle d'accès par rôle

## 🚀 Fonctionnalités Avancées

### Système de Crédits Revendeur
- Achat de packs avec différents volumes
- Génération de codes IPTV en temps réel
- Historique complet des transactions
- Renouvellement automatique des packs

### Gestion des Tutoriels
- Catégorisation par type d'appareil
- Étapes détaillées avec images/vidéos
- Navigation séquentielle
- Recherche et filtres avancés

### Back-Office Complet
- Tableau de bord avec statistiques temps réel
- Graphiques de revenus mensuels
- Gestion complète de tous les contenus
- Système de notifications

## 📊 Statistiques et Rapports

### Métriques Disponibles
- Revenus journaliers/mensuels
- Commandes par statut
- Activité des revendeurs
- Messages non lus
- Abonnements populaires

## 🔮 Évolutions Futures

### Prêt pour
- **PayPal API Réelle** : Structure en place
- **Génération PDF** : DomPDF configuré
- **Système d'Affiliation** : Base de données préparée
- **API Mobile** : Architecture extensible
- **Notifications Push** : Infrastructure email prête

## 📞 Support

### Pour les Développeurs
- Code bien documenté et structuré
- Architecture Laravel standard
- Modèles avec relations complètes
- Migrations versionnées

### Pour les Utilisateurs
- Interface intuitive
- Tutoriels détaillés
- Support 24/7 (configurable)
- FAQ intégrée

---

## 🎯 Points Clés

✅ **Application complète et fonctionnelle**  
✅ **Interface moderne et responsive**  
✅ **Back-office administrateur complet**  
✅ **Système de revendeurs avec crédits**  
✅ **Emails automatiques configurés**  
✅ **Sécurité renforcée**  
✅ **Prêt pour la production**

**Développé avec ❤️ en Laravel 12**