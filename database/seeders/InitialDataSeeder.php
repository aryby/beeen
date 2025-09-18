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
        $admin = User::create([
            'name' => 'Administrateur',
            'email' => 'admin@iptv.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Créer les abonnements par défaut
        $subscriptions = [
            ['name' => '1 Mois', 'duration_months' => 1, 'price' => 15.99],
            ['name' => '3 Mois', 'duration_months' => 3, 'price' => 39.99],
            ['name' => '6 Mois', 'duration_months' => 6, 'price' => 69.99],
            ['name' => '12 Mois', 'duration_months' => 12, 'price' => 119.99],
        ];

        foreach ($subscriptions as $sub) {
            Subscription::create(array_merge($sub, [
                'description' => 'Accès complet à plus de 1000 chaînes HD, VOD illimité, sans publicité avec support 24/7.',
                'is_active' => true,
            ]));
        }

        // Créer les packs revendeurs par défaut
        $resellerPacks = [
            ['name' => '50 Crédits', 'credits' => 50, 'price' => 199.99],
            ['name' => '100 Crédits', 'credits' => 100, 'price' => 349.99],
            ['name' => '250 Crédits', 'credits' => 250, 'price' => 799.99],
            ['name' => '500 Crédits', 'credits' => 500, 'price' => 1499.99],
        ];

        foreach ($resellerPacks as $pack) {
            ResellerPack::create(array_merge($pack, [
                'description' => 'Pack de crédits pour générer des codes IPTV.',
                'is_active' => true,
            ]));
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

        $this->command->info('Données initiales créées avec succès !');
        $this->command->info('Admin: admin@iptv.com / admin123');
    }
}