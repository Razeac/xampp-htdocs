<?php
class pod_framework {
    public function __construct() {
        try { 
            $this->conn = new PDO("mysql:host=127.0.0.1;dbname=pod_attendance","root",""); 
        } catch (PDOException $e) { 
            echo "<script>alert(\"There was an error in our side, please stand by!\")</script>"; 
        }
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $reqType = stripslashes( $_POST["request_type"] );
        
		switch($reqType) {
			case "updatetime": $this->updatetime();
			break;
			case "client_ip": $this->client_ip();
			break;
			case "checkrefresh": $this->checkrefresh();
			break;
			case "checkclient": $this->checkclient();
			break;
			case "stoprefresh": $this->stoprefresh();
			break;
			case "student_id": $this->student_id();
			break;
			case "faculty_id": $this->faculty_id();
			break;
			case "get_websettingsinfo": $this->get_websettingsinfo();
			break;
		}
    }

    public function client_ip() {
        $name = $_POST["name"];
        $ip = $_POST["ip"];
        $zero = "0";
        $stmt = $this->conn->prepare("SELECT * FROM clients WHERE ip=:ip LIMIT 1");
        $stmt->execute( array(":ip" => $ip) );

        if ($stmt->rowCount() > 0) {
            echo "1";
        } else {
            $stmt2 = $this->conn->prepare("INSERT INTO clients (name,ip,refresh)
            VALUES (:name,:ip,:refresh)");
            $stmt2->bindparam(":name", $name);
            $stmt2->bindparam(":ip", $ip);
            $stmt2->bindparam(":refresh", $zero);
            $stmt2->execute();
            echo "success";
        }
    }

    public function checkclient() {
        $ip = $_POST["ip"];
        $stmt = $this->conn->prepare("SELECT * FROM clients WHERE ip=:ip LIMIT 1");
        $stmt->execute( array(":ip" => $ip) );

        if ($stmt->rowCount() > 0) {
            $a = "1"; //true
        } else {
            $a = "0"; //false
        }
        $this->output($a, NULL,NULL,NULL,NULL);
    }

    public function stoprefresh() {
        $ip = $_POST['ip'];
        $refresh = "0";

        $sql = "UPDATE clients SET refresh=:refresh WHERE ip=:ip";
		$stmt= $this->conn->prepare($sql);
		$stmt->execute(array(":refresh" => $refresh,":ip" => $ip) );
    }

    public function checkrefresh() {
        $ip = $_POST["ip"];
        $stmt = $this->conn->prepare("SELECT * FROM clients WHERE ip=:ip LIMIT 1");
        $stmt->execute( array(":ip" => $ip) );
        $data = $stmt->fetch();

        $a = $data['refresh'];
        $this->output($a, NULL,NULL,NULL,NULL);
    }

    public function faculty_id() {
        $faculty_id = stripslashes($_POST['faculty_id']);
        $hours = stripslashes($_POST['hours']);
        $minutes = stripslashes($_POST['minutes']);
        $seconds = stripslashes($_POST['seconds']);
        $median = stripslashes($_POST['median']);
        $todaydate = stripslashes($_POST['date']);

        $time = $hours.":".$minutes.":".$seconds." ".$median;


        //check if there is data or none
        $stmt = $this->conn->prepare("SELECT * FROM faculty WHERE faculty_id = :faculty_id LIMIT 1");
        $stmt->execute( array(":faculty_id" => $faculty_id) );
        $data = $stmt->fetch();

        $name = $data["name"];
        $faculty_img = $data["picture"].".jpg";

        $null = NULL;


        if ($stmt->rowCount() > 0) {

            if($median=="AM") {
                $stmt3 = $this->conn->prepare("SELECT * FROM faculty_attendance WHERE faculty_id=:faculty_id AND date = :date LIMIT 1");
                $stmt3->execute( array(":faculty_id" => $faculty_id,":date" => $todaydate) );
                $count = $stmt3->rowCount();

                if ($count > 0) {
                    //update to attendance AM_time_out
                    $sql = "UPDATE faculty_attendance SET am_time_out=NOW() WHERE faculty_id=:faculty_id AND date=:date";
                    $stmt5= $this->conn->prepare($sql);
                    $stmt5->execute(array(":faculty_id" => $faculty_id,":date" => $todaydate) );
    
                    $stmt3 = $this->conn->prepare("SELECT * FROM faculty_attendance f JOIN faculty t1 ON f.faculty_id = t1.faculty_id WHERE f.faculty_id=:faculty_id AND f.date = :date LIMIT 1");
                    $stmt3->execute( array(":faculty_id" => $faculty_id,":date" => $todaydate) );
                    $data_info = $stmt3->fetch();
    
                    $name = $data_info['name'];
                    $img = "pictures/faculty/".$data_info["picture"].".jpg";
                    $role = $data_info['role'];
                    $in = date('h:i:s A', strtotime($data_info['am_time_in']));
                    $out = date('h:i:s A', strtotime($data_info['am_time_out']));
                    $nout = "-------";
                    
                    $this->fac_output(
                        array(
                            'name' => $name,
                            'img' => $img,
                            'role' => $role,
                            'am_in' => $in,
                            'am_out' => $out,
                            'pm_in' => $nout,
                            'pm_out' => $nout,
                            'check' => "1"
                        )
                    );
    
                } else {
                    //insert to attendance AM_time_in
                    $stmt4 = $this->conn->prepare("INSERT INTO faculty_attendance (faculty_id,am_time_in,am_time_out,pm_time_in,pm_time_out,date)
                                                VALUES (:faculty_id,NOW(),NULL,NULL,NULL,NOW())");
    
                    $stmt4->bindparam(":faculty_id", $faculty_id);
                    $stmt4->execute();
    
                    $stmt3 = $this->conn->prepare("SELECT * FROM faculty_attendance f JOIN faculty t1 ON f.faculty_id = t1.faculty_id WHERE f.faculty_id=:faculty_id AND f.date = :date LIMIT 1");
                    $stmt3->execute( array(":faculty_id" => $faculty_id,":date" => $todaydate) );
                    $data_info = $stmt3->fetch();
    
                    $name = $data_info['name'];
                    $img = "pictures/faculty/".$data_info["picture"].".jpg";
                    $role = $data_info['role'];
                    $in = date('h:i:s A', strtotime($data_info['am_time_in']));
                    $out = "-------";
                    $this->fac_output(
                        array(
                            'name' => $name,
                            'img' => $img,
                            'role' => $role,
                            'am_in' => $in,
                            'am_out' => $out,
                            'pm_in' => $out,
                            'pm_out' => $out,
                            'check' => "1"
                        )
                    );
                }
            } else if($median=="PM") {

                $stmt3 = $this->conn->prepare("SELECT * FROM faculty_attendance WHERE faculty_id=:faculty_id AND date = :date AND pm_time_in IS NULL LIMIT 1");
                $stmt3->execute( array(":faculty_id" => $faculty_id,":date" => $todaydate) );
                $count = $stmt3->rowCount();

                if ($count > 0) {
                    
                    //insert to attendance PM_time_in
                    $sql = "UPDATE faculty_attendance SET pm_time_in=NOW() WHERE faculty_id=:faculty_id AND date=:date";
                    $stmt5= $this->conn->prepare($sql);
                    $stmt5->execute(array(":faculty_id" => $faculty_id,":date" => $todaydate) );
    
                    $stmt3 = $this->conn->prepare("SELECT * FROM faculty_attendance f JOIN faculty t1 ON f.faculty_id = t1.faculty_id WHERE f.faculty_id=:faculty_id AND f.date = :date LIMIT 1");
                    $stmt3->execute( array(":faculty_id" => $faculty_id,":date" => $todaydate) );
                    $data_info = $stmt3->fetch();
    
                    $name = $data_info['name'];
                    $img = "pictures/faculty/".$data_info["picture"].".jpg";
                    $role = $data_info['role'];

                    $intemp = date('h:i:s A', strtotime($data_info['am_time_in']));
                    if($intemp == "01:00:00 AM") {
                        $in="-------";
                    } else {
                        $in=$intemp;
                    }
                    $out_temp = date('h:i:s A', strtotime($data_info['am_time_out']));
                    if($out_temp == "01:00:00 AM") {
                        $out="-------";
                    } else {
                        $out=$out_temp;
                    }
                    $inpmtemp = date('h:i:s A', strtotime($data_info['pm_time_in']));
                    if($inpmtemp == "01:00:00 AM") {
                        $inpm="-------";
                    } else {
                        $inpm=$inpmtemp;
                    }
                    $outpmtemp = date('h:i:s A', strtotime($data_info['pm_time_out']));
                    if($outpmtemp == "01:00:00 AM" || $outpmtemp == "08:00:00 AM") {
                        $outpm="-------";
                    } else {
                        $outpm=$outpmtemp;
                    }
                    
                    $this->fac_output(
                        array(
                            'name' => $name,
                            'img' => $img,
                            'role' => $role,
                            'am_in' => $in,
                            'am_out' => $out,
                            'pm_in' => $inpm,
                            'pm_out' => $outpm,
                            'check' => "1"
                        )
                    );
    
                } else {
                    $stmt31 = $this->conn->prepare("SELECT * FROM faculty_attendance WHERE faculty_id=:faculty_id AND date = :date LIMIT 1");
                    $stmt31->execute( array(":faculty_id" => $faculty_id,":date" => $todaydate) );
                    $count2 = $stmt31->rowCount();

                    if($count2 > 0) {
                        //update to attendance PM_time_out
                        $sql = "UPDATE faculty_attendance SET pm_time_out=NOW() WHERE faculty_id=:faculty_id AND date=:date";
                        $stmt5= $this->conn->prepare($sql);
                        $stmt5->execute(array(":faculty_id" => $faculty_id,":date" => $todaydate) );
        
                        $stmt3 = $this->conn->prepare("SELECT * FROM faculty_attendance f JOIN faculty t1 ON f.faculty_id = t1.faculty_id WHERE f.faculty_id=:faculty_id AND f.date = :date LIMIT 1");
                        $stmt3->execute( array(":faculty_id" => $faculty_id,":date" => $todaydate) );
                        $data_info = $stmt3->fetch();
        
                        $name = $data_info['name'];
                        $img = "pictures/faculty/".$data_info["picture"].".jpg";
                        $role = $data_info['role'];
                        
                        $intemp = date('h:i:s A', strtotime($data_info['am_time_in']));
                        if(!$data_info['am_time_in']) {
                            $in="-------";
                        } else {
                            $in=$intemp;
                        }
                        $out_temp = date('h:i:s A', strtotime($data_info['am_time_out']));
                        if(!$data_info['am_time_out']) {
                            $out="-------";
                        } else {
                            $out=$out_temp;
                        }
                        $inpmtemp = date('h:i:s A', strtotime($data_info['pm_time_in']));
                        if(!$data_info['pm_time_in']) {
                            $inpm="-------";
                        } else {
                            $inpm=$inpmtemp;
                        }
                        $outpmtemp = date('h:i:s A', strtotime($data_info['pm_time_out']));
                        if(!$data_info['pm_time_out']) {
                            $outpm="-------";
                        } else {
                            $outpm=$outpmtemp;
                        }
                        
                        
                        $this->fac_output(
                            array(
                                'name' => $name,
                                'img' => $img,
                                'role' => $role,
                                'am_in' => $in,
                                'am_out' => $out,
                                'pm_in' => $inpm,
                                'pm_out' => $outpm,
                                'check' => "1"
                            )
                        );
                    } else {
                        //insert to attendance AM_time_in
                        $stmt4 = $this->conn->prepare("INSERT INTO faculty_attendance (faculty_id,am_time_in,am_time_out,pm_time_in,pm_time_out,date)
                        VALUES (:faculty_id,NULL,NULL,NOW(),NULL,NOW())");

                        $stmt4->bindparam(":faculty_id", $faculty_id);
                        $stmt4->execute();

                        $stmt3 = $this->conn->prepare("SELECT * FROM faculty_attendance f JOIN faculty t1 ON f.faculty_id = t1.faculty_id WHERE f.faculty_id=:faculty_id AND f.date = :date LIMIT 1");
                        $stmt3->execute( array(":faculty_id" => $faculty_id,":date" => $todaydate) );
                        $data_info = $stmt3->fetch();

                        $name = $data_info['name'];
                        $img = "pictures/faculty/".$data_info["picture"].".jpg";
                        $role = $data_info['role'];

                        $intemp = date('h:i:s A', strtotime($data_info['am_time_in']));
                        if(!$data_info['am_time_in']) {
                            $in="-------";
                        } else {
                            $in=$intemp;
                        }
                        $out_temp = date('h:i:s A', strtotime($data_info['am_time_out']));
                        if(!$data_info['am_time_out']) {
                            $out="-------";
                        } else {
                            $out=$out_temp;
                        }
                        $inpmtemp = date('h:i:s A', strtotime($data_info['pm_time_in']));
                        if(!$data_info['pm_time_in']) {
                            $inpm="-------";
                        } else {
                            $inpm=$inpmtemp;
                        }
                        $outpmtemp = date('h:i:s A', strtotime($data_info['pm_time_out']));
                        if(!$data_info['pm_time_out']) {
                            $outpm="-------";
                        } else {
                            $outpm=$outpmtemp;
                        }

                        $this->fac_output(
                            array(
                            'name' => $name,
                            'img' => $img,
                            'role' => $role,
                            'am_in' => $in,
                            'am_out' => $out,
                            'pm_in' => $inpm,
                            'pm_out' => $outpm,
                            'check' => "1"
                            )
                        );
                    }
                }
            }
        } else {
            $none = "-------";
            $nonedata = $faculty_id." was Not Found";
            $noneimg = "img/none.jpg";
            
            $this->fac_output(
                array(
                    'name' => $nonedata,
                    'img' => $noneimg,
                    'role' => $none,
                    'am_in' => $none,
                    'am_out' => $none,
                    'pm_in' => $none,
                    'pm_out' => $none,
                    'check' => "0"
                )
            ); 
        }
    }

    public function student_id() {
        $student_id = stripslashes($_POST['student_id']);
        $median = stripslashes($_POST['median']);
        $remarks = stripslashes($_POST['remarks']);
        $slip = stripslashes($_POST['slip']);
        $time_post = stripslashes($_POST['time']);
        $todaydate = stripslashes($_POST['date']);

        //check if there is data or none
        $stmt = $this->conn->prepare("SELECT * FROM student_info WHERE student_id = :student_id LIMIT 1");
        $stmt->execute( array(":student_id" => $student_id) );
        $data = $stmt->fetch();
        
        $name = $data["first_name"] . " " . $data["last_name"];
        $grade = $data["grade"];
        $section = $data["section"];
        $grade_section = "Grade ".$grade." - ".$section;
        $student_img = "resources/students/".$data["photo"].".JPG";
        $null = NULL;

        if ($stmt->rowCount() > 0) {
            //check if AM or PM
            if($median=="AM") {
                //select stu id and date today in attendance if ady logged in AM
                $log= "AM";
                $stmt2 = $this->conn->prepare("SELECT * FROM idtag WHERE student_id = :student_id AND date = :date AND log=:log LIMIT 1");
                $stmt2->execute( array(":student_id" => $student_id,":date" => $todaydate,":log" => $log) );
                $info = $stmt2->fetch();

                if ($stmt2->rowCount() > 0) {
                    $time = $info["time"] . " AM";
                    $name = "Already Logged In - AM";
                    $grade_section = "Already Logged In - AM";
                    $remarks = "Already Logged In - AM";
                    $student_img = "img/none.jpg";
                } else {
                    //insert to attendance
                    $time = $time_post . " AM";
                    $stmt4 = $this->conn->prepare("INSERT INTO idtag (student_id,time,log,date)
		        				                VALUES (:student_id,:time,:log,:date)");

		        	$stmt4->bindparam(":student_id", $student_id);
		        	$stmt4->bindparam(":time", $time_post);
		        	$stmt4->bindparam(":log", $log);
		        	$stmt4->bindparam(":date", $todaydate);
                    $stmt4->execute();
                }
                    
            } else if($median=="PM") {
                //select stu id and date today in attendance if ady logged in PM
                $log= "PM";
                $stmt2 = $this->conn->prepare("SELECT * FROM idtag WHERE student_id = :student_id AND date = :date AND log=:log LIMIT 1");
                $stmt2->execute( array(":student_id" => $student_id,":date" => $todaydate,":log" => $log) );
                $info = $stmt2->fetch();

                if ($stmt2->rowCount() > 0) {
                    $time = $info["time"] . " PM";
                    $name = "Already Logged In - PM";
                    $grade_section = "Already Logged In - PM";
                    $remarks = "Already Logged In - PM";
                    $student_img = "resources/students/none.jpg";
                } else {
                    //update students
                    
                    $time = $time_post . " PM";
                    $stmt4 = $this->conn->prepare("INSERT INTO idtag (student_id,time,log,date)
		        				                VALUES (:student_id,:time,:log,:date)");

		        	$stmt4->bindparam(":student_id", $student_id);
		        	$stmt4->bindparam(":time", $time_post);
		        	$stmt4->bindparam(":log", $log);
		        	$stmt4->bindparam(":date", $todaydate);
                    $stmt4->execute();
                }
            }
            
            $this->output($name, $grade_section, $student_img, $remarks, $time);
        } else {
            $none = "-------";
            $nonedata = $student_id." was Not Found";
            $noneimg = "img/none.jpg";
            $this->output($none, $nonedata, $noneimg, NULL, "0");
        }
    }

    public function get_websettingsinfo() {
        $items = array();
        $stmt = $this->conn->prepare("SELECT * FROM websettings");
        $stmt->execute();
        foreach ($stmt->fetchAll() as $data) {
            $items[] = $data['content'];
        }
        $this->output($items[0], $items[1], "NULL", NULL, "1");
    }
    
    protected function output($ab=NULL, $bc=NULL, $cd=NULL, $de=NULL, $ef=NULL){
		echo json_encode(array(
			'ab' => $ab,
			'bc' => $bc,
			'cd' => $cd,
			'de' => $de,
			'ef' => $ef
		));
    }
    
    protected function fac_output($array){
		echo json_encode($array);
	}
}
new pod_framework();