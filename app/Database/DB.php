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

 ////// EDIT CLASSES
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
            return true;
        } catch (Exception $e) {
            //redirect error!
        }
    }


    public function searchClasses($keyword)
    {
        $stmt = $this->conn->prepare("select * from dereeAthletics.classes WHERE name LIKE ?");

            $stmt->bindValue(1, $keyword);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();

        return $result;

    }

    public function addClass($name, $duration, $instructorID, $startTime, $period, $capacity, $location, $monday, $tuesday, $wednesday, $thursday, $friday)
    {
        $stmt = $this->conn->prepare("insert into dereeAthletics.classes (name, duration, instructorID, startTime, period, 
capacity, location, monday, tuesday, wednesday, thursday, friday) VALUES  (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        try{
            $stmt->bindValue(1, $name);
            $stmt->bindValue(2, $duration);
            $stmt->bindValue(3, $instructorID);
            $stmt->bindValue(4, $startTime);
            $stmt->bindValue(5, $period);
            $stmt->bindValue(6, $capacity);
            $stmt->bindValue(7, $location);
            $stmt->bindValue(8, $monday);
            $stmt->bindValue(9, $tuesday);
            $stmt->bindValue(10, $wednesday);
            $stmt->bindValue(11, $thursday);
            $stmt->bindValue(12, $friday);

            $stmt->execute();

            return true;
        } catch (Exception $e) {
            return false;
        }


    }


    public function deleteClass($id)
    {
        $stmt = $this->conn->prepare("delete from dereeAthletics.classes WHERE id = ?");
        $stmt->bindValue(1,$id);
        $stmt->execute();
    }

    ///////USERS

    public function searchUsers($keyword)
    {
        $stmt = $this->conn->prepare("select * from dereeAthletics.users WHERE name LIKE ? OR id LIKE ? OR email LIKE ?");

        $stmt->bindValue(1, $keyword);
        $stmt->bindValue(2, $keyword);
        $stmt->bindValue(3, $keyword);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;


    }

    public function updateUser($id, $name, $email, $password, $birthDate, $gender, $membershipType, $admin)
    {
        $stmt = $this->conn->prepare("update dereeAthletics.users set name = ?, email = ?, password = ?, birthDate = ?,
 gender = ?, membershipType = ?, admin = ? WHERE id = ? ");

        try{
            $stmt->bindValue(1, $name);
            $stmt->bindValue(2, $email);
            $stmt->bindValue(3, $password);
            $stmt->bindValue(4, $birthDate);
            $stmt->bindValue(5, $gender);
            $stmt->bindValue(6, $membershipType);
            $stmt->bindValue(7, $admin);
            $stmt->bindValue(8, $id);
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            //redirect error!
        }
    }

    public function getUsers()
    {
        $stmt = $this->conn->prepare("select * from dereeAthletics.users");
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }


    public function deleteUser($id)
    {
        $stmt = $this->conn->prepare("delete from dereeAthletics.users WHERE id = ?");
        $stmt->bindValue(1,$id);
        $stmt->execute();
    }



}