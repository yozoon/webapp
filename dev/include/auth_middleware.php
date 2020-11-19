<?php
require_once "../../../secure/info.inc.php";
$user_sql = $db->prepare("SELECT username FROM user WHERE UID LIKE '".$_POST['uid']."'");
$user_sql->execute($user_sql->errorInfo());
$rowuser = $user_sql->fetchColumn();
$username = $rowuser;

function auth_wrapper($token, callable $function, $params = null) {
    if ($token == "1234") {
        rwdb(false);
    } else {
        header("HTTP/1.1 401 Unauthorized");
        echo "unauthorized";
    exit;
    }
}

$token = $_GET["token"];

function rwdb($params) {
    require "rwdb.php";
}

auth_wrapper($token, function ($params) {
    rwdb($params);
});
?>