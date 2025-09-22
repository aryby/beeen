// Système de notifications global
window.NotificationSystem = {
    // Afficher un toast
    showToast: function(message, type = 'success', duration = 5000) {
        // Créer le toast
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        // Icône selon le type
        let icon = 'bi-check-circle';
        switch(type) {
            case 'danger':
            case 'error':
                icon = 'bi-exclamation-triangle';
                break;
            case 'warning':
                icon = 'bi-exclamation-triangle';
                break;
            case 'info':
                icon = 'bi-info-circle';
                break;
            default:
                icon = 'bi-check-circle';
        }
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi ${icon} me-2"></i>${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        
        // Ajouter au DOM
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }
        
        toastContainer.appendChild(toast);
        
        // Initialiser et afficher le toast
        const bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: duration
        });
        bsToast.show();
        
        // Nettoyer après fermeture
        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    },
    
    // Afficher une alerte
    showAlert: function(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.setAttribute('role', 'alert');
        
        let icon = 'bi-check-circle';
        switch(type) {
            case 'danger':
                icon = 'bi-exclamation-triangle';
                break;
            case 'warning':
                icon = 'bi-exclamation-triangle';
                break;
            case 'info':
                icon = 'bi-info-circle';
                break;
        }
        
        alertDiv.innerHTML = `
            <i class="bi ${icon} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insérer en haut de la page
        const main = document.querySelector('main') || document.body;
        main.insertBefore(alertDiv, main.firstChild);
        
        // Auto-supprimer après 10 secondes
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 10000);
    },
    
    // Gérer les erreurs AJAX
    handleAjaxError: function(xhr, status, error) {
        let message = 'Une erreur est survenue.';
        
        if (xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
        } else if (xhr.responseJSON && xhr.responseJSON.error) {
            message = xhr.responseJSON.error;
        } else if (xhr.status === 422) {
            message = 'Veuillez vérifier les informations saisies.';
        } else if (xhr.status === 500) {
            message = 'Erreur serveur. Veuillez réessayer plus tard.';
        } else if (xhr.status === 0) {
            message = 'Problème de connexion. Vérifiez votre connexion internet.';
        }
        
        this.showToast(message, 'danger');
    },
    
    // Gérer les succès AJAX
    handleAjaxSuccess: function(response) {
        if (response.message) {
            this.showToast(response.message, 'success');
        }
    }
};

// Fonctions globales pour compatibilité
window.showToast = window.NotificationSystem.showToast.bind(window.NotificationSystem);
window.showAlert = window.NotificationSystem.showAlert.bind(window.NotificationSystem);

// Gestionnaire global des erreurs AJAX
$(document).ajaxError(function(event, xhr, settings) {
    // Ne pas afficher d'erreur pour les requêtes silencieuses
    if (settings.silent) return;
    
    window.NotificationSystem.handleAjaxError(xhr);
});

// Gestionnaire global des succès AJAX
$(document).ajaxSuccess(function(event, xhr, settings) {
    // Ne pas afficher de succès pour les requêtes silencieuses
    if (settings.silent) return;
    
    try {
        const response = JSON.parse(xhr.responseText);
        if (response.success && response.message) {
            window.NotificationSystem.showToast(response.message, 'success');
        }
    } catch (e) {
        // Ignorer les erreurs de parsing JSON
    }
});

// Auto-dismiss des alertes de session après 5 secondes
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.parentNode) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });
});
