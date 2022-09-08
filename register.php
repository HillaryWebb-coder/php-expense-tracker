<?php
include "inc/header.php";
?>

<div class="row align-items-center fullHeight ">
    <div class="mx-auto col-lg-5 col-md-6 col">
        <div class="card border-0 p-4 gap-4">
            <h2 class="fw-bold fs-2 text-primary text-center"> Sign Up</h2>

            <!-- Display Non field errors -->
            <div class="alert alert-danger d-none capitalize" id="non-field"></div>

            <form method="POST" id="register-form">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="username" />
                    <p class="invalid-feedback" id="username_err"></p>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" name="email" class="form-control" id="email" />
                    <p class="invalid-feedback" id="email_err"></p>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" />
                    <p class="invalid-feedback" id="password_err"></p>
                </div>
                <div class="d-grid">
                    <input type="Submit" name="register" value="Register" class="btn btn-primary" />
                </div>
            </form>
            <div class="card-footer text-muted fst-italic border-0">
                <p>Already have an account? <a href="index.php">Login</a></p>
            </div>
        </div>
    </div>
</div>
<?php
include "inc/footer.php";
