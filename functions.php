<?php

require "config/database.php";
session_start();

function register_user($username, $email, $password)
{
    /*------------------------------------------
    REGISTER USER
    Register user and redirect to login or return errors
    ---------------------------------------------*/
    global $conn;
    $errors = [];
    // Create hashed password to store in the database
    $password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Check if user already exists
        $sql = "SELECT * FROM users WHERE username = ? && email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username, $email]);
        if ($stmt->fetchAll()) {
            $errors["errors"]["non-field"] = "User already exists";
            return json_encode($errors);
        };

        // If user does not exist, register new user
        $sql = "INSERT INTO users (username, email, password) VALUES (?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username, $email, $password]);
        return json_encode(["success" => "user registered successfully"]);
    } catch (Exception $e) {
        return json_encode(["errors" => ["non-field" => "unable to create user"]]);
    }
};

function login_user($username, $password)
{
    /*----------------------------------
    LOGIN USER
    Login user and return user object or errors
    -------------------------------------*/
    global $conn;
    $sql = "SELECT * FROM users WHERE username=?";
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            if (password_verify($password, $result["password"])) {
                array_pop($result);
                $_SESSION["user"] = $result;
                return json_encode(["success" => "User logged in successfully"]);
            };
        } else {
            return json_encode(["errors" => ["non-field" => "Invalid credentials"]]);
        }
    } catch (Exception $e) {
        return json_encode(["errors" => ["non-field" => "Unable to login at the moment"]]);
    }
}

function create_transaction($type, $amount, $desc)
{
    /*-------------------------------
    Create a new deposit or expense transaction in the database
    --------------------------------*/
    global $conn;
    try {
        $sql = "INSERT INTO transactions(type, amount, description, userid) VALUES (?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$type, $amount, $desc, $_SESSION["user"]["id"]]);
        return json_encode(["success" => "Transaction created successfully", "type" => "transaction"]);
    } catch (Exception $e) {
        return json_encode(["errors" => ["non-field" => "Error creating new transaction"]]);
    }
}

function get_transactions()
{
    /*####################################
    GET ALL TRANSACTIONS FROM THE DATATBASE
    #######################################*/
    global $conn;
    try {
        $sql = "SELECT * FROM transactions WHERE userid=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_SESSION["user"]["id"]]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($rows);
    } catch (Exception $e) {
        return json_encode(["errors" => "unable to fetch data"]);
    }
}

function delete_transaction($id)
{
    /*######################################
    DELETE SINGLE TRANSACTION FROM DATABASE
    #######################################*/
    global $conn;
    try {
        $sql = "DELETE FROM transactions WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}


/*#########################################
POST ACTIONS FOR REGISTER, LOGIN AND NEW TRANSACTION
###########################################*/
if (isset($_POST['action'])) {
    if ($_POST['action'] === "register") {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $errors = [];

        // Validate email
        if (empty($username)) {
            $errors["errors"]["username"] = "Username field cannot be empty";
        }

        // Validate email
        if (!empty($email)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors["errors"]["email"] = "Please enter a valid email address";
            }
        } else {
            $errors["errors"]["email"] = "email field cannot be empty";
        }

        // Validate password
        if (empty($password)) {
            $errors["errors"]["password"] = "password field cannot be empty";
        }

        if ($errors) {
            echo json_encode($errors);
        } else {
            echo register_user($username, $email, $password);
        }
    } else if ($_POST["action"] === "login") {
        // ###################### lOGIN USERS ##############################//
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = $_POST["password"];
        echo login_user($username, $password);
    } else if ($_POST["action"] === "transaction") {
        // ###################### CREATE NEW TRANSACTION ##############################//
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_FLOAT);
        $desc = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $errors = [];

        if (empty($type)) {
            $errors["errors"]["type"] = "Please select a transaction type";
        }
        if (empty($amount)) {
            $errors["errors"]["amount"] = "Please enter an amount";
        }
        if (empty($desc)) {
            $errors["errors"]["description"] = "Please enter a valid description";
        }

        if ($errors) {
            echo json_encode($errors);
        } else {
            echo create_transaction($type, $amount, $desc);
        }
    }
}

/*################################
GET ALL TRANSACTIONS
##################################*/
if (isset($_GET["action"])) {
    if ($_GET["action"] === "transaction") {
        echo get_transactions();
    } elseif ($_GET["action"] === "delete") {
        if (delete_transaction($_GET["id"])) {
            header("Location: dashboard.php");
        };
    }
}
