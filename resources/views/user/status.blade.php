@php
$kyc = Auth::user()->kycVerification;
@endphp

@if($kyc && $kyc->status === 'pending')
<div class="alert alert-info">
    <h5><i class="bi bi-hourglass-split"></i> KYC Under Review</h5>
    <p>Your KYC submission is currently being reviewed. Please check back later.</p>
    <p>Submitted on: {{ $kyc->created_at->format('M d, Y H:i') }}</p>
</div>
@elseif($kyc && $kyc->status === 'approved')
<div class="alert alert-success">
    <h5><i class="bi bi-check-circle"></i> KYC Approved</h5>
    <p>Your KYC verification has been approved on {{ $kyc->updated_at->format('M d, Y') }}.</p>
</div>
@elseif($kyc && $kyc->status === 'rejected')
<div class="alert alert-danger">
    <h5><i class="bi bi-exclamation-triangle"></i> KYC Rejected</h5>
    <p>Your KYC submission was rejected. Reason: {{ $kyc->rejection_reason }}</p>
    <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#kycModal">
        Resubmit Documents
    </button>
</div>
@else
<div class="alert alert-warning">
    <h5><i class="bi bi-exclamation-circle"></i> KYC Not Submitted</h5>
    <p>You need to complete KYC verification to access all features.</p>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kycModal">
        Start KYC Verification
    </button>
</div>
@endif