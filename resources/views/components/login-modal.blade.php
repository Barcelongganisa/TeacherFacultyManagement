@props(['show' => false])

<style>
    #loginButton > span.btn-text{
        color: white
    }

    /* Modal popup animation */
    .modern-login-modal {
        border-radius: 14px;
        border: none;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        animation: modalPop .35s ease;
    }

    @keyframes modalPop{
        from{
            transform: scale(.9);
            opacity:0;
        }
        to{
            transform: scale(1);
            opacity:1;
        }
    }

    /* Header */
    .modern-login-header{
        background:#2e7d32;
        color:white;
        text-align:center;
        padding:30px 20px;
    }

    .modern-login-header h5{
        font-weight:700;
        margin-top:8px;
    }

    .login-icon{
        font-size:32px;
    }

    /* Body */
    .modern-login-body{
        padding:30px;
    }

    /* Inputs */
    .modern-input{
        border-radius:8px;
        padding:10px 12px;
    }

    .modern-input:focus{
        border-color:#2e7d32;
        box-shadow:0 0 0 .15rem rgba(46,125,50,.15);
    }

    /* Login Button */
    .modern-login-btn{
        background:#2e7d32;
        border:none;
        font-weight:600;
        padding:10px;
        border-radius:8px;
        transition:.2s;
    }

    .modern-login-btn:hover{
        background:#256b2a;
        transform:translateY(-1px);
    }

    /* Footer */
    .modern-login-footer{
        text-align:center;
        padding:15px;
        font-size:14px;
    }

    .modern-login-footer a{
        color:#2e7d32;
        font-weight:600;
    }

    /* Password eye icon */
    .password-wrapper{
        position:relative;
    }

    .toggle-password{
        position:absolute;
        right:12px;
        top:50%;
        transform:translateY(-50%);
        cursor:pointer;
        color:#777;
    }
</style>

<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content modern-login-modal">

            <!-- Header -->
            <div class="modern-login-header">
                <i class="fas fa-graduation-cap login-icon"></i>
                <h5>CMS Login</h5>
                <p>Classroom Monitoring System</p>
            </div>

            <!-- Body -->
            <div class="modal-body modern-login-body">

                <div id="loginAlert" class="alert d-none"></div>

                <form id="loginForm" method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input 
                            type="email"
                            class="form-control modern-input"
                            name="email"
                            placeholder="Enter your email"
                            required>
                    </div>

                    <!-- Password -->
                    <div class="mb-3 password-group">
                        <label class="form-label">Password</label>

                        <div class="password-wrapper">
                            <input 
                                type="password"
                                class="form-control modern-input"
                                name="password"
                                id="password"
                                placeholder="Enter your password"
                                required>

                            <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                        </div>
                    </div>

                    <!-- Remember -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember">
                        <label class="form-check-label">
                            Remember me
                        </label>
                    </div>

                    <!-- Button -->
                    <button type="submit" class="btn modern-login-btn w-100" id="loginButton">
                        <span class="spinner-border spinner-border-sm d-none"></span>
                        <span class="btn-text">Sign In</span>
                    </button>
                </form>
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