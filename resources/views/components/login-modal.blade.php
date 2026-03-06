@props(['show' => false])

<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">
                    <i class="fas fa-graduation-cap me-2"></i>Login to CMS
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="loginAlert" class="alert d-none" role="alert"></div>
                
                <form id="loginForm" method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input 
                            type="email" 
                            class="form-control" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            placeholder="Enter your email"
                            required 
                            autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password" 
                            name="password" 
                            placeholder="Enter your password"
                            required>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-modal-primary" id="loginButton">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text">Sign In</span>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <div class="switch-form">
                    Don't have an account? 
                    <a href="#" onclick="openRegisterModal(); return false;">Register here</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Make sure these functions are globally available
window.openLoginModal = function() {
    var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
    loginModal.show();
}

// Handle login form submission
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const loginButton = document.getElementById('loginButton');
            const spinner = loginButton.querySelector('.spinner-border');
            const buttonText = loginButton.querySelector('.btn-text');
            const alertDiv = document.getElementById('loginAlert');
            
            // Show loading state
            loginButton.disabled = true;
            spinner.classList.remove('d-none');
            buttonText.textContent = ' Logging in...';
            alertDiv.classList.add('d-none');
            
            // Get the correct action URL
            const actionUrl = this.getAttribute('action');
            
            // Send AJAX request
            fetch(actionUrl, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.redirect) {
                    // Successful login - redirect to dashboard
                    window.location.href = data.redirect;
                } else if (data.errors) {
                    // Validation errors
                    let errorHtml = '<ul class="mb-0">';
                    for (let field in data.errors) {
                        data.errors[field].forEach(error => {
                            errorHtml += `<li>${error}</li>`;
                        });
                    }
                    errorHtml += '</ul>';
                    
                    alertDiv.className = 'alert alert-danger';
                    alertDiv.innerHTML = errorHtml;
                    alertDiv.classList.remove('d-none');
                } else if (data.error) {
                    // General error
                    alertDiv.className = 'alert alert-danger';
                    alertDiv.textContent = data.error;
                    alertDiv.classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alertDiv.className = 'alert alert-danger';
                alertDiv.textContent = 'An error occurred. Please try again.';
                alertDiv.classList.remove('d-none');
            })
            .finally(() => {
                // Reset button state
                loginButton.disabled = false;
                spinner.classList.add('d-none');
                buttonText.textContent = 'Sign In';
            });
        });
    }
});
</script>
@endpush