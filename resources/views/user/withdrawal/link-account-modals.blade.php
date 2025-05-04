<!-- Bank Account Linking Modal -->
<div class="modal fade" id="linkBankModal" tabindex="-1" aria-labelledby="linkBankModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-bank"></i> Link Bank Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="account-linking-form" action="{{ route('withdrawals.link') }}" method="POST">
                @csrf
                <input type="hidden" name="method" value="bank">

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" required placeholder="e.g. Chase Bank">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Account Number</label>
                        <input type="text" name="account_number" class="form-control" required
                            placeholder="Enter account number">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Account Holder Name</label>
                        <input type="text" name="account_name" class="form-control" required
                            placeholder="Name as on account">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cash App Linking Modal -->
<div class="modal fade" id="linkCashappModal" tabindex="-1" aria-labelledby="linkCashappModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-cash-coin"></i> Link Cash App</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="account-linking-form" action="{{ route('withdrawals.link') }}" method="POST">
                @csrf
                <input type="hidden" name="method" value="cashapp">

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Cash App ID</label>
                        <input type="text" name="cashapp_id" class="form-control" required
                            placeholder="Enter your Cash App ID">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Account</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Crypto Wallet Linking Modal -->
<div class="modal fade" id="linkCryptoModal" tabindex="-1" aria-labelledby="linkCryptoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-wallet2"></i> Link Crypto Wallet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="account-linking-form" action="{{ route('withdrawals.link') }}" method="POST">
                @csrf
                <input type="hidden" name="method" value="crypto">

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Wallet Address</label>
                        <input type="text" name="wallet_address" class="form-control" required
                            placeholder="Enter wallet address">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Network</label>
                        <select name="network" class="form-select" required>
                            <option value="ERC20">ERC20 (Ethereum)</option>
                            <option value="BEP20">BEP20 (Binance Smart Chain)</option>
                            <option value="TRC20">TRC20 (Tron)</option>
                            <option value="Bitcoin">Bitcoin Network</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Save Wallet</button>
                </div>
            </form>
        </div>
    </div>
</div>