<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
 	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="css/style.css">
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
	$classlistq="SELECT ClassID, CONCAT_WS('-', Start,End) AS Classyear FROM Class ORDER BY End DESC";
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
						$listresult = mysqli_query($link,$classlistq);
		        		while ($row = mysqli_fetch_array($listresult)){
							//Display every class in the select box
							echo "<option value=$row[ClassID]>$row[Classyear]</option>";
						}
?>				
						</select>
					</div>
<!-- --------------------------------End of Select Box------------------------------------------------------- -->
					<div class="col-md-1">
						<button name="button" type="submit" class="btn" id="submitbutton">SUBMIT</button>
					</div>
<!-- -------------------------------------End of Submit---------------------------------------------------- -->
				</div>   
			</form>
        </div>
    </div>
</header>

<!-- Start of Navigation Bar. It contains the header of the table. -->      		
<div class="main-nav">		
	<div class="container" id="head">
		<div class="col-md-2"><h4>Name</h4></div>
      	<div class="col-md-3"><h4>Current Title</h4></div>
      	<div class="col-md-3"><h4>Current Company</h4></div>
      	<div class="col-md-2"><h4>Current Location</h4></div>
      	<div class="col-md-2"><h4>Year of Class</h4></div>
	</div>
</div>
<!-- ---------------End of Navigation Bar------------------------- -->
				
<div class="main">
<div class="container">
  	<div class="row">
      	<div id="content" class="col-md-12">
      	
<?php 
			if ( isset($_GET['class']) )
			{   // Because the page gets from its own
			    // We have to check if submit is clicked and get isn't null
				
				$class=$_GET['class'];
				$selectclassq = "SELECT CONCAT_WS('-', Start, End) AS Classyear, 
       			             			CONCAT_WS(' ', FirstName, LastName) AS Name,
        			            		Title.name AS Currenttitle, 
         			            		People.PeopleID, 
                                        Company.Company AS Currentcompany, 
                                        CONCAT_WS(', ', City, State) AS Location
                  				FROM Class,People,Title,Main,CurrentCompany,Location,Company
                     				WHERE Class.ClassID = $class
                     					AND Class.ClassID=Main.ClassID
                     					AND Main.PeopleID=People.PeopleID
                     					AND Main.MainID=CurrentCompany.MainID
                                        AND CurrentCompany.CompanyID=Company.CompanyID
                                        AND CurrentCompany.LocationID=Location.LocationID
                     					AND CurrentCompany.TitleID=Title.Title_id";
				$listresult = mysqli_query($link, $selectclassq);
				/* This query gets the record of name, curren title, current company, current location, and year of class,
					the name is consisted of  first name and last name, the location is consisted of city and states,
					the year of class is consisted of start year and end year. */		
				echo "<table>";			
					echo "<tbody>"; 
						while ($row = mysqli_fetch_array($listresult)) {
							//Display the result. A person's name is a link to a page of more detailed information.
							echo "<tr>
								<td class=col-md-2><a href=\"info.php?name=$row[PeopleID]\">$row[Name]</a></td>
								<td class=col-md-3>$row[Currenttitle]</td>
                                <td class=col-md-3>$row[Currentcompany]</td>
                                <td class=col-md-2>$row[Location]</td>
                                <td class=col-md-2>$row[Classyear]</td>
							    </tr>";
						}
						
						mysqli_close($link);
						
						 "</tbody>";
						 "</table>";

			} else {
				echo "<h3>Please select a value.</h3>";
			}
?>

      	</div>
    </div>
</div>

<!-- This is the button at the right corner which can make the page scroll up to the top. -->
<a href="#" id="return-to-top"><i class="glyphicon glyphicon-chevron-up"></i></a>

</div>
<!-- End of Main -->

</body>
</html>
