<?php
include 'inc/header.php';

if (!isset($_SESSION["user"])) {
    header("Location: index.php");
}
?>
<div class="row flex-col">
    <div class="col-lg-6 col-md-8 col mx-auto">
        <div class="card mb-3 rounded-sm border-0 mt-5 p-3">
            <h1 class="fw-bold fs-1 text-center mb-5">my<span class="text-primary">Tranzacts</span></h1>
            <div class="card-body vstack gap-4">
                <div class="hstack justify-content-between">
                    <h2 class="fw-bold fs-2 card-title">Welcome <span class="text-primary text-capitalize"><?= $_SESSION["user"]["username"] ?></span></h2>
                    <button class="rounded-pill btn btn-primary" id="new-trans-btn">New Transaction</button>
                </div>

                <!-- Form to add new transaction to the database -->
                <form method="post" style="display:none" id="transaction-form">
                    <!-- Display non field errors -->
                    <div class="alert alert-danger d-none capitalize" id="non-field"></div>

                    <!-- Display success message -->
                    <div class="alert alert-success d-none capitalize alert-dismissible fade show" id="success">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Transaction Type</label>
                        <select name="type" id="type" class="form-select">
                            <option value="income">Income</option>
                            <option value="expenses">Expenditure</option>
                        </select>
                        <p class="invalid-feedback" id="type_err"></p>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" name="amount" id="amount" class="form-control">
                        <p class="invalid-feedback" id="amount_err"></p>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" name="desc" id="description" class="form-control">
                        <p class="invalid-feedback" id="description_err"></p>
                    </div>
                    <div class="d-grid mb-3">
                        <input type="submit" value="Submit" name="submit" class="btn btn-primary col-md-7">
                    </div>
                </form>
                <div class="d-grid">
                    <a class="btn btn-outline-danger" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
        <div class="card mb-3 rounded-sm bg-warning shadow-md p-3 border-0">
            <div class="card-body row">
                <div class="col-md-4 col-6">
                    <h6 class="fs-6 fw-light text-uppercase">Expenditure</h6>
                    <p class="fw-bold fs-3" id="totalExpense"></p>
                </div>
                <div class="col-md-4 col-6">
                    <h6 class="fs-6 fw-light text-uppercase">Income</h6>
                    <p class="fw-bold fs-3" id="totalIncome"></p>
                </div>
                <div class="col-md-4 col">
                    <h6 class="fs-6 fw-light text-uppercase">Net Income</h6>
                    <p class="fw-bold fs-3" id="netIncome"></p>
                </div>
            </div>
        </div>
        <div id="transactions">
        </div>
    </div>
</div>
<?php
include "inc/footer.php";
