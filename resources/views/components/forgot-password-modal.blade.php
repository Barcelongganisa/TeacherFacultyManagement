<style>
    #forgotButton{
        color: white;
    }
</style>
<div class="modal fade" id="forgotPasswordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content modern-login-modal">

            <!-- Header -->
            <div class="modern-login-header">
                <i class="fas fa-key login-icon"></i>
                <h5>Reset Password</h5>
                <p>We'll email you a reset link</p>
            </div>

            <!-- Body -->
            <div class="modal-body modern-login-body">

                <div id="forgotAlert" class="alert d-none"></div>

                <form id="forgotForm" method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Email Address</label>
                        <input
                            type="email"
                            class="form-control modern-input"
                            name="email"
                            placeholder="Enter your email"
                            required>
                    </div>

                    <button type="submit" class="btn modern-login-btn w-100" id="forgotButton">
                        <span class="spinner-border spinner-border-sm d-none"></span>
                        <span class="btn-text">Send Reset Link</span>
                    </button>

                    <div class="text-center mt-3">
                        <a href="#" class="forgot-password-link"
                           onclick="
                               bootstrap.Modal.getInstance(document.getElementById('forgotPasswordModal')).hide();
                               setTimeout(function(){
                                   new bootstrap.Modal(document.getElementById('loginModal')).show();
                               }, 300);
                               return false;
                           ">
                            <i class="fas fa-arrow-left me-1"></i> Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const forgotForm = document.getElementById('forgotForm');
    if (!forgotForm) return;

    forgotForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const forgotButton = document.getElementById('forgotButton');
        const spinner      = forgotButton.querySelector('.spinner-border');
        const buttonText   = forgotButton.querySelector('.btn-text');
        const alertDiv     = document.getElementById('forgotAlert');

        forgotButton.disabled = true;
        spinner.classList.remove('d-none');
        buttonText.textContent = ' Sending...';
        alertDiv.classList.add('d-none');

        fetch(this.getAttribute('action'), {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept':           'application/json',
                'X-CSRF-TOKEN':     document.querySelector('input[name="_token"]').value
            },
            body: new FormData(this)
        })
        .then(r => r.json())
        .then(data => {
            if (data.status) {
                alertDiv.className   = 'alert alert-success';
                alertDiv.textContent = data.status;
                alertDiv.classList.remove('d-none');
                forgotForm.reset();
            } else if (data.errors) {
                let html = '<ul class="mb-0">';
                for (let f in data.errors) {
                    data.errors[f].forEach(err => { html += `<li>${err}</li>`; });
                }
                html += '</ul>';
                alertDiv.className = 'alert alert-danger';
                alertDiv.innerHTML = html;
                alertDiv.classList.remove('d-none');
            }
        })
        .catch(() => {
            alertDiv.className   = 'alert alert-danger';
            alertDiv.textContent = 'An error occurred. Please try again.';
            alertDiv.classList.remove('d-none');
        })
        .finally(() => {
            forgotButton.disabled  = false;
            spinner.classList.add('d-none');
            buttonText.textContent = 'Send Reset Link';
        });
    });
});
</script>