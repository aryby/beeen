<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de votre demande de test IPTV</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #17c1e8;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #17c1e8;
            margin-bottom: 10px;
        }
        .title {
            color: #2d3748;
            font-size: 20px;
            margin-bottom: 20px;
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #17c1e8;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
        .device-info {
            background: #e3f2fd;
            border: 1px solid #17c1e8;
            border-radius: 5px;
            padding: 15px;
            margin: 15px 0;
        }
        .status-badge {
            display: inline-block;
            background: #ffc107;
            color: #000;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }
        .next-steps {
            background: #d4edda;
            border: 1px solid #28a745;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background: #17c1e8;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 10px 5px;
        }
        .btn:hover {
            background: #138496;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">IPTV Premium</div>
            <h1 class="title">Confirmation de votre demande de test</h1>
        </div>

        <p>Bonjour <strong>{{ $testRequest->name }}</strong>,</p>

        <p>Nous avons bien reçu votre demande de test IPTV 48h. Votre demande est actuellement <span class="status-badge">En attente de validation</span>.</p>

        <div class="info-box">
            <h3 style="margin-top: 0; color: #17c1e8;">Détails de votre demande</h3>
            <p><strong>Nom :</strong> {{ $testRequest->name }}</p>
            <p><strong>Email :</strong> {{ $testRequest->name }}</p>
            <p><strong>Date de demande :</strong> {{ $testRequest->created_at->format('d/m/Y à H:i') }}</p>
        </div>

        <div class="device-info">
            <h4 style="margin-top: 0; color: #17c1e8;">Informations sur votre appareil</h4>
            <p><strong>Type d'appareil :</strong> {{ $testRequest->device_type_label }}</p>
            @if($testRequest->mac_address)
                <p><strong>Adresse MAC :</strong> {{ $testRequest->mac_address }}</p>
            @endif
            @if($testRequest->notes)
                <p><strong>Notes :</strong> {{ $testRequest->notes }}</p>
            @endif
        </div>

        <div class="next-steps">
            <h4 style="margin-top: 0; color: #28a745;">Prochaines étapes</h4>
            <ol>
                <li><strong>Validation :</strong> Notre équipe va examiner votre demande dans les plus brefs délais</li>
                <li><strong>Activation :</strong> Une fois approuvée, vous recevrez vos identifiants de test par email</li>
                <li><strong>Test :</strong> Vous pourrez profiter de 48h d'accès complet à notre service IPTV</li>
                <li><strong>Décision :</strong> À la fin du test, vous pourrez choisir de vous abonner</li>
            </ol>
        </div>

        <div class="info-box">
            <h4 style="margin-top: 0; color: #17c1e8;">Que contient le test ?</h4>
            <ul>
                <li>Accès à plus de 12 000 chaînes HD/4K</li>
                <li>VOD illimité</li>
                <li>Sans publicité</li>
                <li>Support technique inclus</li>
                <li>Compatible avec votre {{ $testRequest->device_type_label }}</li>
            </ul>
        </div>

        <p>Si vous avez des questions ou besoin d'aide, n'hésitez pas à nous contacter.</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('contact.index') }}" class="btn">Nous contacter</a>
            <a href="{{ route('tutorials.index') }}" class="btn" style="background: #28a745;">Voir les tutoriels</a>
        </div>

        <div class="footer">
            <p><strong>IPTV Premium</strong> - Service IPTV Légal</p>
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
            <p>Pour toute question, contactez-nous via notre site web.</p>
        </div>
    </div>
</body>
</html>
