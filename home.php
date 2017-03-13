<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
 	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="css/homestyle.css">
	<!-- JavaScript -->
	<script type="text/javascript" src="js/scroll.js"></script>
	
</head>

<body>
<?php
        //Open a connection to the MySQL server
        $host = "localhost";
        $user = "ilinked";
        $password = "ghlpq2016";
        $database = "ilinked";
        $link = mysqli_connect($host, $user, $password, $database);
        //This is the query of getting the class list for the select box to display
        //The class table has two columns of the start year and end year, so we need to concatenate them
	//The display is in decending order of the end year      
        $selectclassq="SELECT ClassID, CONCAT_WS('-', Start,End) AS Classyear FROM Class ORDER BY End DESC";
?>

<header>    
<!-- The header contains the background image, the title, the subtitile, the select box, and the submit button. -->
	<div class="background-image"></div>
	<h1>iLinked</h1>
    <h2>Database of iSchool Alumni</h2>
    <br>
    <div class="container">
    	<div class="row">                                  
			<!-- Use form to get the select value -->
			<form role="form" action="result.php" method="get">
				<div class="form-group">
					<!-- An empty div to make the next div display in the center -->
					<div class="col-md-4"></div>
<!-- -------------------------------------Start of Select Box---------------------------------------------- -->
					<div class="col-md-3">
						<label for="selclass">Year of Class:</label><br>
						<select name="class" multiple class="form-control col-md-2">
						<!-- For the sake of display layout we use the multiple select box. -->
						<!-- If need single select function, just leave out the "multiple" above. -->
<?php  	
						//Get the class from database
						$listresult = mysqli_query($link,$selectclassq);
		        		while ($row = mysqli_fetch_array($listresult)){
							//Display every class in the select box
							echo "<option value=$row[ClassID]>$row[Classyear]</option>";
						}
?>				
						</select>
					</div>
<!-- --------------------------------End of Select Boxs------------------------------------------------------- -->
					<!-- The Submit Button --> 
					<div class="col-md-1">
						<button name="button" type="submit" class="btn" id="submitbutton">SUBMIT</button>
					</div>
				</div>   
			</form>
        </div>
    	
    </div>
</header>
</body>
</html>
