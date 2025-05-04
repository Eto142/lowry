<!-- Bank Transfer Modal -->
<div class="modal fade withdrawal-modal" id="bankModal" tabindex="-1" aria-labelledby="bankModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-bank"></i> Bank Transfer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if(!auth()->user()->bank_account_linked)
                <div class="alert alert-warning">
                    <h5><i class="bi bi-exclamation-triangle"></i> Account Not Linked</h5>
                    <p class="mb-3">Please link your bank account to proceed with withdrawals.</p>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#linkBankModal">
                        Link Bank Account
                    </button>
                </div>
                @else
                <form id="bankWithdrawalForm" class="withdrawal-form" action="{{ route('withdrawals.process') }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="method" value="bank">
                    <input type="hidden" name="withdrawal_id">

                    <div class="mb-3">
                        <label class="form-label">Linked Account</label>
                        <div class="card p-3 bg-light">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ auth()->user()->bank_name }}</strong><br>
                                    ****{{ substr(auth()->user()->bank_account_number, -4) }}<br>
                                    {{ auth()->user()->bank_account_name }}
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#linkBankModal">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="bankAmount" class="form-label">Withdrawal Amount</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="10" required
                            placeholder="Enter amount ($)">
                        <div class="form-text">Minimum withdrawal: $10.00</div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Cash App Modal -->
<div class="modal fade withdrawal-modal" id="cashappModal" tabindex="-1" aria-labelledby="cashappModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-cash-coin"></i> Cash App</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if(!auth()->user()->cashapp_linked)
                <div class="alert alert-warning">
                    <h5><i class="bi bi-exclamation-triangle"></i> Account Not Linked</h5>
                    <p class="mb-3">Please link your Cash App account to proceed.</p>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#linkCashappModal">
                        Link Cash App
                    </button>
                </div>
                @else
                <form id="cashappWithdrawalForm" class="withdrawal-form" action="{{ route('withdrawals.process') }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="method" value="cashapp">
                    <input type="hidden" name="withdrawal_id">

                    <div class="mb-3">
                        <label class="form-label">Linked Cash App</label>
                        <div class="card p-3 bg-light">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ auth()->user()->cashapp_id }}</strong>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                                    data-bs-target="#linkCashappModal">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="cashappAmount" class="form-label">Withdrawal Amount</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="10" required
                            placeholder="Enter amount ($)">
                        <div class="form-text">Minimum withdrawal: $10.00</div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">Submit Request</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Crypto Modal -->
<div class="modal fade withdrawal-modal" id="cryptoModal" tabindex="-1" aria-labelledby="cryptoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-currency-bitcoin"></i> Crypto Wallet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if(!auth()->user()->crypto_wallet_linked)
                <div class="alert alert-warning">
                    <h5><i class="bi bi-exclamation-triangle"></i> Wallet Not Linked</h5>
                    <p class="mb-3">Please link your crypto wallet to proceed.</p>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#linkCryptoModal">
                        Link Wallet
                    </button>
                </div>
                @else
                <form id="cryptoWithdrawalForm" class="withdrawal-form" action="{{ route('withdrawals.process') }}"
                    method="POST">
                    @csrf
                    <input type="hidden" name="method" value="crypto">
                    <input type="hidden" name="withdrawal_id">

                    <div class="mb-3">
                        <label class="form-label">Linked Wallet</label>
                        <div class="card p-3 bg-light">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ auth()->user()->crypto_network }} Network</strong><br>
                                    {{ substr(auth()->user()->crypto_wallet_address, 0, 12) }}...{{
                                    substr(auth()->user()->crypto_wallet_address, -10) }}
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                    data-bs-target="#linkCryptoModal">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="cryptoAmount" class="form-label">Withdrawal Amount</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="10" required
                            placeholder="Enter amount ($)">
                        <div class="form-text">Minimum withdrawal: $10.00</div>
                    </div>

                    <div class="mb-3">
                        <label for="cryptoType" class="form-label">Currency</label>
                        <select name="crypto_type" class="form-select" required>
                            <option value="bitcoin">Bitcoin (BTC)</option>
                            <option value="ethereum">Ethereum (ETH)</option>
                            <option value="usdt">USDT</option>
                            <option value="usdc">USDC</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning">Submit Request</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>