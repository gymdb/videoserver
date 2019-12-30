<?php namespace pdo;

use PDO;
use PDOException;

class Database
{
  private $conn;

  # @array,  The database settings
  private $dbSettings;

  public function __construct()
  {
    $this->openConnection();
  }

  public function getConnection()
  {
    return $this->conn;
  }

  private function openConnection()
  {
    try
    {
      $this->dbSettings = parse_ini_file("settings.ini.php");
      $dsn = 'mysql:dbname=' . $this->dbSettings["dbname"] . ';host=' . $this->dbSettings["host"] . '';
      $this->conn = new PDO($dsn, $this->dbSettings["user"], $this->dbSettings["password"], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e)
    {
      error_log('There was an error connecting to the database, Error: ' . $e->getMessage());
      die();
    }
  }

  public function close()
  {
    $this->conn = null;
  }

  public function insertVideo($title, $description, $form, $subject, $fileName, $fileLocation, $duration)
  {
    try
    {
      $this->conn->beginTransaction();
      $stmt = $this->conn->prepare("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'media' AND TABLE_NAME = 'media'");
      $stmt->execute();
      $res = $stmt->fetch(PDO::FETCH_ASSOC);
      $nextId = $res['AUTO_INCREMENT'];
      $fileName = pathinfo($fileName, PATHINFO_FILENAME) . "_" . $nextId . "." . pathinfo($fileName, PATHINFO_EXTENSION);

      $stmt = $this->conn->prepare("INSERT INTO `media` (`description`, `duration`, `fileLocation`, `fileName`, `form`, `subject`, `title`,  `uploadedOn`) 
                                                     VALUES(:description, :duration, :fileLocation, :fileName, :form, :subject, :title, UNIX_TIMESTAMP(NOW())*1000)");
      $stmt->bindParam(":description", $description);
      $stmt->bindParam(":duration", $duration);
      $stmt->bindParam(":fileLocation", $fileLocation);
      $stmt->bindParam(":fileName", $fileName);
      $stmt->bindParam(":form", $form);
      $stmt->bindParam(":subject", $subject);
      $stmt->bindParam(":title", $title);
      $stmt->execute();
      $this->conn->commit();
      return $nextId;
    } catch (PDOException $e)
    {
      $this->conn->rollBack();
      error_log($e->getMessage());
      return -1;
    }
  }

  public function fetch($search, $orderBy, $limit)
  {
    try
    {
      //  $stmt = $this->conn->prepare("SELECT   id,   title,  description,  subject,  duration,   FROM_UNIXTIME(uploadedOn/1000,\"%d.%m.%Y %H:%i\") AS uploadedOn,  form,   fileLocation,  fileName FROM media WHERE (title LIKE \"%:search%\" OR description LIKE \"%:search%\" OR subject LIKE \"%:search%\") ".$orderBy." ".$limit);
      $query = "SELECT   id,   title,  description,  subject,  duration,   FROM_UNIXTIME(uploadedOn/1000,\"%d.%m.%Y %H:%i\") AS uploadedOn,  form," .
        "fileLocation,  fileName FROM media WHERE archived = 0 AND (subject LIKE  :search  OR title LIKE :search OR description LIKE :search )" . $orderBy;
      $stmt = $this->conn->prepare($query . " " . $limit);

      $stmt->bindValue(':search', '%' . $search . '%');
      $stmt->bindValue(':search', '%' . $search . '%');
      $stmt->bindValue(':search', '%' . $search . '%');
      $stmt->execute();

      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $stmt = $this->conn->prepare($query); //rowCount without limit
      $stmt->bindValue(':search', '%' . $search . '%');
      $stmt->bindValue(':search', '%' . $search . '%');
      $stmt->bindValue(':search', '%' . $search . '%');
      $stmt->execute();
      $rowCount = $stmt->rowCount();
      return array($result, $rowCount);
    } catch (PDOException $e)
    {
      error_log($e->getMessage());
      return [-1, -1];
    }
  }

  public function delete($id)
  {
    try
    {
      $stmt = $this->conn->prepare("UPDATE media set archived = TRUE where id = :id");
      $stmt->bindValue(":id", $id);
      $stmt->execute();
    } catch (PDOException $e)
    {
      error_log($e->getMessage());
      return false;
    }
  }

  public function update($id, $video_name, $video_description, $video_duration, $video_class, $video_subject)
  {
    try
    {
      $stmt = $this->conn->prepare("UPDATE media set title = :video_name, description = :video_description, duration = :video_duration, form = :video_class, subject = :video_subject  where id = :id");
      $stmt->bindValue(":video_name", $video_name);
      $stmt->bindValue(":video_description", $video_description);
      $stmt->bindValue(":video_duration", $video_duration);
      $stmt->bindValue(":video_class", $video_class);
      $stmt->bindValue(":video_subject", $video_subject);
      $stmt->bindValue(":id", $id);
      $stmt->execute();
    } catch (PDOException $e)
    {
      error_log($e->getMessage());
      return false;
    }

  }

  public function getVideoData($id)
  {
    try
    {
      $stmt = $this->conn->prepare("SELECT * from media WHERE id = :id");
      $stmt->bindValue(":id", $id);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result;
    } catch (PDOException $e)
    {
      error_log($e->getMessage());
      return false;
    }
  }


}
