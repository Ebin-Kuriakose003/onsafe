

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
  .form-control:user-invalid {
    border-color: #dc3545 !important;
  }
  .form-control:user-valid {
    border-color: #198754 !important;
  }
</style>

<section class="auth-page">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-5 col-lg-6 col-md-8">

        <div class="registration-card">
          <div class="text-center mb-4">
           <a href="../../index.php"><img src="../images/favicon.png" alt="MissionBDU" class="img-fluid" style="max-height: 100px;"></a> 
            <h3 class="fw-bold">System Login</h3>
            <p class="text-muted">Secure access to MissionBDU</p>
          </div>

          <form id="loginForm" method="POST">

            <!-- Email -->
            <div class="mb-3">
              <label class="form-label">Email (Login ID)</label>
              <input
                type="email"
                name="email"
                autocomplete="off"
                placeholder="Enter Your Registered email Here"
                class="form-control"
                required
                title="Enter a valid Gmail address">
            </div>

            <!-- Password -->
            <div class="mb-3 position-relative">
              <label class="form-label">Password</label>
              <input
                type="password"
                name="password"
                id="login_password"
                placeholder="Enter Password Here"
                autocomplete="off"
                class="form-control"
                required>
              <span class="password-toggle" onclick="togglePassword('login_password')">👁</span>
            </div>

            <div class="d-grid mt-4">
              <button type="submit" class="btn btn-primary btn-lg">
                Login
              </button>
            </div>

            <p class="text-center mt-3 small">
              <a href="forgot_password.php">Forgot password?</a>
            </p>

          </form>
        </div>

      </div>
    </div>
  </div>
</section>

<script>
const form = document.getElementById("loginForm");

form.addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(form);

  fetch('../../app/auth/login.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())   // ✅ EXPECT JSON
  .then(data => {

    if (data.status === 'success') {
      Swal.fire({
        icon: 'success',
        title: 'Login Successful',
        text: 'Redirecting...'
      }).then(() => {
        window.location.href = data.redirect;
      });

    } else {
      Swal.fire('Login Failed', data.msg || 'Invalid credentials', 'error');
    }

  })
  .catch(() => {
    Swal.fire('Server Error', 'Please try again later', 'error');
  });
});

function togglePassword(id) {
  const input = document.getElementById(id);
  input.type = input.type === "password" ? "text" : "password";
}
</script>
