<?php
require_once("config/db_setup.php");
include "inc/header.php";
?>

<div class="row align-items-center fullHeight ">
    <div class="mx-auto col-lg-5 col-md-6 col">
        <div class="card border-0 p-4 gap-4">
            <h2 class="fw-bold fs-2 text-primary text-center">Welcome Back</h2>

            <!-- Display non field errors -->
            <div class="alert alert-danger d-none capitalize" id="non-field"></div>
            <form method="POST" id="login-form">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="username" />
                    <p class="invalid-feedback" id="username_err"></p>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" />
                    <p class="form-invalid" id="password_err"></p>
                </div>
                <div class="d-grid">
                    <input type="Submit" name="login" value="Login" class="btn btn-primary" />
                </div>
            </form>
            <div class="card-footer text-muted fst-italic border-0">
                <p>Don't have an account yet? <a href="register.php" />Register</a></p>
            </div>
        </div>
    </div>
</div>
<?php
include "inc/footer.php";
