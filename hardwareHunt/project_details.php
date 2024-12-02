<?php

// var_dump output variables ot the screen
// request contains all submitted form information
var_dump($_REQUEST);

// echo "<br><br>hello " . $_REQUEST["fullname"];

//step 1 connect to database

$host = "webdev.iyaserver.com";
$userid = "nepo";
$userpw = "BackendMagic1024";
$db = "nepo_hardwareHunt2";

// inlcude "../anvariables.php";

$mysql = new mysqli(
    $host,
    $userid,
    $userpw,
    $db
);
//
// if I can find an error number then stop because there was a problem
if($mysql->connect_errno) { //if error
    echo "db connection error : " . $mysql->connect_error; //tell me there was an erro
    exit(); //stop running page
} else {
    //echo "db connection success!"; //slaytastic. no errors, removing to get rid of it on page
    //if you mess up username password serve then this error will come up.
}
?>
<html>

<body>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<?php
$sql = "SELECT * from projects WHERE project_id = " . $_REQUEST['project_id'];
$results = $mysql->query($sql);

if(!$results) {
    echo "<hr>Your SQL:<br> " . $sql . "<br><br>";
    echo "SQL Error: " . $mysql->error . "<hr>";
    exit();
}

while($currentrow = $results->fetch_assoc()) {
    ?>
    <strong>project name: </strong> <?php echo $currentrow['project_name']; ?><br>
       <strong> project description: </strong><?php echo $currentrow['project_description']; ?><br>
    <strong>project documentation: </strong><?php echo $currentrow['project_documentation_url']; ?><br>
    <strong>project price: </strong><?php echo $currentrow['cost']; ?><br>
    <strong>project fork count: </strong><?php echo $currentrow['fork_count']; ?><br>


<?php  }?>

</body>
</html>