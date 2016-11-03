<?php
namespace Nourhan\Controllers;


use Nourhan\Database\DB;
use Nourhan\Services\Upload;
use Nourhan\ReCaptcha;
use Nourhan\Services\ChangeCarousel;


class MainController extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	/*****HOME*****/
	public function index()
	{
		echo $this->twig->render('index.twig');
	}

	public function calendar()
	{
		echo $this->twig->render('calendar.twig');
	}

	public function fitnessProgram()
	{
		$db = new DB();
		$classes = $db->getClasses();
//        $days[]= array();
//        var_dump($classes);
		foreach ($classes as $key => $class)
		{
//            var_dump($class);

			$class['days'] = array();

			if ($class['monday'])
			{
				$classes[ $key ]['days'][] = "1";
//                $classes[$key]['days'][]="monday";
//                echo $class['monday'];
			}
			if ($class['tuesday'])
			{
				$classes[ $key ]['days'][] = "2";
//                $classes[$key]['days'][]="tuesday";
			}
			if ($class['wednesday'])
			{
				$classes[ $key ]['days'][] = "3";
//                $classes[$key]['days'][]="wednesday";
			}
			if ($class['thursday'])
			{
				$classes[ $key ]['days'][] = "4";
//                $classes[$key]['days'][]="thursday";
			}
			if ($class['friday'])
			{
				$classes[ $key ]['days'][] = "5";
//                $classes[$key]['days'][]="friday";
			}
		}
//    var_dump($classes[0]['days']);
//        if ($classes[])

		echo $this->twig->render('fitnessProgram.twig', array('classes' => $classes));
	}

	/*****CUSTOMER*****/
	public function profileStats()
	{
		$array = array("January", "January", "January", "February", "February", "March");
		$vals = array_count_values($array);
//        var_dump($vals);
		for ($x = 0; $x < count($array);)
		{

			$x++;
		}

		echo $this->twig->render('customer/profile.twig', array('vals' => $vals));
	}

	public function login()
	{
		echo $this->twig->render('login.twig');
	}

	public function verifyResponse()
	{
// your secret key
		$secret = "6Lel3ggUAAAAACT3Xz7dfhUhvFGALwDrXgtwFeON";
// empty response
		$response = null;
// check secret key
		$reCaptcha = new ReCaptcha\ReCaptcha($secret);

// if submitted check response
		if ($_POST["g-recaptcha-response"])
		{
			$response = $reCaptcha->verifyResponse(
				$_SERVER["REMOTE_ADDR"],
				$_POST["g-recaptcha-response"]
			);
		}

		if ($response != null && $response->success)
		{
			//REDIRECT TO PROFILE
//            echo "Hi " . $_POST["username"] . " (" . $_POST["password"] . "), thanks for submitting the form!";
			return true;

		}
		else
		{
			return false;
			//REDIRECT TO ERROR'
//            echo "not submitted";
			return false;
		}
	}

	public function userLogin()
	{

		if (isset($_SESSION['user']))
		{

			header('Location: /profile');
		}
		else
		{

			if (($this->verifyResponse()))
			{

				$db = new DB();
				$user = $db->getUser($_POST["username"], $_POST["password"]);

				if (empty($user))
				{
					echo "user not found";
				}
				else
				{
//                $this->profileStats();
					$_SESSION['user'] = $user;
//                    echo $this->twig->render('customer/profile.twig');
					header('Location: /profile');
				}


			}
		}
	}

	public function logout()
	{
		unset($_SESSION['user']);
//        echo $this->twig->render('login.twig');
		header('Location: /login');
	}


	public function profile()
	{
		echo $this->twig->render('customer/profile.twig');
	}

	/***********ADMIN************/

	////////EDIT CLASS SCHEDULE

	public function editSchedule()
	{
		$DB = new DB();
		$classes = $DB->getClasses();
		$allInstructor = $DB->getInstructors();

		foreach ($classes as $key => $class)
		{

			$temp = ($class['currentCapacity'] * 100) / $class['capacity'];
			$temp2 = $class['currentCapacity'];

			$classes[ $key ]['currentCapacityPercentage'] = $temp;
			$classes[ $key ]['currentCapacity'] = $temp2;

			$class['days'] = array();

			if ($class['monday'])
			{
				$classes[ $key ]['days']['monday'] = "1";
//                $classes[$key]['days'][]="monday";
//                echo $class['monday'];
			}
			else
			{
				$classes[ $key ]['days']['monday'] = "0";
			}
			if ($class['tuesday'])
			{
				$classes[ $key ]['days']['tuesday'] = "1";
//                $classes[$key]['days'][]="tuesday";
			}
			else
			{
				$classes[ $key ]['days']['tuesday'] = "0";
			}
			if ($class['wednesday'])
			{
				$classes[ $key ]['days']['wednesday'] = "1";
//                $classes[$key]['days'][]="wednesday";
			}
			else
			{
				$classes[ $key ]['days']['wednesday'] = "0";
			}
			if ($class['thursday'])
			{
				$classes[ $key ]['days']['thursday'] = "1";
//                $classes[$key]['days'][]="thursday";
			}
			else
			{
				$classes[ $key ]['days']['thursday'] = "0";
			}
			if ($class['friday'])
			{
				$classes[ $key ]['days']['friday'] = "1";
//                $classes[$key]['days'][]="friday";
			}
			else
			{
				$classes[ $key ]['days']['friday'] = "0";
			}
		}


		$instructors = array();

		foreach ($classes as $class)
		{

			$id = "" . $class["instructorID"] . "";
			$instructors[] = $DB->getInstructor($id);

		}

		echo $this->twig->render('admin/editSchedule.twig',
			array('classes' => $classes, 'instructors' => $instructors, 'allInstructors' => $allInstructor));
	}

	public function deleteClass()
	{
		$db = new DB();
		$db->deleteClass($_POST['id']);

	}

	public function addClass()
	{

		$db = new DB();
		$test = $db->addClass($_POST['name'], $_POST['duration'], $_POST['instructorID'], $_POST['startTime'],
			$_POST['period'],
			$_POST['capacity'], $_POST['location'], (int)in_array('monday', $_POST['days']),
			(int)in_array('tuesday', $_POST['days']), (int)in_array('wednesday', $_POST['days']),
			(int)in_array('thursday', $_POST['days']), (int)in_array('friday', $_POST['days']));


		if ($test)
		{

			header('Location: /editschedule');

		}
		else
		{
			//ERROR
		}

	}

	public function searchClasses()
	{
		$db = new DB();

		$result = $db->searchClasses($_GET['keyword']);

		$allInstructor = $db->getInstructors();

		foreach ($result as $key => $class)
		{

			$temp = ($class['currentCapacity'] * 100) / $class['capacity'];
			$temp2 = $class['currentCapacity'];

			$result[ $key ]['currentCapacityPercentage'] = $temp;
			$result[ $key ]['currentCapacity'] = $temp2;
		}


		$instructors = array();

		foreach ($result as $class)
		{

			$id = "" . $class["instructorID"] . "";
			$instructors[] = $db->getInstructor($id);

		}

		echo $this->twig->render('admin/editSchedule.twig',
			array('classes' => $result, 'instructors' => $instructors, 'allInstructors' => $allInstructor));


	}

	public function updateClass()
	{
		$DB = new DB();
		if ($DB->updateClass($_POST['id'], $_POST['duration'], $_POST['startTime'], $_POST['capacity'],
			$_POST['instructor'])
		)
		{

			$this->editSchedule();
		}


	}

	////////EDIT USERS


	public function editUsers()
	{
		$db = new DB();

		$users = $db->getUsers();

		echo $this->twig->render('admin/editUsers.twig', array('users' => $users));

	}

