@props(['show' => false])

<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel">
                    <i class="fas fa-user-plus me-2"></i>Register for CMS
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="registerAlert" class="alert d-none" role="alert"></div>
                
                <form id="registerForm" method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}" 
                            placeholder="Enter your full name"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input 
                            type="email" 
                            class="form-control" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            placeholder="Enter your email"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password" 
                            name="password" 
                            placeholder="Choose a password (min. 8 characters)"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            placeholder="Confirm your password"
                            required>
                    </div>

                    <button type="submit" class="btn btn-modal-primary" id="registerButton">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="btn-text">Register</span>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <div class="switch-form">
                    Already have an account? 
                    <a href="#" onclick="openLoginModal(); return false;">Login here</a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
window.openRegisterModal = function() {
    var registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
    registerModal.show();
}

// Handle register form submission
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const registerButton = document.getElementById('registerButton');
            const spinner = registerButton.querySelector('.spinner-border');
            const buttonText = registerButton.querySelector('.btn-text');
            const alertDiv = document.getElementById('registerAlert');
            
            // Show loading state
            registerButton.disabled = true;
            spinner.classList.remove('d-none');
            buttonText.textContent = ' Registering...';
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
                    // Successful registration - redirect to dashboard
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
                registerButton.disabled = false;
                spinner.classList.add('d-none');
                buttonText.textContent = 'Register';
            });
        });
    }
});
</script>
@endpush