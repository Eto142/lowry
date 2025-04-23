<!-- Link Bank Account Modal -->
<div class="modal fade" id="linkBankModal" tabindex="-1" aria-labelledby="linkBankModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="linkBankModalLabel"><i class="bi bi-bank"></i> Link Bank Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <p>Please provide your bank account details for verification.</p>
                </div>
                <form class="account-linking-form" action="{{ route('withdrawals.link-account') }}" method="POST">
                    @csrf
                    <input type="hidden" name="method" value="bank">
                    <div class="mb-3">
                        <label class="form-label">Bank Name</label>
                        <input type="text" class="form-control" name="bank_name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Number</label>
                        <input type="text" class="form-control" name="account_number" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Account Name</label>
                        <input type="text" class="form-control" name="account_name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Link Cash App Modal -->
<div class="modal fade" id="linkCashappModal" tabindex="-1" aria-labelledby="linkCashappModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="linkCashappModalLabel"><i class="bi bi-cash-coin"></i> Link Cash App</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <p>Please provide your Cash App details for verification.</p>
                </div>
                <form class="account-linking-form" action="{{ route('withdrawals.link-account') }}" method="POST">
                    @csrf
                    <input type="hidden" name="method" value="cashapp">
                    <div class="mb-3">
                        <label class="form-label">Cash App ID</label>
                        <input type="text" class="form-control" name="cashapp_id" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Link Crypto Wallet Modal -->
<div class="modal fade" id="linkCryptoModal" tabindex="-1" aria-labelledby="linkCryptoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="linkCryptoModalLabel"><i class="bi bi-currency-bitcoin"></i> Link Crypto
                    Wallet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <p>Please provide your cryptocurrency wallet details for verification.</p>
                </div>
                <form class="account-linking-form" action="{{ route('withdrawals.link-account') }}" method="POST">
                    @csrf
                    <input type="hidden" name="method" value="crypto">
                    <div class="mb-3">
                        <label class="form-label">Wallet Address</label>
                        <input type="text" class="form-control" name="wallet_address" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Network</label>
                        <select class="form-select" name="network" required>
                            <option value="BTC">Bitcoin (BTC)</option>
                            <option value="ETH">Ethereum (ETH)</option>
                            <option value="TRX">Tron (TRX)</option>
                            <option value="BSC">Binance Smart Chain (BSC)</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>