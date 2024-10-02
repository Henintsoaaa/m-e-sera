<?php
// an api to get the session id
session_start();
$sessionId = $_SESSION['id'];

echo json_encode($sessionId);