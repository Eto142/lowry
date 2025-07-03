@include("auth.header")

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logo.jpeg') }}" alt="Logo" width="150px">
                    </div>

                    <h4 class="text-center mb-4">Email Verification</h4>

                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    <p class="text-center mb-4">We've sent a verification code to your email address. Please enter the
                        code below to verify your email.</p>

                    <form method="POST" action="{{ route('verify.code') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="verification_code" class="form-label">Verification Code</label>
                            <input type="number" class="form-control @error('verification_code') is-invalid @enderror"
                                id="verification_code" name="verification_code" required>
                            @error('verification_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Verify Email</button>
                            {{-- <a href="{{ route('skip.code') }}" class="btn btn-outline-secondary">Skip
                                Verification</a> --}}
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <p>Didn't receive a code? <a href="{{ route('resend.verification') }}">Resend Verification
                                Code</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include("auth.footer")

<style>
    .card {
        max-width: 600px;
        margin: 0 auto;
    }

    .btn-primary {
        background-color: #333;
        border-color: #333;
    }
</style>