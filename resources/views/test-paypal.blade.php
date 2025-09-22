<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test PayPal Configuration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Test Configuration PayPal</h4>
                    </div>
                    <div class="card-body">
                        <div id="test-results">
                            <div class="text-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Test en cours...</span>
                                </div>
                                <p class="mt-2">Test de la configuration PayPal...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        fetch('/test-paypal')
            .then(response => response.json())
            .then(data => {
                const resultsDiv = document.getElementById('test-results');
                
                if (data.success) {
                    resultsDiv.innerHTML = `
                        <div class="alert alert-success">
                            <h5><i class="bi bi-check-circle me-2"></i>Configuration PayPal OK</h5>
                            <p>La configuration PayPal fonctionne correctement.</p>
                        </div>
                        <div class="mt-3">
                            <h6>Configuration actuelle :</h6>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Client ID :</span>
                                    <code>${data.config.client_id ? data.config.client_id.substring(0, 10) + '...' : 'Non configuré'}</code>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Client Secret :</span>
                                    <code>${data.config.client_secret ? 'Configuré' : 'Non configuré'}</code>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Mode Sandbox :</span>
                                    <span class="badge ${data.config.sandbox ? 'bg-warning' : 'bg-success'}">${data.config.sandbox ? 'Oui' : 'Non'}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Base URL :</span>
                                    <code>${data.config.base_url}</code>
                                </li>
                            </ul>
                        </div>
                        <div class="mt-3">
                            <h6>Résultat du test de paiement :</h6>
                            <pre class="bg-light p-3 rounded">${JSON.stringify(data.result, null, 2)}</pre>
                        </div>
                    `;
                } else {
                    resultsDiv.innerHTML = `
                        <div class="alert alert-danger">
                            <h5><i class="bi bi-exclamation-triangle me-2"></i>Erreur de configuration PayPal</h5>
                            <p><strong>Erreur :</strong> ${data.error}</p>
                        </div>
                        <div class="mt-3">
                            <h6>Configuration actuelle :</h6>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Client ID :</span>
                                    <code>${data.config.client_id ? data.config.client_id.substring(0, 10) + '...' : 'Non configuré'}</code>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Client Secret :</span>
                                    <code>${data.config.client_secret ? 'Configuré' : 'Non configuré'}</code>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Mode Sandbox :</span>
                                    <span class="badge ${data.config.sandbox ? 'bg-warning' : 'bg-success'}">${data.config.sandbox ? 'Oui' : 'Non'}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="mt-3">
                            <div class="alert alert-info">
                                <h6>Solutions possibles :</h6>
                                <ul class="mb-0">
                                    <li>Vérifiez que les clés PayPal sont correctement configurées dans les paramètres</li>
                                    <li>Assurez-vous que le mode sandbox est activé pour les tests</li>
                                    <li>Vérifiez la connectivité internet</li>
                                    <li>Consultez les logs Laravel pour plus de détails</li>
                                </ul>
                            </div>
                        </div>
                    `;
                }
            })
            .catch(error => {
                document.getElementById('test-results').innerHTML = `
                    <div class="alert alert-danger">
                        <h5><i class="bi bi-exclamation-triangle me-2"></i>Erreur de test</h5>
                        <p>Impossible de tester la configuration PayPal : ${error.message}</p>
                    </div>
                `;
            });
    </script>
</body>
</html>
