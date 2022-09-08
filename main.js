$("document").ready(() => {
  getTransactions();
  function responseHandler(response) {
    console.log(response);
    response = JSON.parse(response);
    if (response["errors"]) {
      errors = response["errors"];
      for (error in errors) {
        if (error === "non-field") {
          $(`#${error}`).removeClass("d-none");
          $(`#${error}`).text(errors[error]);
        } else {
          $(`#${error}`).addClass("is-invalid");
          $(`#${error}_err`).text(errors[error]);
        }
      }
    } else if (response["success"] && response["type"]) {
      $(`#success`).removeClass("d-none");
      $(`#success`).text(response["success"]);
    } else {
      window.location.assign("dashboard.php");
    }
    getTransactions();
  }

  /*##########################################
  FORM AJAX ACTION TO REGISTER USERS
  ###########################################*/
  $("#register-form").on("submit", (e) => {
    e.preventDefault();
    const fields = ["username", "email", "password"];

    const data = {};

    fields.forEach((field) => {
      if (field === "non-field") {
        $(`#${field}`).addClass("d-none");
        $(`#${field}`).text("");
      } else {
        $(`#${field}`).removeClass("is-invalid");
        $(`#${field}_err`).text("");
        data[field] = $(`#${field}`).val();
      }
    });

    const userData = {
      ...data,
      action: "register",
    };

    $.ajax({
      method: "POST",
      url: "functions.php",
      data: userData,
      success: responseHandler,
    });
  });

  /*##########################################
  FORM AJAX ACTION TO LOGIN USERS
  ###########################################*/
  $("#login-form").on("submit", (e) => {
    e.preventDefault();
    const fields = ["username", "password"];

    const data = {};

    fields.forEach((field) => {
      if (field === "non-field") {
        $(`#${field}`).addClass("d-none");
        $(`#${field}`).text("");
      } else {
        $(`#${field}`).removeClass("is-invalid");
        $(`#${field}_err`).text("");
        data[field] = $(`#${field}`).val();
      }
    });

    const userData = {
      ...data,
      action: "login",
    };

    $.ajax({
      method: "POST",
      url: "functions.php",
      data: userData,
      success: responseHandler,
    });
  });

  /*##########################################
  FORM AJAX ACTION TO CREATE NEW TRANSACTION
  ###########################################*/
  $("#transaction-form").on("submit", (e) => {
    e.preventDefault();
    const fields = ["type", "amount", "description"];

    // Clear non-field errors
    $("#non-field").addClass("d-none");
    $("#non-field").text("");

    let data = {};

    fields.forEach((field) => {
      $(`#${field}`).removeClass("is-invalid");
      $(`#${field}_err`).text("");
      data[field] = $(`#${field}`).val();
    });

    data = {
      ...data,
      action: "transaction",
    };

    $.ajax({
      method: "POST",
      url: "functions.php",
      data: data,
      success: responseHandler,
    });
  });

  /*####################################
  TOGGLE TRANSACTION FORM
  #####################################*/
  $("#new-trans-btn").on("click", () => {
    $("#transaction-form").toggle("slow");
  });

  /*###################################
  GET TRANSACTION DATA FROM THE DATABASE
  ######################################*/
  function getTransactions() {
    $.ajax({
      url: "functions.php",
      data: { action: "transaction" },
      success: (response) => {
        console.log(response);
        data = JSON.parse(response);

        let totalExpense = 0;
        let totalIncome = 0;

        data.forEach(function (transaction, index) {
          if (transaction["type"] === "income") {
            totalIncome += transaction["amount"];
          } else {
            totalExpense += transaction["amount"];
          }
        });

        const netIncome = totalIncome - totalExpense;

        $("#totalExpense").text(`$${totalExpense}`);
        $("#totalIncome").text(`$${totalIncome}`);
        $("#netIncome").text(`$${netIncome}`);
        $("#transactions").empty();
        data.forEach((transaction) => {
          $("#transactions")
            .prepend(`<div class="card mb-1 rounded-sm shadow-md p-3 border-0 text-white ${
            transaction["type"] === "income" ? "bg-success" : "bg-danger"
          }">
          ${transaction["type"]}
                <div class="card-body hstack">
                    <div class="vstack">
                        <h5 class="card-title fw-semibold fs-3">$${
                          transaction["amount"]
                        }</h5>
                        <p class="card-text">${transaction["description"]}</p>
                    </div>
                    <div class="hstack gap-3">
                        <a class="btn btn-link delete ${
                          transaction["type"] === "income"
                            ? "text-danger"
                            : "text-white"
                        }" data-id=${
            transaction["id"]
          } href="functions.php?action=delete&id=${
            transaction["id"]
          }"><i class="fa-solid fa-trash-can"></i></a>
                    </div>
                </div>
            </div>`);
        });
      },
    });
  }
});
