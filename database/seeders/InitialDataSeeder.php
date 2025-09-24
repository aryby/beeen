<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Subscription;
use App\Models\ResellerPack;
use App\Models\Setting;
use App\Models\Tutorial;
use App\Models\TutorialStep;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer l'utilisateur admin par défaut
        $admin = User::updateOrCreate(
            ['email' => 'admin@iptv.com'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Créer les abonnements par défaut
        $subscriptions = [
            ['name' => '1 Mois', 'duration_months' => 1, 'price' => 15.99],
            ['name' => '3 Mois', 'duration_months' => 3, 'price' => 39.99],
            ['name' => '6 Mois', 'duration_months' => 6, 'price' => 69.99],
            ['name' => '12 Mois', 'duration_months' => 12, 'price' => 119.99],
        ];

        foreach ($subscriptions as $sub) {
            Subscription::updateOrCreate(
                ['name' => $sub['name']],
                array_merge($sub, [
                    'description' => 'Accès complet à plus de 12000 chaînes HD, VOD illimité, sans publicité avec support 24/7.',
                    'is_active' => true,
                ])
            );
        }

        // Créer les packs revendeurs par défaut
        $resellerPacks = [
            ['name' => '50 Crédits', 'credits' => 50, 'price' => 199.99],
            ['name' => '100 Crédits', 'credits' => 100, 'price' => 349.99],
            ['name' => '250 Crédits', 'credits' => 250, 'price' => 799.99],
            ['name' => '500 Crédits', 'credits' => 500, 'price' => 1499.99],
        ];

        foreach ($resellerPacks as $pack) {
            ResellerPack::updateOrCreate(
                ['name' => $pack['name']],
                array_merge($pack, [
                    'description' => 'Pack de crédits pour générer des codes IPTV.',
                    'is_active' => true,
                ])
            );
        }

        // Initialiser les paramètres par défaut
        Setting::initializeDefaults();

        // Créer quelques tutoriels exemple
        $androidTutorial = Tutorial::create([
            'title' => 'Configuration IPTV sur Android',
            'device_type' => 'android',
            'intro' => 'Apprenez à configurer votre service IPTV sur votre appareil Android en quelques étapes simples.',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $androidSteps = [
            [
                'title' => 'Télécharger l\'application',
                'content' => '<p>Téléchargez et installez l\'application <strong>IPTV Smarters Pro</strong> depuis le Google Play Store.</p>',
                'step_order' => 1,
            ],
            [
                'title' => 'Configurer les paramètres',
                'content' => '<p>Ouvrez l\'application et sélectionnez <em>"Ajouter un utilisateur"</em>. Choisissez le type de connexion <strong>"Xtream Codes API"</strong>.</p>',
                'step_order' => 2,
            ],
            [
                'title' => 'Saisir vos identifiants',
                'content' => '<p>Entrez vos identifiants IPTV que vous avez reçus par email :</p><ul><li>Nom d\'utilisateur</li><li>Mot de passe</li><li>URL du serveur</li></ul>',
                'step_order' => 3,
            ],
            [
                'title' => 'Profiter du contenu',
                'content' => '<p>Une fois connecté, vous pouvez accéder à toutes vos chaînes et contenus VOD. Bon visionnage !</p>',
                'step_order' => 4,
            ],
        ];

        foreach ($androidSteps as $step) {
            $androidTutorial->steps()->create($step);
        }

        // Tutoriel Smart TV
        $smartTvTutorial = Tutorial::create([
            'title' => 'Configuration IPTV sur Smart TV',
            'device_type' => 'smart_tv',
            'intro' => 'Guide complet pour configurer IPTV sur votre Smart TV Samsung, LG ou autre.',
            'is_published' => true,
            'sort_order' => 2,
        ]);

        $smartTvSteps = [
            [
                'title' => 'Installer l\'application IPTV',
                'content' => '<p>Recherchez et installez une application IPTV compatible depuis le store de votre Smart TV (Smart IPTV, SS IPTV, etc.)</p>',
                'step_order' => 1,
            ],
            [
                'title' => 'Obtenir l\'adresse MAC',
                'content' => '<p>Notez l\'adresse MAC affichée dans l\'application. Vous en aurez besoin pour l\'activation.</p>',
                'step_order' => 2,
            ],
            [
                'title' => 'Activer votre liste',
                'content' => '<p>Utilisez vos identifiants pour activer votre liste de chaînes sur l\'application.</p>',
                'step_order' => 3,
            ],
        ];

        foreach ($smartTvSteps as $step) {
            $smartTvTutorial->steps()->create($step);
        }

        // Créer quelques témoignages exemple
        $testimonials = [
            [
                'customer_name' => 'Pierre M.',
                'customer_location' => 'Paris, France',
                'testimonial' => 'Excellent service ! La qualité des chaînes est parfaite et le support client très réactif. Je recommande vivement.',
                'rating' => 5,
                'is_published' => true,
                'sort_order' => 1,
            ],
            [
                'customer_name' => 'Marie L.',
                'customer_location' => 'Lyon, France',
                'testimonial' => 'Très satisfaite de mon abonnement. Beaucoup de chaînes disponibles et une interface facile à utiliser.',
                'rating' => 5,
                'is_published' => true,
                'sort_order' => 2,
            ],
            [
                'customer_name' => 'Jean D.',
                'customer_location' => 'Marseille, France',
                'testimonial' => 'Service stable et fiable. Les tutoriels m\'ont beaucoup aidé pour la configuration.',
                'rating' => 4,
                'is_published' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }

        // Initialiser les pages légales avec du contenu par défaut
        $this->initializeLegalPages();

        $this->command->info('Données initiales créées avec succès !');
        $this->command->info('Admin: admin@iptv.com / admin123');
    }

    /**
     * Initialise les pages légales avec du contenu par défaut
     */
    private function initializeLegalPages()
    {
        // Conditions Générales de Vente
        Setting::updateOrCreate(
            ['key' => 'terms_of_service'],
            [
                'value' => $this->getTermsOfServiceContent(),
                'type' => 'text',
                'description' => 'Conditions générales de vente'
            ]
        );

        // Politique de Confidentialité
        Setting::updateOrCreate(
            ['key' => 'privacy_policy'],
            [
                'value' => $this->getPrivacyPolicyContent(),
                'type' => 'text',
                'description' => 'Politique de confidentialité'
            ]
        );

        // Mentions Légales
        Setting::updateOrCreate(
            ['key' => 'legal_mentions'],
            [
                'value' => $this->getLegalMentionsContent(),
                'type' => 'text',
                'description' => 'Mentions légales'
            ]
        );
    }

    private function getTermsOfServiceContent()
    {
        return '<h1>Conditions Générales de Vente</h1>

<h2>1. Objet</h2>
<p>Les présentes conditions générales de vente (CGV) régissent les relations contractuelles entre IPTV Premium et ses clients concernant la fourniture de services IPTV.</p>

<h2>2. Services proposés</h2>
<p>IPTV Premium propose des abonnements permettant l\'accès à des chaînes de télévision et contenus VOD via Internet. Les services incluent :</p>
<ul>
<li>Plus de 12 000 chaînes HD/4K</li>
<li>Accès VOD illimité</li>
<li>Diffusion sans publicité</li>
<li>Support technique 24/7</li>
<li>Compatibilité multi-appareils</li>
</ul>

<h2>3. Tarifs et facturation</h2>
<p>Les tarifs des abonnements sont indiqués en euros TTC sur le site. Les prix peuvent être modifiés à tout moment. Les abonnements sont facturés à l\'avance pour la période choisie.</p>

<h2>4. Paiement</h2>
<p>Le paiement s\'effectue exclusivement via PayPal. Le service est activé immédiatement après confirmation du paiement.</p>

<h2>5. Durée et renouvellement</h2>
<p>Les abonnements sont souscrits pour la durée choisie (1, 3, 6 ou 12 mois). Le renouvellement n\'est pas automatique.</p>

<h2>6. Droit de rétractation</h2>
<p>Conformément à la législation en vigueur, vous disposez d\'un délai de 14 jours pour exercer votre droit de rétractation.</p>

<h2>7. Responsabilité</h2>
<p>IPTV Premium s\'engage à fournir un service de qualité mais ne peut garantir une disponibilité à 100%. Les interruptions techniques ne donnent pas lieu à remboursement.</p>

<h2>8. Utilisation</h2>
<p>Le service est destiné à un usage personnel uniquement. Toute utilisation commerciale ou de revente est interdite.</p>

<h2>9. Loi applicable</h2>
<p>Les présentes CGV sont soumises au droit français.</p>';
    }

    private function getPrivacyPolicyContent()
    {
        return '<h1>Politique de Confidentialité</h1>

<h2>1. Collecte des données</h2>
<p>IPTV Premium collecte les données personnelles suivantes :</p>
<ul>
<li>Nom et prénom</li>
<li>Adresse email</li>
<li>Informations de paiement (via PayPal)</li>
<li>Type d\'appareil utilisé</li>
<li>Adresse MAC (pour certains appareils)</li>
</ul>

<h2>2. Utilisation des données</h2>
<p>Vos données personnelles sont utilisées pour :</p>
<ul>
<li>Fournir le service IPTV</li>
<li>Traiter vos commandes et paiements</li>
<li>Vous envoyer des confirmations et communications</li>
<li>Assurer le support technique</li>
<li>Respecter nos obligations légales</li>
</ul>

<h2>3. Partage des données</h2>
<p>Nous ne vendons jamais vos données personnelles. Elles peuvent être partagées uniquement avec :</p>
<ul>
<li>PayPal pour le traitement des paiements</li>
<li>Nos prestataires techniques (sous contrat de confidentialité)</li>
<li>Les autorités compétentes si requis par la loi</li>
</ul>

<h2>4. Sécurité</h2>
<p>Nous mettons en œuvre des mesures de sécurité appropriées pour protéger vos données contre l\'accès non autorisé, la modification, la divulgation ou la destruction.</p>

<h2>5. Vos droits</h2>
<p>Conformément au RGPD, vous disposez des droits suivants :</p>
<ul>
<li>Droit d\'accès à vos données</li>
<li>Droit de rectification</li>
<li>Droit à l\'effacement</li>
<li>Droit à la portabilité</li>
<li>Droit d\'opposition</li>
</ul>

<h2>6. Cookies</h2>
<p>Notre site utilise des cookies pour améliorer votre expérience de navigation et analyser l\'utilisation du site.</p>

<h2>7. Contact</h2>
<p>Pour toute question concernant cette politique de confidentialité, contactez-nous via notre formulaire de contact.</p>';
    }

    private function getLegalMentionsContent()
    {
        return '<h1>Mentions Légales</h1>

<h2>1. Éditeur du site</h2>
<p><strong>IPTV Premium</strong><br>
Service IPTV Légal<br>
France<br>
Email : contact@iptv-premium.com</p>

<h2>2. Hébergement</h2>
<p>Le site est hébergé par un prestataire professionnel respectant les standards de sécurité et de disponibilité.</p>

<h2>3. Propriété intellectuelle</h2>
<p>Le contenu de ce site (textes, images, logos) est protégé par le droit d\'auteur. Toute reproduction est interdite sans autorisation préalable.</p>

<h2>4. Responsabilité</h2>
<p>IPTV Premium s\'efforce de fournir des informations exactes et à jour, mais ne peut garantir l\'exactitude complète des informations diffusées.</p>

<h2>5. Liens externes</h2>
<p>Notre site peut contenir des liens vers d\'autres sites web. Nous ne sommes pas responsables du contenu de ces sites externes.</p>

<h2>6. Collecte de données</h2>
<p>Les données collectées sur ce site sont traitées conformément à notre Politique de Confidentialité et au RGPD.</p>

<h2>7. Droit applicable</h2>
<p>Le présent site est soumis au droit français. En cas de litige, les tribunaux français seront seuls compétents.</p>

<h2>8. Contact</h2>
<p>Pour toute question concernant ces mentions légales, vous pouvez nous contacter via notre formulaire de contact.</p>';
    }
}