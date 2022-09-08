<?php
include "inc/header.php";
session_destroy();
header("Location: index.php");
include "inc/footer.php";
