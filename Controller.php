<?php
include("database.php");

if (isset($_POST["operation"]))
{
  if ($_POST["operation"] == "Add")
  {
    $db = new \pdo\Database();
    $video_name = $_POST["video_name"];
    $video_duration = $_POST["video_duration"];
    $video_class = $_POST["video_class"];
    $video_description = $_POST["video_description"];
    $video_subject = $_POST["video_subject"];
    $fileName = $_FILES["video_file"]["name"];
    $fileLocation = $_POST["video_class"];

    $fileId = $db->insertVideo($video_name, $video_description, $video_class, $video_subject, $fileName, $fileLocation, $video_duration);
    if ($fileId > 0)
    {
      $fileName = pathinfo($fileName, PATHINFO_FILENAME) . "_" . $fileId . "." . pathinfo($fileName, PATHINFO_EXTENSION);
      move_uploaded_file($_FILES["video_file"]["tmp_name"], "./data/videos/" . $video_class . "/" . $fileName);
    }

    echo 'Video inserted';
  }
  if ($_POST["operation"] == "Edit")
  {
    $db = new \pdo\Database();
    $id = $_POST["video_id"];
    $output = $db->getVideoData($id);

    echo json_encode($output);
    return;
  }
  if ($_POST["operation"] == "Update")
  {


    $id = $_POST["video_id"];
    $video_name = $_POST["video_name"];
    $video_duration = $_POST["video_duration"];
    $video_class = $_POST["video_class"];
    $video_description = $_POST["video_description"];
    $video_subject = $_POST["video_subject"];

    $db = new \pdo\Database();
    $db->update($id, $video_name, $video_description, $video_duration, $video_class, $video_subject);

  }
  if ($_POST["operation"] == "Delete")
  {
    $db = new \pdo\Database();
    $db->delete($_POST["video_id"]);
  }
}
//fetch data
$data = array();
$records_per_page = 30;
$start_from = 0;
$current_page_number = 1;
$records_per_page = 10;
if (isset($_POST["rowCount"]))
{
  $records_per_page = $_POST["rowCount"];
}
if (isset($_POST["current"]))
{
  $current_page_number = $_POST["current"];
}
$start_from = ($current_page_number - 1) * $records_per_page;

$search = '';
if (!empty($_POST["searchPhrase"]))
{
  $search = $_POST["searchPhrase"];
}
$order_by = 'ORDER BY id DESC';
if (isset($_POST["sort"]) && is_array($_POST["sort"]))
{
  foreach ($_POST["sort"] as $key => $value)
  {
    $order_by = "ORDER BY $key $value";
  }
}

$limit = '';
if ($records_per_page != -1)
{
  $limit .= " LIMIT " . $start_from . ", " . $records_per_page;
}

$db = new \pdo\Database();
list($data, $total_records) = $db->fetch($search, $order_by, $limit);

$output = array(
  'current' => intval(!empty($_POST["current"]) ? $_POST["current"] : 1),
  'rowCount' => 10,
  'total' => intval($total_records),
  'rows' => $data
);

echo json_encode($output);


?>
