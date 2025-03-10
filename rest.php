<?php
require_once 'corpse-chat/corpse-chat.php';

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"), true);

switch($method) {
  case "POST":
    if(isset($input['message'])) {
      $message = $input['message'];
      $chat = new ChatBot();
      $response = $chat->generateResponse($message);
      echo json_encode(["status" => "success", "message" => $response]);
    } else {
      echo json_encode([
        "status" => "failed",
        "message" => $input
      ]);
    }
  break;
  default:
    echo json_encode(array("status" => "error", "message" => "Invalid request method"));
}
