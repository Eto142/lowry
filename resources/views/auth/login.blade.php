@include("auth.header");
<!-- Login Form Modal (Hidden Initially) -->
<div id="auth-modal" class="modal-overlay" style="display: none;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow">
          <div class="card-body p-4">
            <div class="row">
              <div class="col-md-8 border-end">
                <!-- Lowry Logo -->
                <div class="login-logo text-start mb-4">
                  <svg xmlns="http://www.w3.org/2000/svg" width="200" height="80" viewBox="0 0 200 80">
                    <text x="10" y="50" font-family="Arial, sans-serif" font-size="40" font-weight="bold" fill="#333">LOWRY</text>
                  </svg>
                </div>
                
                <form class="login-form">
                  <div class="mb-3">
                    <label for="email">Email address:</label>
                    <div class="input-group">
                      <input type="email" class="form-control py-1" id="email" placeholder="Email address:">
                      <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    </div>
                  </div>
                  
                  <div class="mb-3">
                    <label for="password">Password</label>
                    <div class="input-group">
                      <input type="password" class="form-control py-1" id="password" placeholder="Password">
                      <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    </div>
                  </div>
                  
                 <small><a href="#" class="password-link">Request a new password</a></small> <br>
                  
                  <button type="submit" class="btn btn-dark btn-signin rounded-bit bg-white py-1 text-dark my-2">Sign In</button>
                  
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="rememberPassword" checked>
                    <label class="form-check-label" for="rememberPassword">
                      <small>Please remember my password for this site</small>
                    </label>
                  </div>
                </form>
              </div>
              
              <div class="col-md-4 d-flex flex-column justify-content-center align-items-center">
                <div class="new-customer">
                  <p class="text-center mb-3">New customer?</p>
                 <a href="{{ url("/register") }}" class="text-decoration-none">
                  <button class="btn btn-outline-dark">Join now</button>
                  </a> 
                </div>
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