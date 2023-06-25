<?php
require_once('includes/config.php');

$firstNameFromCookie = "";
$lastNameFromCookie = "";
$studentNumberFromCookie = "";

$stylesheetPreference = $defaultStylesheet;

$genderFromCookie = "";
$male = "";
$female = "";
$nonbinary = "";

$remember = "";

if( isset($_COOKIE["firstName"]) ){
	$firstNameFromCookie = trim($_COOKIE["firstName"]);
}

if( isset($_COOKIE["lastName"]) ){
	$lastNameFromCookie = trim($_COOKIE["lastName"]);
}

if( isset($_COOKIE["studentNumber"]) ){
	$studentNumberFromCookie = trim($_COOKIE["studentNumber"]);
}

if( isset($_COOKIE["stylesheet"]) ){
	$stylesheetPreference = trim($_COOKIE["stylesheet"]);
}

if( isset($_COOKIE["gender"]) ){
	$genderFromCookie = trim($_COOKIE["gender"]);
	if($genderFromCookie == "male"){
		$male = "checked";
	}else if($genderFromCookie == "female"){
		$female = "checked";
	}else if($genderFromCookie == "non-binary"){
		$nonbinary = "checked";
    }
}

if( isset($_COOKIE["rememberMe"]) ){
    $remember = "checked";
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
    <?php if (isset($stylesheetPreference) && strlen($stylesheetPreference) > 0 && is_dir($stylesheetDirectory)) { 
        echo $stylesheetDirectory.$stylesheetPreference; }
    else {
        echo $stylesheetDirectory.$defaultStylesheet; } 
        ?>
    ">
    <title>PHP Form</title>
</head>
<body>
    <header>
        <h1>PHP Form</h1>
    </header>
    
    <main>

        <?php
        if (isset($stylesheetPreference) && strlen($stylesheetPreference) > 0 && is_dir($stylesheetDirectory)) { 
            echo "<p>This page is using the <code> $stylesheetPreference</code> stylesheet.</p>"; 
        }
        else {
            echo "<p>This page is using the <code>$defaultStylesheet</code> stylesheet.</p>"; 
        } 
        ?>

        <form action='processor.php' method='post'>
            <fieldset>
                <legend>Please complete and submit the form.</legend>

                    <div class="infoContainer">
                        <label for="firstName">First Name</label>
                        <input type='text' name='firstName' id="firstName"
                        value="<?php echo $firstNameFromCookie; ?>">                
                    </div>

                    <div class="infoContainer">
                        <label for="lastName">Last Name</label>
                        <input type='text' name='lastName' id="lastName" 
                        value="<?php echo $lastNameFromCookie; ?>">                
                    </div>

                    <div class="infoContainer">
                        <label for="studentNumber">Student Number</label>
                        <input type='text' name='studentNumber' id="studentNumber" 
                        value="<?php echo $studentNumberFromCookie; ?>">                
                    </div>    

                    <div class="infoContainer">

                        <label for="stylesheet">Stylesheet</label>
                        <select id="stylesheet" name="stylesheet">
                            <?php
                            if(is_dir($stylesheetDirectory)) {
                            $arrayOfStyles = array_slice(scandir($stylesheetDirectory), 2);
                                if (is_array($arrayOfStyles)) {
                                    foreach($arrayOfStyles as $oneStylesheet) {
                                        if(is_file($stylesheetDirectory.$oneStylesheet)) {
                                            if($oneStylesheet == $stylesheetPreference){
                                            echo "<option value='$oneStylesheet' selected>$oneStylesheet</option>";
                                            } else {
                                                echo "<option value='$oneStylesheet'>$oneStylesheet</option>";
                                            }
                                        } 
                                    }
                                } 
                            } 
                            ?>
                        </select> 

                    </div>    
            
                <fieldset>            
                    <legend>Gender</legend> 

                        <input type='radio' name='gender' value='male' id="male" <?php echo $male; ?>>
                        <label for="male">Male</label>

                        <input type='radio' name='gender' value='female' id="female" <?php echo $female; ?>>
                        <label for="female">Female</label>

                        <input type='radio' name='gender' value='non-binary' id="non-binary" <?php echo $nonbinary; ?>>   
                        <label for="non-binary">Non-Binary</label>
                </fieldset>

                <fieldset>
                    <legend>Describe Yourself (Choose all that apply)</legend>
                    
                        <input  type="checkbox"	
                                name="describeSelf[]" 
                                id="healthy" 
                                value="healthy">
                        <label for="healthy">Healthy</label>
                        <br>
                        <input  type="checkbox"	
                                name="describeSelf[]" 
                                id="wealthy" 
                                value="wealthy">
                        <label  for="wealthy">Wealthy</label>
                        <br>
                        <input  type="checkbox"	
                                name="describeSelf[]" 
                                id="wise" 
                                value="wise">
                        <label  for="wise">Wise</label> 

                </fieldset>           

                    <input  type="checkbox"	
                    name="rememberMe" 
                    id="rememberMe" 
                    value="rememberMe" 
                    <?php echo $remember; ?>
                    >
                    <label for="rememberMe">Remember Me</label>
                    <br>
                
                <input class="faux-btn" type='submit'>
            </fieldset>

        </form>

    </main>

</body>
</html>