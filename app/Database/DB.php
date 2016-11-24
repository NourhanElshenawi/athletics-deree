<?php
namespace Nourhan\Database;

use Nourhan\Services\Upload;
use PDO;
use PDOException;

class DB
{

    protected $host;
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

//    public function __construct($host = "127.0.0.1", $port = "33060", $dbName = "dereeAthletics",$username = "homestead", $password = "secret" )
//    {
//        $this->host = $host;
//        $this->port = $port;
//        $this->dbname = $dbName;
//        $this->username = $username;
//        $this->password = $password;
//
//        $this->connect();
//    }


    /**
     * DB constructor. Connect to Heroku's DB (ClearDB).
     */
        public function __construct()
        {
            $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
//            ddd($cleardb_url);
            $this->host = $cleardb_url["host"];;
            $this->port = 3306;
            $this->dbname = substr($cleardb_url["path"], 1);
            $this->username = $cleardb_url["user"];
            $this->password = $cleardb_url["pass"];

            $this->options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'];

            $this->connect();
        }


    public function connect()
    {
        try{
            $conn = new PDO("mysql:host=$this->host;port:$this->port;dbname=$this->dbName", $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn = $conn;
//            echo "Connection Established!";
        }   catch(PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function getClasses()
    {

        $stmt = $this->conn->prepare("select *, classes.name AS className, instructors.name AS instructorName,
                                      classes.id AS classID, instructors.id AS instructorID
                                      from {$this->dbname}.classes
                                      join {$this->dbname}.instructors
                                      on classes.instructorID = instructors.id");

        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getClass($id)
    {
        $stmt = $this->conn->prepare("select *, classes.name AS className, instructors.name AS instructorName,
                                      classes.id AS classID, instructors.id AS instructorID
                                      from {$this->dbname}.classes
                                      join {$this->dbname}.instructors
                                      on classes.instructorID = instructors.id
                                      WHERE classes.id =:classID");
        $stmt->bindParam(':classID',$id);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        return $result;
    }

    public function getInstructors()
    {
        $stmt = $this->conn->prepare("select * from {$this->dbname}.instructors");
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getInstructor($id)
    {
        $stmt = $this->conn->prepare("select * from {$this->dbname}.instructors WHERE id=?");
        $stmt->bindValue(1,$id);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        return $result;
    }

    public function getUser($email, $password)
    {
        try {
            $stmt = $this->conn->prepare("select * from {$this->dbname}.users WHERE email = ? and password = ?");
            $stmt->bindValue(1, $email);
            $stmt->bindValue(2, $password);
            $stmt->execute();
            // set the resulting array to associative
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetch();

        } catch (PDOException $e) {
            $result["success"] = 0;
            $result["message"] = "Database Error1. Please Try Again!";
        }

        return $result;
    }



    public function androidLogin($email, $password)
    {
        try {
            $stmt = $this->conn->prepare("select * from {$this->dbname}.users WHERE email = ? and password = ?");
            $stmt->bindValue(1, $email);
            $stmt->bindValue(2, $password);
            $stmt->execute();
            $numberOfRows = $stmt->fetchColumn();

            if($numberOfRows>0) {
                $result["success"] = 1;
                $result["message"] = "Welcome";
            } else {
                $result["success"] = 0;
                $result["message"] = "Invalid Credentials";
            }

        } catch (PDOException $e) {
            $result["success"] = 0;
            $result["message"] = "Database Error1. Please Try Again!";
        }

        return $result;
    }


    public function androidViewProgram($id)
    {
        try {
            $stmt = $this->conn->prepare("select *
                                          from {$this->dbname}.program_requests
                                          WHERE userID=:id and trainerResponse=:trainerResponse
                                          ORDER by program_requests.date DESC limit 1");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':trainerResponse', 1);
            $stmt->execute();
            $program = $stmt->fetch();


            $result["success"] = 1;
            $result["message"] = "Success";
            $result["program"] = $program;

        } catch (PDOException $e) {
            $result["success"] = 0;
            $result["message"] = "Database Error1. Please Try Again!";
        }

        return $result;
    }

    public function getUserProfile($id)
    {
        $stmt = $this->conn->prepare("select * from {$this->dbname}.users WHERE id = ?");
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
        $stmt = $this->conn->prepare("select * from {$this->dbname}.users WHERE email = ? and password = ?");
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
        $stmt = $this->conn->prepare("update {$this->dbname}.classes set duration = ?, startTime = ?, capacity = ?,
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
        $stmt = $this->conn->prepare("select *, classes.name AS className, instructors.name AS instructorName,
                                      classes.id AS classID, instructors.id AS instructorID
                                      from {$this->dbname}.classes
                                      join {$this->dbname}.instructors
                                      on classes.instructorID = instructors.id
                                      WHERE instructors.name LIKE :keyword or classes.name LIKE :keyword");
            $stmt->bindParam(':keyword', $keyword);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();

        return $result;

    }

    public function addClass($name, $duration, $instructorID, $startTime, $period, $capacity, $location, $monday, $tuesday, $wednesday, $thursday, $friday)
    {
        $stmt = $this->conn->prepare("insert into {$this->dbname}.classes (name, duration, instructorID, startTime, period, 
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
        $stmt = $this->conn->prepare("delete from {$this->dbname}.classes WHERE id = ?");
        $stmt->bindValue(1,$id);
        $stmt->execute();
    }


    ///////Nurse
    public function getUserCertificates()
    {
        $stmt = $this->conn->prepare("
          select *
          from {$this->dbname}.users
          join {$this->dbname}.user_certificates
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
        $stmt = $this->conn->prepare("update {$this->dbname}.user_certificates set certificate_status = ? WHERE id = ? ");

        try{
            $stmt->bindValue(1, '1');
            $stmt->bindValue(2, $id);
            $result = $stmt->execute();

            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    public function rejectUserCertificate($id)
    {
        $stmt = $this->conn->prepare("update {$this->dbname}.user_certificates set certificate_status = ? WHERE id = ? ");

        try{
            $stmt->bindValue(1, '2');
            $stmt->bindValue(2, $id);
            $result = $stmt->execute();

            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getApprovedCertificates()
    {
        $stmt = $this->conn->prepare("
          select *
          from {$this->dbname}.users
          join {$this->dbname}.user_certificates
          on users.id = user_certificates.userID
          WHERE user_certificates.certificate_status = ?;
          ");
        $stmt->bindValue(1, '1');
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getRejectedCertificates()
    {
        $stmt = $this->conn->prepare("
          select *
          from {$this->dbname}.users
          join {$this->dbname}.user_certificates
          on users.id = user_certificates.userID
          WHERE user_certificates.certificate_status = ?;
          ");
        $stmt->bindValue(1, '2');
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    ///////USERS

    public function searchUsers($keyword)
    {
        $stmt = $this->conn->prepare("select * from {$this->dbname}.users WHERE name LIKE ? OR id LIKE ? OR email LIKE ?");

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
        $stmt = $this->conn->prepare("update {$this->dbname}.users set name = ?, email = ?, password = ?, birthDate = ?,
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
        $stmt = $this->conn->prepare("select *
                                      from {$this->dbname}.users");
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        return $result;
    }

    public function getUsersByID($id)
    {
        $stmt = $this->conn->prepare("select * from {$this->dbname}.users WHERE id = ?");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        return $result;
    }

    public function searchUsersByID($id, $keyword)
    {
        $stmt = $this->conn->prepare("select * from {$this->dbname}.users WHERE id = ? AND (name LIKE ? or 
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
        $stmt = $this->conn->prepare("select *
                                      from {$this->dbname}.registrations
                                      join {$this->dbname}.classes
                                      on registrations.classID = classes.id
                                      WHERE registrations.userID =:id ORDER BY registrations.id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getUserRegistrationsForClass($id, $classID)
    {
        $stmt = $this->conn->prepare("select *
                                      from {$this->dbname}.registrations
                                      join {$this->dbname}.classes
                                      on registrations.classID = classes.id
                                      WHERE registrations.userID =:id AND registrations.classID=:classID");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':classID', $classID);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
        return $result;
    }

    public function getRegisteredUsers($classID)
    {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM {$this->dbname}.users
            INNER JOIN {$this->dbname}.registrations
            ON {$this->dbname}.registrations.userID={$this->dbname}.users.id
            AND {$this->dbname}.registrations.classID=:class limit 5;"
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
            FROM {$this->dbname}.users
            INNER JOIN {$this->dbname}.registrations
            ON {$this->dbname}.registrations.userID={$this->dbname}.users.id
            AND {$this->dbname}.registrations.classID=:class WHERE {$this->dbname}.users.id LIKE :id or {$this->dbname}.users.email LIKE :email
            OR {$this->dbname}.users.name LIKE :username or {$this->dbname}.users.gender LIKE :gender limit 5;"
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
 				Select * FROM {$this->dbname}.users
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
                    insert into {$this->dbname}.users
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
 				update {$this->dbname}.users
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
                    insert into {$this->dbname}.users
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
        $stmt = $this->conn->prepare("delete from {$this->dbname}.users WHERE id = ?");
        $stmt->bindValue(1,$id);
        $stmt->execute();
    }

    public function unregisterClass($id, $classID)
    {
        if($this->getUserRegistrationsForClass($id,$classID)) {
            $stmt = $this->conn->prepare("delete from {$this->dbname}.registrations
                                      WHERE userID =:userID and classID =:classID");
            $stmt->bindParam(':userID', $id);
            $stmt->bindParam(':classID', $classID);
            $stmt->execute();

            $result['success'] = true;
            $result['msg'] = "Unregistered!";
            return $result;
        }
        else{
            $result['success'] = false;
            $result['msg'] = "You're not Registered for This Class anyway";
            return $result;
        }
    }

    public function registerClass($id, $classID)
    {
//        return json_encode($this->getUserRegistrationsForClass($id,$classID));
        if(!$this->getUserRegistrationsForClass($id,$classID)) {
            try {
                $stmt = $this->conn->prepare("insert into {$this->dbname}.registrations
                                            (userID, classID) VALUES  (?, ?)");
                $stmt->bindValue(1, $id);
                $stmt->bindValue(2, $classID);
                $stmt->execute();

                $result['success'] = true;
                $result['msg'] = "Registered!";
                return $result;
            } catch (Exception $e) {

                $result['success'] = false;
                $result['msg'] = "Could Not Register for Class!";
                return $result;
            }
        }
        else {
            $result['success'] = false;
            $result['msg'] = "Already Registered For This Class";
            return $result;
        }
    }

    public function getUserProgramRequests($id)
    {
        $stmt = $this->conn->prepare("select *
          from {$this->dbname}.program_requests
          WHERE program_requests.userID =:id ORDER BY program_requests.date;");

        $stmt->bindParam(':id', $id);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    /*******GET USER LOGS FOR USER STATS*********/



    /** Logs **/

    public function getUsersLogin(){

        $stmt = $this->conn->prepare("select * from {$this->dbname}.logs WHERE logout IS NULL ");

        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;

    }

    public function getUsersLogs(){

        $stmt = $this->conn->prepare("select * from {$this->dbname}.logs
                                      join {$this->dbname}.users
                                      on logs.userID = users.id");

        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        return $result;

    }

    public function getLogsByKeyword($keyword){

        $stmt = $this->conn->prepare("select * from {$this->dbname}.logs
                                      join {$this->dbname}.users
                                      on logs.userID = users.id
                                      WHERE DATE (logs.login) like :date or TIME (logs.login) like :time or
                                      HOUR (logs.login) =:hour or
                                      MONTH (logs.login) like :month or DAYOFWEEK (logs.login) like :day or
                                      YEAR (logs.login) like :year or users.name like :name or 
                                      users.email like :email or MONTHNAME (logs.login) like :monthName");

        $stmt->bindParam(':date', $keyword);
        $stmt->bindParam(':time', $keyword);
        $stmt->bindParam(':hour', $keyword);
        $stmt->bindParam(':name', $keyword);
        $stmt->bindParam(':email', $keyword);
        $stmt->bindParam(':month', $keyword);
        $stmt->bindParam(':day', $keyword);
        $stmt->bindParam(':month', $keyword);
        $stmt->bindParam(':monthName', $keyword);
        $stmt->bindParam(':year', $keyword);

        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        return $result;

    }

    public function getRealtimeLogs(){

        $stmt = $this->conn->prepare("select DISTINCT (userID) from {$this->dbname}.logs WHERE logout is NULL");

        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;

    }

    public function userInGym($id){

        $stmt = $this->conn->prepare("select *
                                      from {$this->dbname}.logs
                                      WHERE logs.userID =:id and logs.logout is NULL limit 1");

        $stmt->bindParam(':id', $id);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();
        return $result;

    }


    /*******STATS******/

    public function getUsersLogsYears(){

        $stmt = $this->conn->prepare("select YEAR (login) from {$this->dbname}.logs");
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;

    }

    public function getUsersLogsMonths(){

        $stmt = $this->conn->prepare("
            SELECT MONTH({$this->dbname}.logs.login)
            FROM {$this->dbname}.users
            INNER JOIN {$this->dbname}.logs
            ON {$this->dbname}.logs.userID={$this->dbname}.users.id;"
        );

        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getUsersLogsDays(){

        $stmt = $this->conn->prepare("select DAYOFWEEK (login) from {$this->dbname}.logs");
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;

    }

    public function getUsersLogsHours(){

        $stmt = $this->conn->prepare("select HOUR (login) from {$this->dbname}.logs");
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;

    }

    public function getUsersLogsYearsFilter(){

        $statement = "
            SELECT YEAR ({$this->dbname}.logs.login)
            FROM {$this->dbname}.users
            INNER JOIN {$this->dbname}.logs
            ON {$this->dbname}.logs.userID={$this->dbname}.users.id";

        $gender = $_POST['gender'];
        if($gender == 'b'){
            $gender="";
        }

        if (!empty($gender)){
            $statement = $statement . " AND {$this->dbname}.users.gender=:gender";
        }

        if (isset($_POST['ageUpper']) && isset($_POST['ageLower'])){
            $statement = $statement . " AND {$this->dbname}.users.birthDate BETWEEN :down AND :up";
        }

        if ($_POST['student']){
            $statement = $statement . " AND {$this->dbname}.users.student=:student ";
        }

        if ($_POST['staff']){
            $statement = $statement . " AND {$this->dbname}.users.staff=:staff ";
        }

        if ($_POST['alumni']){
            $statement = $statement . " AND {$this->dbname}.users.alumni=:alumni ";
        }

        if ($_POST['faculty']){
            $statement = $statement . " AND {$this->dbname}.users.faculty=:faculty ";
        }

        if ($_POST['admin']){
            $statement = $statement . " AND {$this->dbname}.users.admin=:admin ";
        }

        if ($_POST['external']){
            $statement = $statement . " AND {$this->dbname}.users.external=:external ";
        }


        $stmt = $this->conn->prepare($statement);

        if (!empty($gender)) {
            $stmt->bindParam(':gender', $gender);
        }
        if (isset($_POST['ageUpper']) && isset($_POST['ageLower'])){
            $stmt->bindParam(':up', $_POST['ageUpper']);
            $stmt->bindParam(':down', $_POST['ageLower']);
        }


        if ($_POST['student']) {
            $stmt->bindValue(':student', $_POST['student']);
        }
        if ($_POST['staff']) {
            $stmt->bindValue(':staff', $_POST['staff']);
        }
        if ($_POST['alumni']) {
            $stmt->bindValue(':alumni', $_POST['alumni']);
        }
        if ($_POST['faculty']) {
            $stmt->bindValue(':faculty', $_POST['faculty']);
        }
        if ($_POST['admin']) {
            $stmt->bindValue(':admin', $_POST['admin']);
        }
        if ($_POST['external']) {
            $stmt->bindValue(':external', $_POST['external']);
        }

        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getUsersLogsMonthsFilter(){

        $statement = "
            SELECT MONTH({$this->dbname}.logs.login)
            FROM {$this->dbname}.users
            INNER JOIN {$this->dbname}.logs
            ON {$this->dbname}.logs.userID={$this->dbname}.users.id";

        $gender = $_POST['gender'];
        if($gender == 'b'){
            $gender="";
        }

        if (!empty($gender)){
            $statement = $statement . " AND {$this->dbname}.users.gender=:gender";
        }

        if (isset($_POST['ageUpper']) && isset($_POST['ageLower'])){
            $statement = $statement . " AND {$this->dbname}.users.birthDate BETWEEN :down AND :up";
        }

        if ($_POST['student']){
            $statement = $statement . " AND {$this->dbname}.users.student=:student ";
        }

        if ($_POST['staff']){
            $statement = $statement . " AND {$this->dbname}.users.staff=:staff ";
        }

        if ($_POST['alumni']){
            $statement = $statement . " AND {$this->dbname}.users.alumni=:alumni ";
        }

        if ($_POST['faculty']){
            $statement = $statement . " AND {$this->dbname}.users.faculty=:faculty ";
        }

        if ($_POST['admin']){
            $statement = $statement . " AND {$this->dbname}.users.admin=:admin ";
        }

        if ($_POST['external']){
            $statement = $statement . " AND {$this->dbname}.users.external=:external ";
        }


        $stmt = $this->conn->prepare($statement);

        if (!empty($gender)) {
            $stmt->bindParam(':gender', $gender);
        }
        if (isset($_POST['ageUpper']) && isset($_POST['ageLower'])){
            $stmt->bindParam(':up', $_POST['ageUpper']);
            $stmt->bindParam(':down', $_POST['ageLower']);
        }

        if ($_POST['student']) {
            $stmt->bindValue(':student', $_POST['student']);
        }
        if ($_POST['staff']) {
            $stmt->bindValue(':staff', $_POST['staff']);
        }
        if ($_POST['alumni']) {
            $stmt->bindValue(':alumni', $_POST['alumni']);
        }
        if ($_POST['faculty']) {
            $stmt->bindValue(':faculty', $_POST['faculty']);
        }
        if ($_POST['admin']) {
            $stmt->bindValue(':admin', $_POST['admin']);
        }
        if ($_POST['external']) {
            $stmt->bindValue(':external', $_POST['external']);
        }

        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getUsersLogsDaysFilter(){

        $statement = "SELECT DAYOFWEEK ({$this->dbname}.logs.login)
            FROM {$this->dbname}.users
            INNER JOIN {$this->dbname}.logs
            ON {$this->dbname}.logs.userID={$this->dbname}.users.id";

        $gender = $_POST['gender'];
        if($gender == 'b'){
            $gender="";
        }

        if (!empty($gender)){
            $statement = $statement . " AND {$this->dbname}.users.gender=:gender";
        }

        if (isset($_POST['ageUpper']) && isset($_POST['ageLower'])){
            $statement = $statement . " AND {$this->dbname}.users.birthDate BETWEEN :down AND :up";
        }

        if ($_POST['student']){
            $statement = $statement . " AND {$this->dbname}.users.student=:student ";
        }

        if ($_POST['staff']){
            $statement = $statement . " AND {$this->dbname}.users.staff=:staff ";
        }

        if ($_POST['alumni']){
            $statement = $statement . " AND {$this->dbname}.users.alumni=:alumni ";
        }

        if ($_POST['faculty']){
            $statement = $statement . " AND {$this->dbname}.users.faculty=:faculty ";
        }

        if ($_POST['admin']){
            $statement = $statement . " AND {$this->dbname}.users.admin=:admin ";
        }

        if ($_POST['external']){
            $statement = $statement . " AND {$this->dbname}.users.external=:external ";
        }


        $stmt = $this->conn->prepare($statement);

        if (!empty($gender)) {
            $stmt->bindParam(':gender', $gender);
        }
        if (isset($_POST['ageUpper']) && isset($_POST['ageLower'])){
            $stmt->bindParam(':up', $_POST['ageUpper']);
            $stmt->bindParam(':down', $_POST['ageLower']);
        }


        if ($_POST['student']) {
            $stmt->bindValue(':student', $_POST['student']);
        }
        if ($_POST['staff']) {
            $stmt->bindValue(':staff', $_POST['staff']);
        }
        if ($_POST['alumni']) {
            $stmt->bindValue(':alumni', $_POST['alumni']);
        }
        if ($_POST['faculty']) {
            $stmt->bindValue(':faculty', $_POST['faculty']);
        }
        if ($_POST['admin']) {
            $stmt->bindValue(':admin', $_POST['admin']);
        }
        if ($_POST['external']) {
            $stmt->bindValue(':external', $_POST['external']);
        }

        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getUsersLogsHoursFilter(){

        $statement = "SELECT HOUR ({$this->dbname}.logs.login)
            FROM {$this->dbname}.users
            INNER JOIN {$this->dbname}.logs
            ON {$this->dbname}.logs.userID={$this->dbname}.users.id";

        $gender = $_POST['gender'];
        if($gender == 'b'){
            $gender="";
        }

        if (!empty($gender)){
            $statement = $statement . " AND {$this->dbname}.users.gender=:gender";
        }

        if (isset($_POST['ageUpper']) && isset($_POST['ageLower'])){
            $statement = $statement . " AND {$this->dbname}.users.birthDate BETWEEN :down AND :up";
        }


        if ($_POST['student']){
            $statement = $statement . " AND {$this->dbname}.users.student=:student ";
        }

        if ($_POST['staff']){
            $statement = $statement . " AND {$this->dbname}.users.staff=:staff ";
        }

        if ($_POST['alumni']){
            $statement = $statement . " AND {$this->dbname}.users.alumni=:alumni ";
        }

        if ($_POST['faculty']){
            $statement = $statement . " AND {$this->dbname}.users.faculty=:faculty ";
        }

        if ($_POST['admin']){
            $statement = $statement . " AND {$this->dbname}.users.admin=:admin ";
        }

        if ($_POST['external']){
            $statement = $statement . " AND {$this->dbname}.users.external=:external ";
        }


        $stmt = $this->conn->prepare($statement);

        if (!empty($gender)) {
            $stmt->bindParam(':gender', $gender);
        }
        if (isset($_POST['ageUpper']) && isset($_POST['ageLower'])){
            $stmt->bindParam(':up', $_POST['ageUpper']);
            $stmt->bindParam(':down', $_POST['ageLower']);
        }


        if ($_POST['student']) {
            $stmt->bindValue(':student', $_POST['student']);
        }
        if ($_POST['staff']) {
            $stmt->bindValue(':staff', $_POST['staff']);
        }
        if ($_POST['alumni']) {
            $stmt->bindValue(':alumni', $_POST['alumni']);
        }
        if ($_POST['faculty']) {
            $stmt->bindValue(':faculty', $_POST['faculty']);
        }
        if ($_POST['admin']) {
            $stmt->bindValue(':admin', $_POST['admin']);
        }
        if ($_POST['external']) {
            $stmt->bindValue(':external', $_POST['external']);
        }

        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function getUsersAge(){

        $stmt = $this->conn->prepare("select TIMESTAMPDIFF(YEAR,birthDate,CURDATE()) AS age from {$this->dbname}.users");
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;

    }

    public function getUsersGender(){

        $stmt = $this->conn->prepare("select gender from {$this->dbname}.users");
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;

    }


    /******RECORDING USER LOGS WITH NFC********/

    public function signout($id, $date)
    {
        $stmt = $this->conn->prepare("update {$this->dbname}.logs set logout = ? WHERE userID = ? AND logout IS NULL ");

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
        $stmt = $this->conn->prepare("insert into {$this->dbname}.logs (userID, login) VALUES  (?,?)  ");

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
        $stmt = $this->conn->prepare("select * from {$this->dbname}.logs WHERE userID = ? AND MONTH(login) = 10 AND YEAR(login) = 2016");
        $stmt->bindValue(1, $id);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function createProgramRequest ($data)
    {
        $result = [];
        $userID = $_SESSION['user']['id'];
        $monday = $data['monday'] ?? 0;
        $tuesday = $data['tuesday'] ?? 0;
        $wednesday = $data['wednesday'] ?? 0;
        $thursday = $data['thursday'] ?? 0;
        $friday = $data['friday'] ?? 0;
        $saturday = $data['saturday'] ?? 0;
        $sunday = $data['sunday'] ?? 0;
        $goal = json_decode($_POST['goal']);


        try
        {
            $stmt = $this->conn->prepare("
                INSERT INTO {$this->dbname}.program_requests 
                (`userID`, `height`, `weight`, `pastExercise`, `currentlyExercising`, `currentExercisingIntensity`, `activities`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `sunday`,
                 `developMuscleStrength`, `rehabilitateInjury`, `overallFitness`, `loseBodyFat`, `startExerciseProgram`, `designAdvanceProgram`, `increaseFlexibility`, `sportsSpecificTraining`,
                 `increaseMuscleSize`, `cardioExercise`,`comments`) 
                VALUES (:userID, :height, :weight, :pastExercise, :currentlyExercising, :currentExercisingIntensity, :activities, :monday, :tuesday, :wednesday, :thursday, :friday, :saturday, :sunday,
                 :developMuscleStrength, :rehabilitateInjury, :overallFitness, :loseBodyFat,:startExerciseProgram, :designAdvanceProgram, :increaseFlexibility, :sportsSpecificTraining,
                 :increaseMuscleSize, :cardioExercise, :comments );
            ");
            $stmt->bindParam(':userID', $userID);
            $stmt->bindValue(':height', $data['height'] ?? 0);
            $stmt->bindValue(':weight', $data['weight'] ?? 0);
            $stmt->bindParam(':pastExercise', $data['pastExercise']);
            $stmt->bindParam(':currentlyExercising', $data['currentlyExercising']);
            $stmt->bindValue(':currentExercisingIntensity', $data['currentExercisingIntensity']??0);
            $stmt->bindValue(':activities', $data['activities'] ?? "");
            $stmt->bindValue(':monday', (int) $monday ?? 0);
            $stmt->bindValue(':tuesday', (int) $tuesday ?? 0);
            $stmt->bindValue(':wednesday', (int) $wednesday ?? 0);
            $stmt->bindValue(':thursday', (int) $thursday ?? 0);
            $stmt->bindValue(':friday', (int) $friday ?? 0);
            $stmt->bindValue(':saturday', (int) $saturday ?? 0);
            $stmt->bindValue(':sunday', (int) $sunday ?? 0);
            $stmt->bindValue(':comments', $data['comments'] ?? "");

            foreach($goal as $key => $value)
            {
                $stmt->bindValue(":".$value, (int)$key ?? 0);
            }

            $stmt->execute();

            $result['success'] = 1;
            $result['message'] = "Request Successfully Submitted!";
        }
        catch (PDOException $exception)
        {
            ddd($exception->getMessage());
            $result['success'] = 0;
            $result['message'] = $exception->getMessage();
        }

        return $result;
    }

    /** Trainer **/
    public function getPendingProgramRequests()
    {
        $stmt = $this->conn->prepare("select *
          from {$this->dbname}.users
          join {$this->dbname}.program_requests
          on users.id = program_requests.userID
          WHERE program_requests.trainerResponse = 0;");
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        return $result;
    }

    public function trainerResponse($id, $trainerComments)
    {
        try
        {
            $stmt = $this->conn->prepare("UPDATE {$this->dbname}.program_requests SET trainerResponse = 1, trainerComments=:trainerComments WHERE id=:id");
            $stmt->bindParam(':trainerComments', $trainerComments);
            $stmt->bindParam(':id', $id);

            $stmt->execute();

            $result['success'] = true;
            $result['message'] = "Request Successfully Submitted!";
        }
        catch (PDOException $exception)
        {
            ddd($exception->getMessage());
            $result['success'] = false;
            $result['message'] = $exception->getMessage();
        }
        return $result;
    }

}