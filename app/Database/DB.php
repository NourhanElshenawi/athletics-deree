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

    public function __construct($serverName = "127.0.0.1", $port = "33060", $dbName = "project1",$username = "homestead", $password = "secret" )
    {
        $this->serverName = $serverName;
        $this->port = $port;
        $this->dbname = $dbName;
        $this->username = $username;
        $this->password = $password;

        $this->connect();

//        $stmt = $this->conn->prepare("SELECT * FROM project1.carousel");
//        $stmt->execute();
//        // set the resulting array to associative
//        $stmt->setFetchMode(PDO::FETCH_ASSOC);
//        $result = $stmt->fetchAll();
//        var_dump($result);
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


    public function uploadCarousel($name)
    {
        $stmt = $this->conn->prepare("insert into project1.carousel(name) VALUES (?)");
        try{
            $stmt->bindValue(1,$name);
            $stmt->execute();
            echo "DONE!";
        }   catch(Exception $e){
                              echo "ERROR";
        }
    }

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

    public function removeFromCarousel($id)
    {
        $stmt = $this->conn->prepare("update project1.carousel set included = ?, POSITION = ? WHERE id = ? ");

        echo "ID: ".$id;
        try{
            $stmt->bindValue(1, "0");
            $stmt->bindValue(2, null);
            $stmt->bindValue(3, $id);
            $stmt->execute();
        } catch (Exception $e) {
        }
    }

    public function getCarouselImage($id)
    {
        $stmt = $this->conn->prepare("select * from project1.carousel WHERE id = ?");
        $stmt->bindValue(1,$id);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        return $result;
    }

    public function updateCarousel($id, $position, $included)
    {

        $replacing = $this->getCarouselImageByPosition($position);
        $currentlyEditing = $this->getCarouselImage($id);

        $stmt = $this->conn->prepare("update project1.carousel set position = ? WHERE id = ? ");

        try{
            $stmt->bindValue(1, $currentlyEditing['position']);
            $stmt->bindValue(2, $replacing['id']);
            $stmt->execute();
        } catch (Exception $e) {
        }


        $stmt = $this->conn->prepare("update project1.carousel set position = ?, included = ? WHERE id = ?");

        try{
            $stmt->bindValue(1, $position);
            $stmt->bindValue(2, $included);
            $stmt->bindValue(3, $id);
            $stmt->execute();
        } catch (Exception $e) {
        }

    }

    public function deleteCarouselImage($id)
    {
        $stmt = $this->conn->prepare("delete from project1.carousel WHERE id = ?");
        $stmt->bindValue(1,$id);
        $stmt->execute();
    }



}