//    public function editUsers()
//    {
//        $DB = new DB();
//        $users = $DB->getusers();
//        $allInstructor = $DB->getInstructors();
//
//        foreach ($classes as $key=>$class ){
//
//            $temp = ($class['currentCapacity']*100)/$class['capacity'];
//            $temp2 = $class['currentCapacity'];
//
//            $classes[$key]['currentCapacityPercentage'] = $temp;
//            $classes[$key]['currentCapacity'] = $temp2;
//
//            $class['days']=array();
//
//            if($class['monday']){
//                $classes[$key]['days']['monday']="1";
////                $classes[$key]['days'][]="monday";
////                echo $class['monday'];
//            } else {
//                $classes[$key]['days']['monday']="0";
//            }
//            if($class['tuesday']){
//                $classes[$key]['days']['tuesday']="1";
////                $classes[$key]['days'][]="tuesday";
//            }else {
//                $classes[$key]['days']['tuesday']="0";
//            }
//            if($class['wednesday']){
//                $classes[$key]['days']['wednesday']="1";
////                $classes[$key]['days'][]="wednesday";
//            }else {
//                $classes[$key]['days']['wednesday']="0";
//            }
//            if($class['thursday']){
//                $classes[$key]['days']['thursday']="1";
////                $classes[$key]['days'][]="thursday";
//            }else {
//                $classes[$key]['days']['thursday']="0";
//            }
//            if($class['friday']){
//                $classes[$key]['days']['friday']="1";
////                $classes[$key]['days'][]="friday";
//            }else {
//                $classes[$key]['days']['friday']="0";
//            }
//        }
//
//
//        $instructors = array();
//
//        foreach ($classes as $class ){
//
//            $id = "".$class["instructorID"]."";
//            $instructors[]= $DB->getInstructor($id);
//
//        }
//
//        echo $this->twig->render('admin/editSchedule.twig', array('classes'=> $classes, 'instructors'=> $instructors, 'allInstructors'=>$allInstructor));
//    }


	public function antony()
	{
		$db = new DB();

		$encodedUsers = file_get_contents(__DIR__.'/../storage/users.json');

		$decodedUsers = json_decode($encodedUsers);

//		var_dump($decodedUsers); // showcase with and without associative array

		foreach ($decodedUsers as $user)
		{
			$db->addUser($user);
		}


//		$users = [
//			[
//				'name' => 'antony',
//				'age'  => '26',
//				'major'=> 'IT'
//			],
//			[
//				'name' => 'nourhan',
//				'age'  => '23',
//				'major'=> 'philosophy'
//			]
//		];


		/*********************|
		|                    *|
		|  Helpful functions  |
		|                    *|
		|*********************/
		/** Get contents from a resource (file, webpage, etc. **/
		//file_get_contents(__DIR__ . '/../storage/users.json');

		/** Write contents to a file **/
		//file_put_contents(__DIR__ . '/../storage/users.json', $users);

		/** Encode into a JSON format **/
		//json_encode($variable);

		/** Decode from JSON format **/
		//json_decode($variable, [true]); //if 2nd argument true --> associative array


	}
}