<?php

/* ====================================================== */
/* Database connection function */
/* ====================================================== */
function dbConnect()
{
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "test";

    $conn = mysqli_connect($hostname, $username, $password, $database) or die("Database connection failed.");
    return $conn;
}

$conn = dbConnect();

/* ====================================================== */
/* Check email is valid or not function */
/* ====================================================== */

function emailIsValid($email)
{
    $conn = dbConnect();
    $sql = "SELECT email FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        return true;
    } else {
        return false;
    }
}


/* ====================================================== */
/* Check login details are valid or not function */
/* ====================================================== */

function checkLoginDetails($email, $password)
{
    $conn = dbConnect();
    $sql = "SELECT email FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        return true;
    } else {
        return false;
    }
}


/* ====================================================== */
/* Create user function */
/* ====================================================== */

function createUser($email, $username,$password)
{
    $conn = dbConnect();
    $sql = "INSERT INTO users (email, username,password) VALUES ('$email', '$username', '$password')";
    $result = mysqli_query($conn, $sql);
    return $result;
}


/* ====================================================== */
/* Get Head function */
/* ====================================================== */

function getHead()
{
    $pageTitle = dynamicTitle();
    $output = '<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
   
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">

    <!-- DATATABLE PLUGIN -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css"/>



    <title>'. $pageTitle .' - Pure Coding</title>';

    echo $output;
}


/* ====================================================== */
/* Get Header function */
/* ====================================================== */

function getHeader()
{
    $output = '<header class="py-3 mb-4 border-bottom bg-white">
        <div class="d-flex flex-wrap justify-content-center container">
            <a href="todos.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                <span class="fs-4">Tasks App</span>
            </a>

            <ul class="nav nav-pills">
                <li class="nav-item"><a href="todos.php" class="nav-link active" aria-current="page">Home</a></li>
                <li class="nav-item"><a href="add-todo.php" class="nav-link text-dark">Add Tasks</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link bg-danger text-white">Logout</a></li>
            </ul>
        </div>
    </header>';

    echo $output;
}


/* ====================================================== */
/* Text Limit function */
/* ====================================================== */

function textLimit($string, $limit)
{
    if (strlen($string) > $limit) {
        return substr($string, 0, $limit) . "...";
    } else {
        return $string;
    }
}



/* ====================================================== */
/* Get Todo function */
/* ====================================================== */

function getTodo($todos)
{   
    //
    $resp = array();
    $action = "";
    foreach ($todos as $todo) {
        $actions = '<a href="edit-todo.php?id='. $todo['id'] .'" target="_blank" class="btn btn-sm btn-outline-secondary">Edit</a>';
        $actions .= '<a href="delete-todo.php?id='. $todo['id'] .'" target="_blank" class="btn btn-sm btn-outline-danger">Delete</a>';
        $resp[] = [
            'id'=>$todo['id'],
            'title'=>$todo['title'],
            'comment'=>$todo['comment'],
            'date'=>$todo['date'],
            'time'=>$todo['time'],
            'actions'=>$actions
        ];

    }

    echo json_encode($resp);
}



/* ====================================================== */
/* Dynamic Title function */
/* ====================================================== */

function dynamicTitle()
{
    global $conn;
    $filename = basename($_SERVER["PHP_SELF"]);
    $pageTitle = "";
    switch ($filename) {
        case 'index.php':
            $pageTitle = "Home";
            break;

        case 'todos.php':
            $pageTitle = "Todo List";
            break;

        case 'add-todo.php':
            $pageTitle = "Add Todo";
            break;

        case 'edit-todo.php':
            $pageTitle = "Edit Todo";
            break;

        case 'view-todo.php':
            $todoId = mysqli_real_escape_string($conn, $_GET["id"]);
            $sql1 = "SELECT * FROM todos WHERE id='{$todoId}'";
            $res1 = mysqli_query($conn, $sql1);
            if (mysqli_num_rows($res1) > 0) {
                foreach ($res1 as $todo) {
                    $pageTitle = $todo["title"];
                }
            }
            break;

        default:
            $pageTitle = "Todo List";
            break;
    }

    return $pageTitle;
}