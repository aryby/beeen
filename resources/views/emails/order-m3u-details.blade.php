<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vos identifiants IPTV</title>
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
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #cb0c9f 0%, #9c27b0 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .content {
            padding: 30px;
        }
        .credentials-box {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .credential-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .credential-item:last-child {
            border-bottom: none;
        }
        .credential-label {
            font-weight: 600;
            color: #495057;
        }
        .credential-value {
            font-family: 'Courier New', monospace;
            background: #e9ecef;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            word-break: break-all;
        }
        .copy-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            margin-left: 10px;
        }
        .copy-btn:hover {
            background: #218838;
        }
        .instructions {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 20px;
            margin: 20px 0;
        }
        .instructions h3 {
            margin-top: 0;
            color: #1976d2;
        }
        .instructions ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        .instructions li {
            margin: 8px 0;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #cb0c9f;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 10px 5px;
        }
        .btn:hover {
            background: #9c27b0;
            color: white;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .highlight {
            background: #fff3cd;
            padding: 2px 4px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🎉 Vos identifiants IPTV sont prêts !</h1>
            <p>Commande {{ $orderNumber }} - {{ $subscriptionName }}</p>
        </div>

        <div class="content">
            <p>Bonjour <strong>{{ $customerName }}</strong>,</p>
            
            <p>Votre commande <strong>{{ $orderNumber }}</strong> a été validée avec succès ! Voici vos identifiants IPTV :</p>

            <div class="credentials-box">
                <h3 style="margin-top: 0; color: #495057;">🔑 Identifiants de connexion</h3>
                
                <div class="credential-item">
                    <span class="credential-label">Code IPTV :</span>
                    <span class="credential-value" id="iptv-code">{{ $iptvCode }}</span>
                    <button class="copy-btn" onclick="copyToClipboard('iptv-code')">Copier</button>
                </div>

                <div class="credential-item">
                    <span class="credential-label">Nom d'utilisateur :</span>
                    <span class="credential-value" id="username">{{ $m3uUsername }}</span>
                    <button class="copy-btn" onclick="copyToClipboard('username')">Copier</button>
                </div>

                <div class="credential-item">
                    <span class="credential-label">Mot de passe :</span>
                    <span class="credential-value" id="password">{{ $m3uPassword }}</span>
                    <button class="copy-btn" onclick="copyToClipboard('password')">Copier</button>
                </div>

                <div class="credential-item">
                    <span class="credential-label">URL du serveur :</span>
                    <span class="credential-value" id="server-url">{{ $m3uServerUrl }}</span>
                    <button class="copy-btn" onclick="copyToClipboard('server-url')">Copier</button>
                </div>

                <div class="credential-item">
                    <span class="credential-label">Lien M3U :</span>
                    <span class="credential-value" id="m3u-url">{{ $m3uUrl }}</span>
                    <button class="copy-btn" onclick="copyToClipboard('m3u-url')">Copier</button>
                </div>
            </div>

            @if($expiresAt)
            <div class="warning">
                <strong>⏰ Important :</strong> Votre abonnement expire le <span class="highlight">{{ $expiresAt->format('d/m/Y à H:i') }}</span>
            </div>
            @endif

            <div class="instructions">
                <h3>📱 Comment utiliser vos identifiants</h3>
                <ol>
                    <li><strong>Pour les applications IPTV :</strong> Utilisez le nom d'utilisateur et mot de passe ci-dessus</li>
                    <li><strong>Pour les lecteurs M3U :</strong> Utilisez le lien M3U fourni</li>
                    <li><strong>Serveur :</strong> {{ $m3uServerUrl }}</li>
                    <li><strong>Port :</strong> 8080</li>
                </ol>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $m3uUrl }}" class="btn" target="_blank">📺 Télécharger le fichier M3U</a>
                <a href="{{ route('tutorials.index') }}" class="btn btn-secondary">📚 Voir les tutoriels</a>
            </div>

            <div class="instructions">
                <h3>🛠️ Applications recommandées</h3>
                <ul>
                    <li><strong>Android :</strong> IPTV Smarters, TiviMate, Perfect Player</li>
                    <li><strong>iOS :</strong> IPTV Smarters, GSE Smart IPTV</li>
                    <li><strong>Windows :</strong> VLC Media Player, Kodi</li>
                    <li><strong>Smart TV :</strong> IPTV Smarters, Smart IPTV</li>
                </ul>
            </div>

            <div class="warning">
                <strong>🔒 Sécurité :</strong> Ne partagez jamais vos identifiants avec d'autres personnes. Ils sont personnels et uniques à votre compte.
            </div>

            <p>Si vous avez des questions ou besoin d'aide, n'hésitez pas à nous contacter.</p>

            <p>Cordialement,<br>
            <strong>L'équipe {{ config('app.name') }}</strong></p>
        </div>

        <div class="footer">
            <p>Cet email contient des informations sensibles. Veuillez le conserver en sécurité.</p>
            <p>Commande {{ $orderNumber }} - {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <script>
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            const text = element.textContent;
            
            navigator.clipboard.writeText(text).then(function() {
                const btn = event.target;
                const originalText = btn.textContent;
                btn.textContent = 'Copié !';
                btn.style.background = '#28a745';
                
                setTimeout(function() {
                    btn.textContent = originalText;
                    btn.style.background = '#28a745';
                }, 2000);
            }).catch(function(err) {
                console.error('Erreur lors de la copie: ', err);
                alert('Impossible de copier automatiquement. Veuillez sélectionner le texte manuellement.');
            });
        }
    </script>
</body>
</html>
