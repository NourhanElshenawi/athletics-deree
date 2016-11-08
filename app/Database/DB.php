<?php
namespace Nourhan\Database;

use Nourhan\Services\Upload;
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

    public function getClass($id)
    {
        $stmt = $this->conn->prepare("select * from dereeAthletics.classes WHERE id = ?");
        $stmt->bindValue(1,$id);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

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

    public function getUserProfile($id)
    {
        $stmt = $this->conn->prepare("select * from dereeAthletics.users WHERE id = ?");
        $stmt->bindValue(1,$id);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        return $result;
    }


    /*********USER VERIFICATION************/

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
        $stmt = $this->conn->prepare("update dereeAthletics.classes set duration = ?, startTime = ?, capacity = ?,
 instructorID = ?  WHERE id = ? ");

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


    ///////Nurse
    public function getUserCertificates()
    {
        $stmt = $this->conn->prepare("
          select *
          from dereeAthletics.users
          join dereeAthletics.user_certificates
          on users.id = user_certificates.userID
          WHERE user_certificates.certificate_status = ?;
          ");
        $stmt->bindValue(1, '0');
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function approveUserCertificate($id)
    {
        $stmt = $this->conn->prepare("update dereeAthletics.user_certificates set certificate_status = ? WHERE id = ? ");

        try{
            $stmt->bindValue(1, '1');
            $stmt->bindValue(2, $id);
            $result = $stmt->execute();

            return $result;
        } catch (Exception $e) {
            return false;
        }
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

    public function getUsersByID($id)
    {
        $stmt = $this->conn->prepare("select * from dereeAthletics.users WHERE id = ?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        return $result;
    }

    public function searchUsersByID($id, $keyword)
    {
        $stmt = $this->conn->prepare("select * from dereeAthletics.users WHERE id = ? AND (name LIKE ? or 
    email like ? or gender like ? or birthDate like ?) ");
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, $keyword);
        $stmt->bindValue(3, $keyword);
        $stmt->bindValue(4, $keyword);
        $stmt->bindValue(5, $keyword);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getUserRegistrations($id)
    {
        $stmt = $this->conn->prepare("select * from dereeAthletics.registrations WHERE userID = ?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getRegisteredUsers($classID)
    {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM dereeAthletics.users
            INNER JOIN dereeAthletics.registrations
            ON dereeAthletics.registrations.userID=dereeAthletics.users.id
            AND dereeAthletics.registrations.classID=:class limit 5;"
        );

        $stmt->bindParam(':class', $classID);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function searchClassRegistrations($classID, $keyword)
    {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM dereeAthletics.users
            INNER JOIN dereeAthletics.registrations
            ON dereeAthletics.registrations.userID=dereeAthletics.users.id
            AND dereeAthletics.registrations.classID=:class WHERE dereeAthletics.users.id LIKE :id or dereeAthletics.users.email LIKE :email
            OR dereeAthletics.users.name LIKE :username or dereeAthletics.users.gender LIKE :gender limit 5;"
        );

        $stmt->bindParam(':class', $classID);
        $stmt->bindParam(':id', $keyword);
        $stmt->bindParam(':email', $keyword);
        $stmt->bindParam(':username', $keyword);
        $stmt->bindParam(':gender', $keyword);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function userExists($email)
    {
        $stmt = $this->conn->prepare("
 				Select * FROM dereeAthletics.users
 				WHERE email=:email;"
        );
        $stmt->bindParam(':email', $email);

        try {
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $row = $stmt->fetch();

            return $row;
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function addUser($user, $userImage)
    {
        //Check if user exists already
        $row = $this->userExists($user['email']);

        if (isset($row) && $row != false) {
            $result['success'] = false;
            $result['message'] = "Error. A user with email {$user['email']} already exists.";

            return $result;
        }

        //Upload user image
        $uploadService = new Upload();

        $userImage['name'] = $user['email'] . '_' . basename($userImage["name"]);

        $uploaded = $uploadService->upload($userImage, $user);

        if ($uploaded['success'] == false) {
            die($uploaded['message']);
        }

        $stmt = $this->conn->prepare("
                    insert into dereeAthletics.users
                    (name, email, password, birthDate, gender, membershipType, picture, admin, active)
                    VALUES  (:name, :email, :password, :birthDate, :gender, :membershipType, :picture, :admin, :active);"
        );
        $stmt->bindParam(':name', $user['name']);
        $stmt->bindParam(':email', $user['email']);
        $stmt->bindParam(':password', $user['password']);
        $stmt->bindParam(':birthDate', $user['birthDate']);
        $stmt->bindParam(':gender', $user['gender']);
        $stmt->bindParam(':membershipType', $user['membershipType']);
        $stmt->bindParam(':picture', $userImage['name']);
        $stmt->bindParam(':admin', $user['admin']);
        $stmt->bindParam(':active', $user['active']);

        try
        {
            $success = $stmt->execute();

            if ($success) {
                $result['success'] = true;
                $result['message'] = "Success! User {$user['name']} added.";
            }
            return $result;
        }
        catch (PDOException $e)
        {
            die($e->getMessage());
        }

        return $result;
    }

    public function addMutlipleUsers($user)
    {
        $result['success'] = false;
        $result['message'] = "";

        //Check if user exists
        $row = $this->userExists($user->email);

        //If user exists then update his info
        if (isset($row) && $row != false) {
            $stmt = $this->conn->prepare("
 				update dereeAthletics.users
 				set name=:name, email=:email, password=:password, birthDate=:birthDate, gender=:gender, membershipType=:membershipType, picture=:picture, admin=:admin, active=:active
 				WHERE id=:id;"
            );
            $stmt->bindParam(':name', $user->name);
            $stmt->bindParam(':email', $user->email);
            $stmt->bindParam(':password', $user->password);
            $stmt->bindParam(':birthDate', $user->birthDate);
            $stmt->bindParam(':gender', $user->gender);
            $stmt->bindParam(':membershipType', $user->membershipType);
            $stmt->bindParam(':picture', $user->picture);
            $stmt->bindParam(':admin', $user->admin);
            $stmt->bindParam(':active', $user->active);
            $stmt->bindParam(':id', $row['id']);

            try
            {
                $stmt->execute();

                $result['success'] = true;
                $result['message'] = "Success! User {$row['id']} updated!";
            }
            catch (PDOException $e)
            {
                die($e->getMessage());
            }

        }
        else { //if user does not exist then add a new one
            $stmt = $this->conn->prepare("
                    insert into dereeAthletics.users
                    (name, email, password, birthDate, gender, membershipType, picture, admin, active)
                    VALUES  (:name, :email, :password, :birthDate, :gender, :membershipType, :picture, :admin, :active);"
            );
            $stmt->bindParam(':name', $user->name);
            $stmt->bindParam(':email', $user->email);
            $stmt->bindParam(':password', $user->password);
            $stmt->bindParam(':birthDate', $user->birthDate);
            $stmt->bindParam(':gender', $user->gender);
            $stmt->bindParam(':membershipType', $user->membershipType);
            $stmt->bindParam(':picture', $user->picture);
            $stmt->bindParam(':admin', $user->admin);
            $stmt->bindParam(':active', $user->active);

            try
            {
                $success = $stmt->execute();

                if ($success) {
                    $result['success'] = true;
                    $result['message'] = "Success! User {$user->name} added.";
                }
                return $result;
            }
            catch (PDOException $e)
            {
                die($e->getMessage());
            }
        }

        return $result;
    }

    public function deleteUser($id)
    {
        $stmt = $this->conn->prepare("delete from dereeAthletics.users WHERE id = ?");
        $stmt->bindValue(1,$id);
        $stmt->execute();
    }

    public function unregisterClass($id, $classID)
    {
        $stmt = $this->conn->prepare("delete from dereeAthletics.registrations WHERE userID = ? and classID = ?");
        $stmt->bindValue(1,$id);
        $stmt->bindValue(2,$classID);
        $stmt->execute();
    }

    public function registerClass($id, $classID)
    {
        try{
            $stmt = $this->conn->prepare("insert into dereeAthletics.registrations (userID, classID) VALUES  (?, ?)");
            $stmt->bindValue(1,$id);
            $stmt->bindValue(2,$classID);
            $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    /*******GET USER LOGS FOR USER STATS*********/

    public function getUserLogin($id){

        $stmt = $this->conn->prepare("select * from dereeAthletics.logs WHERE userID = ? AND logout IS NULL ");
        $stmt->bindValue(1, $id);
//        $stmt->bindValue(2, NULL);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        return $result;

    }


    /*******GET ADMIN STATS*********/

    public function getUsersLogin(){

        $stmt = $this->conn->prepare("select * from dereeAthletics.logs WHERE logout IS NULL ");

        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;

    }

    public function getUsersLogs(){

        $stmt = $this->conn->prepare("select * from dereeAthletics.logs");

        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;

    }

    public function getRealtimeLogs(){

        $stmt = $this->conn->prepare("select DISTINCT (userID) from dereeAthletics.logs WHERE logout is NULL");

        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;

    }

    /*******STATS******/

    public function getUsersLogsDays(){

        $stmt = $this->conn->prepare("select DAYOFWEEK (login) from dereeAthletics.logs");
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;

    }

    public function getUsersLogsMonths($gender='f', $ageUp, $ageDown){

        $stmt = $this->conn->prepare("
            SELECT MONTH(dereeAthletics.logs.login)
            FROM dereeAthletics.users
            INNER JOIN dereeAthletics.logs
            ON dereeAthletics.logs.userID=dereeAthletics.users.id
            AND dereeAthletics.users.gender=:gender AND dereeAthletics.users.birthDate BETWEEN :down AND :up;"
        );

        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':up', $ageUp);
        $stmt->bindParam(':down', $ageDown);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getUsersLogsYears(){

        $stmt = $this->conn->prepare("select YEAR (login) from dereeAthletics.logs");
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;

    }

    public function getUsersAge(){

        $stmt = $this->conn->prepare("select TIMESTAMPDIFF(YEAR,birthDate,CURDATE()) AS age from dereeAthletics.users");
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;

    }

    public function getUsersGender(){

        $stmt = $this->conn->prepare("select gender from dereeAthletics.users");
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;

    }


    /******RECORDING USER LOGS WITH NFC********/

    public function signout($id, $date)
    {
        $stmt = $this->conn->prepare("update dereeAthletics.logs set logout = ? WHERE userID = ? AND logout IS NULL ");

        try{
            $stmt->bindValue(1, $date);
            $stmt->bindValue(2, $id);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            //redirect error!
            return false;
        }
    }

    public function signin($id, $date)
    {
        $stmt = $this->conn->prepare("insert into dereeAthletics.logs (userID, login) VALUES  (?,?)  ");

        try{

            $stmt->bindValue(1, $id);
            $stmt->bindValue(2, $date);
            $stmt->execute();

            return true;
        } catch (Exception $e) {
            //redirect error!
            return false;
        }
    }

    public function getUserLogs ($id) {
        $stmt = $this->conn->prepare("select * from dereeAthletics.logs WHERE userID = ? AND MONTH(login) = 10 AND YEAR(login) = 2016");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }






}