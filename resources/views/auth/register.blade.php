@include("auth.header");

    <div id="auth-modal" class="modal-overlay" style="display: none;">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-8">
            <div class="card shadow">
              <div class="card-body p-4">
                <div class="row">
                  <div class="col-md-8 border-end">
                    <p class="mb-4">Please fill in the following details</p>
                    
                    <form action="{{route('register.submit')}}" method="POST">
                      @csrf
                      <div class="mb-3">
                        <input type="text" name="first_name" class="form-control name-input border-red py-1" placeholder="First Name">
                      </div>
                      
                      <div class="mb-3">
                        <input type="text" name="last_name" class="form-control name-input border-red py-1" placeholder="Last Name">
                      </div>
                      
                      <div class="mb-3">
                        <div class="input-group">
                          <input type="email"  name="email"class="form-control border-red py-1 no-border-right" placeholder="E-mail">
                          <span class="input-group-text border-red">@</span>
                        </div>
                        <small class="text-muted">for sending order confirmation</small>
                      </div>
                      
                      <div class="mb-3">
                        <div class="input-group">
                          <input type="tel" name="phone_number" class="form-control border-red no-border-right py-1" placeholder="Home Phone">
                          <span class="input-group-text border-red"><i class="bi bi-telephone"></i></span>
                        </div>
                        <small class="text-muted">for updates in case of schedule changes</small>
                      </div>

                      <div class="mb-3">
                        <div class="input-group">
                          <input type="text" name="country" class="form-control border-red no-border-right py-1" placeholder="Enter Country">
                          <span class="input-group-text border-red"><i class="bi bi-country"></i></span>
                        </div>

                      </div>

                      <div class="mb-3">
                        <div class="input-group">
                          <input type="password" name="password" class="form-control border-red no-border-right py-1" placeholder="password">
                          <span class="input-group-text border-red"><i class="bi bi-password"></i></span>
                        </div>
                        <small class="text-muted">password</small>
                      </div>

                      <div class="mb-3">
                        <div class="input-group">
                          <input type="password" name="confirm_password" class="form-control border-red no-border-right py-1" placeholder="confirm password">
                          <span class="input-group-text border-red"><i class="bi bi-password"></i></span>
                        </div>
                        <small class="text-muted"> confirm password</small>
                      </div>
                      
                      {{-- <div class="mb-4">
                        <div class="input-group">
                          <input type="date"  name="dob"class="form-control py-1" placeholder="Birthday">
                          <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                        </div>
                      </div> --}}
                      
                      <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary py-1">Back</button>
                        <div>
                          <button type="button" class="btn btn-outline-secondary me-2 py-1">Skip</button>
                          <button type="submit" class="btn btn-dark py-1 rounded-bit">Continue</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  
                  <div class="col-md-4 d-flex flex-column justify-content-center align-items-center">
                    <p class="text-center">Returning client?</p>
                    <a href="{{ url('/login') }}" class="text-decoration-none">
                      <button class="btn btn-outline-dark py-1">Sign in now</button>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Spacer to push footer down -->
    <div class="flex-grow-1"></div>
  </div>

  @include("auth.footer");

  