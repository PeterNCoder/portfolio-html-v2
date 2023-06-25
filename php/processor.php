<?php
require_once('includes/config.php');

const LINK_BACK_TO_FORM	= "<p><a href='form.php'>Try the form again...</a></p>";
$firstName		= "";
$lastName		= "";
$studentNumber  = "";
$stylesheet     = "";
$gender         = "";
$genderTitle    = "";
$rememberMe     = "";
$errorsFound    = array();
$describeSelf   = array();
$pattern        = "/^a0[0-9]{7}$/i";
$expiry	        = time()+60*60;
$deleteCookie   = time()-1;

if( isset($_POST['firstName']) ){
    $firstName 		= trim($_POST['firstName']);
        if( $firstName == "" ){
            $errorsFound[] = "The first name field was not filled in.";
        }
} else {
    $errorsFound[] = "The first name field was not set";
}

if( isset($_POST['lastName']) ){
    $lastName		= trim($_POST['lastName']);
        if( $lastName == "" ){
            $errorsFound[] = "The last name field was not filled in.";
        }
} else {
    $errorsFound[] = "The last name field was not set";
}

if( isset($_POST['studentNumber']) ){
    $studentNumber  = trim($_POST['studentNumber']);
        if( $studentNumber == "" ){
            $errorsFound[] = "The student number field was not filled in.";
        }
} else {
    $errorsFound[] = "The student number field was not set";
}

if( isset($_POST['stylesheet']) ){
    $stylesheet  = trim($_POST['stylesheet']);
} else {
    $errorsFound[] = "The stylesheet was not set";
}

$fullName = ucwords(strtolower($firstName . " " . $lastName));

if (isset($pattern)){
    if(!preg_match($pattern, $studentNumber)){
        $errorsFound[] = "The student number pattern should look like 'A0nnnnnnn'";
    }
}

if( isset($_POST["gender"])){
    $gender = trim($_POST['gender']);
    if($_POST["gender"] == "male"){
        $genderTitle = "Mr.";
    } else if ($_POST["gender"] == "female") {
        $genderTitle = "Ms.";
    } else {
        $genderTitle = "Citizen";
    }
}else{
    $errorsFound[] = "A gender was not selected.";
}

if( isset($_POST["rememberMe"])){
    $rememberMe = trim($_POST['rememberMe']);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/normalize-fwd.css">
    <link rel="stylesheet" href="
    <?php 
    if(isset($stylesheet) && strlen($stylesheet) > 0 && is_dir($stylesheetDirectory)) { 
        echo $stylesheetDirectory.$stylesheet; 
    }
    else { 
        echo $stylesheetDirectory.$defaultStylesheet; 
    } 
    ?>
    ">
    <title>Form Processing</title>
</head>
<body>
    <header>
        <h1>Form Processing</h1>
    </header>

    <main>             
        <div class="php">

        <?php 

        if( isset($errorsFound)) {
            if( count($errorsFound) > 0  ){
                echo "<h2 class='bad'>Unfortunately the form was not filled out correctly. Please fix the following issue(s):</h2>";
                echo "<ul>";

                foreach($errorsFound as $error){
                    echo "<li>$error</li>";
                }

                echo "</ul>";

                if (isset($stylesheet) && strlen($stylesheet) > 0 && is_dir($stylesheetDirectory)) { 
                    echo "<p>This page is using the <code> $stylesheet</code> stylesheet.</p>"; 
                }
                else {
                    echo "<p>This page is using the <code>$defaultStylesheet</code> stylesheet.</p>"; 
                } 

                echo "<p>Form invalid. <br>
                Stylesheet settings not remembered. <br>
                No new cookies created, deleting any cookie(s) that might have previously been made.</p>" .
                LINK_BACK_TO_FORM;

                if(isset($deleteCookie)) {
                    setcookie("firstName", "", $deleteCookie);
                    setcookie("lastName", "", $deleteCookie);
                    setcookie("studentNumber", "", $deleteCookie);
                    setcookie("gender", "", $deleteCookie);
                    setcookie("stylesheet", "" , $deleteCookie);
                    setcookie("rememberMe", "" , $deleteCookie);
                } else {
                    echo "<p>Error: Unable to delete cookie, \$deleteCookie is not set.</p>";
                }

            }else{
                echo "<h2>Marvelous!</h2>
                    <p>You have successfully submitted the form. Thank you!</p>";

                if (isset($stylesheet) && strlen($stylesheet) > 0 && is_dir($stylesheetDirectory)) { 
                    echo "<p>This page is using the <code> $stylesheet</code> stylesheet.</p>"; 
                }
                else {
                    echo "<p>This page is using the <code>$defaultStylesheet</code> stylesheet.</p>"; 
                } 
                    
                echo "<p class='good'>You did a great job, $genderTitle $fullName!</p>";

                if( isset($_POST["describeSelf"]) ){
                    $describeSelf = $_POST["describeSelf"];
                    echo "<p>You are:</p>
                        <ul>";
                    foreach( $describeSelf as $oneDescription ){
                        echo "<li>". ucfirst(strtolower(trim($oneDescription)))."</li>";
                    }
                    echo "</ul>";
                    
                    if (count($describeSelf) == 1){
                        echo "<p>Gee, that's swell!</p>";
                    } else if (count($describeSelf) == 2){
                        echo "<p>Glad to hear it! Keep it up!</p>";
                    } else if (count($describeSelf) == 3){
                        echo "<p>WOW! That's great!</p>";
                    }

                } else {
                echo "<p>Chin up! Things can only get better!</p>";
                }

                if(isset($_POST["rememberMe"])) {
                    if(isset($expiry)){
                    echo "<p>Form data is valid and the user wants to be remembered. <br> Creating the cookie(s).</p>";
                    setcookie("firstName", $firstName, $expiry);
                    setcookie("lastName", $lastName, $expiry);
                    setcookie("studentNumber", $studentNumber, $expiry);
                    setcookie("gender", $gender, $expiry);
                    setcookie("stylesheet", $stylesheet, $expiry);
                    setcookie("rememberMe", $rememberMe, $expiry);
                    } else {
                    echo "<p>Error: Form data is valid and the user wants to be remembered but cookie expiration is not set.</p>";
                    }
                } else {
                    if(isset($deleteCookie)) {
                    echo "<p>Form data is valid, but the user does not want to be remembered. Time to delete any cookie(s) that might have previously been made.</p>";
                    setcookie("firstName", "", $deleteCookie);
                    setcookie("lastName", "", $deleteCookie);
                    setcookie("studentNumber", "", $deleteCookie);
                    setcookie("gender", "", $deleteCookie);
                    setcookie("stylesheet", "" , $deleteCookie);
                    setcookie("rememberMe", "" , $deleteCookie);
                    } else {
                        echo "<p>Error: Unable to delete cookie, \$deleteCookie is not set.</p>";
                    }
                }

            echo LINK_BACK_TO_FORM;
                
            }
        } else {
            echo "<p>Error: Error detection not set</p>";
            echo LINK_BACK_TO_FORM;
        }

        ?>

        </div>
    </main>

</body>
</html>