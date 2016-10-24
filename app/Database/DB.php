<?php
namespace Nourhan\Database;

use PDO;
use PDOException;

class DB
{

    protected $serverName;
    protected $port;
    protected $dbName;
    protected $username;
    protected $password;
    protected $conn;

    /**
     * DB constructor. By default connect to Homestead virtual DB server and to the 'kourtis' database schema.
     * @param string $servername
     * @param string $port
     * @param string $dbname
     * @param string $username
     * @param string $password
     */

    public function __construct($serverName = "127.0.0.1", $port = "33060", $dbName = "dereeAthletics",$username = "homestead", $password = "secret" )
    {
        $this->serverName = $serverName;
        $this->port = $port;
        $this->dbname = $dbName;
        $this->username = $username;
        $this->password = $password;

        $this->connect();
    }

    public function connect()
    {
        try{
            $conn = new PDO("mysql:host=$this->serverName;port:$this->port;dbname=$this->dbName", $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn = $conn;
//            echo "Connection Established!";
        }   catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }


    public function getClasses()
    {
        $stmt = $this->conn->prepare("select * from dereeAthletics.classes");
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getInstructors()
    {
        $stmt = $this->conn->prepare("select * from dereeAthletics.instructors");
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getInstructor($id)
    {
        $stmt = $this->conn->prepare("select * from dereeAthletics.instructors WHERE id=?");
        $stmt->bindValue(1,$id);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        return $result;
    }

    public function getUser($email, $password)
    {
        $stmt = $this->conn->prepare("select * from dereeAthletics.users WHERE email = ? and password = ?");
        $stmt->bindValue(1,$email);
        $stmt->bindValue(2,$password);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        return $result;
    }


//    public function updateClass($id, $duration, $startTime, $capacity,$instructorID)
//    {
//        $stmt = $this->conn->prepare("update dereeAthletics.classes set duration = ?, startTime = ?, capacity = ?, instructorID = ?  WHERE id = ? ");
//
//        try{
//            $stmt->bindValue(1, $duration);
//            $stmt->bindValue(2, $startTime);
//            $stmt->bindValue(3, $capacity);
//            $stmt->bindValue(4, $instructorID);
//            $stmt->bindValue(5, $id);
//            $stmt->execute();
//
//            return true;
//        } catch (Exception $e) {
//        }
//
//    }



    public function getCarousel()
    {
        $stmt = $this->conn->prepare("select * from project1.carousel WHERE included='1' ORDER by POSITION ASC ");
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getNotIncludedCarousel()
    {
        $stmt = $this->conn->prepare("select * from project1.carousel WHERE included='0' ORDER by POSITION ASC ");
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getAllCarousel()
    {
        $stmt = $this->conn->prepare("select * from project1.carousel");
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();


        return $result;
    }

    public function getCarouselImageByPosition($position)
    {
        $stmt = $this->conn->prepare("select * from project1.carousel WHERE position = ?");
        $stmt->bindValue(1,$position);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
        return $result;
    }
    public function updateCarouselPosition($id, $position)
    {
        $stmt = $this->conn->prepare("insert into project1.carousel WHERE id = ? (position) VALUES (?)");

        try{
            $stmt->bindValue(1, $id);
            $stmt->bindValue(2, $position);
            $stmt->execute();
        } catch (Exception $e) {
        }
    }


    public function includeInCarousel($id, $position)
    {
        $stmt = $this->conn->prepare("update project1.carousel set included = ?, POSITION = ? WHERE id = ? ");

        try{
            $stmt->bindValue(1, "1");
            $stmt->bindValue(2, $position);
            $stmt->bindValue(3, $id);
            $stmt->execute();
        } catch (Exception $e) {
        }
    }

    public function getUserCredentials($username, $password)
    {
        $stmt = $this->conn->prepare("select * from dereeAthletics.users WHERE email = ? and password = ?");
        $stmt->bindValue(1,$username);
        $stmt->bindValue(2,$password);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        return $result;
    }

 /*************ADMIN***************/
    public function updateClass($id, $duration, $startTime, $capacity,$instructorID)
    {
        $stmt = $this->conn->prepare("update dereeAthletics.classes set duration = ?, startTime = ?, capacity = ?, instructorID = ?  WHERE id = ? ");

        try{
            $stmt->bindValue(1, $duration);
            $stmt->bindValue(2, $startTime);
            $stmt->bindValue(3, $capacity);
            $stmt->bindValue(4, $instructorID);
            $stmt->bindValue(5, $id);
            $stmt->execute();
        } catch (Exception $e) {
        }
    }




}