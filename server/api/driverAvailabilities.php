<?php
header('Content-Type: application/json');

include '../env.php';
include '../sanitizationValidation.php';
require_once "../db.class.php";
$db = new DB($host, $port, $name, $user, $pass); // From dbinfo.php

switch ($_SERVER['REQUEST_METHOD']) {
   case "GET":
      if (isset($_GET['id']))
      {
         echo json_encode($db->driverAvailability->getDriverAvailability($_GET['id']));
      }
      else if (isset($_GET['page']) && isset($_GET['number_per_page']))
      {
         echo json_encode($db->driverAvailability->getDriverAvailabilities($_GET['page'], $_GET['number_per_page']?:10));
      }
      else
      {
         echo json_encode($db->driverAvailability->getDriverAvailabilities(0, 100));
      }
      break;

   case "POST":
      $requestBody = file_get_contents('php://input');
      $bodyData = json_decode($requestBody, true);

      //print_r($bodyData);

      if (isset($bodyData['start']) && isset($bodyData['end']) && (isset($bodyData['days'])) && isset($bodyData['driverID']))
      {
         $startTime = $bodyData['start'];
         $endTime = $bodyData['end'];
         $days = $bodyData['days'];
         $driverID = $bodyData['driverID'];

         $startTime = sanitizeAndValidate($startTime, FILTER_SANITIZE_STRING);
         $endTime = sanitizeAndValidate($endTime, FILTER_SANITIZE_STRING);
         $days = sanitizeAndValidate($days, FILTER_SANITIZE_STRING);
         $driverID = sanitizeAndValidate($driverID, FILTER_SANITIZE_NUMBER_INT, FILTER_VALIDATE_INT);

         http_response_code(201);
         echo json_encode($db->driverAvailability->insertDriverAvailability($startTime, $endTime, $days, $driverID));
      }
      else
      {
         http_response_code(404);
         echo json_encode(["err" => "Could not create driver schedule"]);
         die();
      }

      break;

   case "PUT":
      $requestBody = file_get_contents('php://input');
      $bodyData = json_decode($requestBody, true);

      //print_r($bodyData);

      if (isset($bodyData['id']))
      {
         $id = $bodyData['id'];
         $startTime = $bodyData['start'] ?? "";
         $endTime = $bodyData['end'] ?? "";
         $days = $bodyData['days'] ?? "";
         $driverID = $bodyData['driverID'] ?? "";

         $id = sanitizeAndValidate($id, FILTER_SANITIZE_NUMBER_INT, FILTER_VALIDATE_INT);
         $startTime = sanitizeAndValidate($startTime, FILTER_SANITIZE_STRING);
         $endTime = sanitizeAndValidate($endTime, FILTER_SANITIZE_STRING);
         $days = sanitizeAndValidate($days, FILTER_SANITIZE_STRING);
         $driverID = sanitizeAndValidate($driverID, FILTER_SANITIZE_NUMBER_INT, FILTER_VALIDATE_INT);

         http_response_code(201);
         echo json_encode($db->driverAvailability->updateDriverAvailability($id, $startTime, $endTime, $days, $driverID));
      }
      else
      {
         http_response_code(404);
         echo json_encode(["err" => "Could not update driver schedule"]);
         die();
      }

      break;

   case "DELETE":
      $requestBody = file_get_contents('php://input');
      $bodyData = json_decode($requestBody, true);

      if (isset($bodyData['id']))
      {
         $id = $bodyData['id'];

         $id = sanitizeAndValidate($id, FILTER_SANITIZE_NUMBER_INT, FILTER_VALIDATE_INT);

         http_response_code(201);
         echo json_encode($db->driverAvailability->deleteDriverAvailability($id));
      }
      else
      {
         http_response_code(404);
         echo json_encode(["err" => "Could not delete driver schedule"]);
         die();
      }

      break;
}
