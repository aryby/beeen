<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette commande ?</p>
                <p class="text-muted">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de remboursement -->
<div class="modal fade" id="refundModal" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="refundModalLabel">Rembourser la commande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="refundForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="refund_amount" class="form-label">Montant du remboursement</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="refund_amount" name="refund_amount" 
                                   step="0.01" min="0.01" placeholder="0.00">
                            <span class="input-group-text">€</span>
                        </div>
                        <div class="form-text">Laisser vide pour rembourser le montant total</div>
                    </div>
                    <div class="mb-3">
                        <label for="refund_reason" class="form-label">Raison du remboursement</label>
                        <textarea class="form-control" id="refund_reason" name="refund_reason" 
                                  rows="3" placeholder="Expliquez la raison du remboursement..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">Rembourser</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmation d'action -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirmer l'action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="confirmMessage">Êtes-vous sûr de vouloir effectuer cette action ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="confirmForm" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary" id="confirmButton">Confirmer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Fonction pour confirmer une action
function confirmAction(url, message, buttonText = 'Confirmer') {
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmButton').textContent = buttonText;
    document.getElementById('confirmForm').action = url;
    
    const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
    modal.show();
}

// Fonction pour confirmer la suppression
function confirmDelete(orderId, orderNumber) {
    document.getElementById('deleteForm').action = `/admin/orders/${orderId}`;
    document.querySelector('#deleteModal .modal-body p').textContent = 
        `Êtes-vous sûr de vouloir supprimer la commande ${orderNumber} ?`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Fonction pour afficher le modal de remboursement
function showRefundModal(orderId, orderNumber, maxAmount) {
    document.getElementById('refundForm').action = `/admin/orders/${orderId}/refund`;
    document.getElementById('refund_amount').max = maxAmount;
    document.getElementById('refund_amount').placeholder = maxAmount.toFixed(2);
    
    const modal = new bootstrap.Modal(document.getElementById('refundModal'));
    modal.show();
}
</script>
