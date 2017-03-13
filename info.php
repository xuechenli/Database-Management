<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
 	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="css/infostyle.css">
	<!-- JavaScript -->
	<script type="text/javascript" src="js/scroll.js"></script>
	
</head>

<body>
<header>
	<!-- The header contains the background image, the title, and the subtitle. -->
	<div class="background-image"></div>
	<h1>iLinked</h1>
    <h2>Database of iSchool Alumni</h2>
</header>

<div class="main-nav">		
	<div class="container" id="head">
	<div class="row"> 
		<div class="col-md-1">
		<!-- This is the button which can lead back to the result page. -->
		<form><input type="button" class="btn" value="Back to Results" id="submitbutton" onClick="history.go(-1);return true;"></form>
		</div>
		<div class="col-md-3"></div>
<?php 
			//Open a connection to the MySQL server
			$host="localhost";
			$user="ilinked";
			$password="ghlpq2016";
			$database="ilinked";
			$link=mysqli_connect($host,$user,$password,$database);
			//Get the person's name
			$peopleid=$_GET['name'];
			$searchquery="SELECT CONCAT_WS(' ', FirstName, LastName) AS Name
 							FROM People
 							WHERE $peopleid=People.PeopleID";
			$current = mysqli_query($link,$searchquery);
			while ($row = mysqli_fetch_array($current)){
				//Display the person's name on the navigation bar.
   				print "<div class=col-md-4><h4>$row[Name]</h4></div";
   			}
/* This while loop establishes where we pull our information from, on results.php. The PeopleID comes from clicking on a person's name on 
that page. */	
?>
	</div>
	</div>
</div>
<!-- End of Navigation Bar -->
				
<div class="main">
<div class="container">
  	<div class="row">
      	<div id="content" class="col-md-12">
<?php
			$host="localhost";
			$user="ilinked";
			$password="ghlpq2016";
			$database="ilinked";
			$link=mysqli_connect($host,$user,$password,$database);
			$peopleid=$_GET['name'];
			$searchquery="SELECT CONCAT_WS(' ', FirstName, LastName) AS Name,
                     			CONCAT_WS('-', Start, End) AS Classyear,
                     			Degree,
                     			Title.name AS Currenttitle,
                     			Company.Company AS Currentcompany,
                     			CONCAT_WS(', ', City, State) AS Location
                     	FROM Degree, People, Class, Title, Company, Location, Main, CurrentCompany
                     	WHERE $peopleid=People.PeopleID
                     			AND People.PeopleID=Main.PeopleID
                     			AND Main.ClassID=Class.ClassID
                     			AND Main.DegreeID=Degree.DegreeID
                     			AND Main.MainID=CurrentCompany.MainID
                     			AND CurrentCompany.CompanyID=Company.CompanyID
                     			AND CurrentCompany.TitleID=Title.Title_id
                     			AND CurrentCompany.LocationID=Location.LocationID";
			/* This query gets us everything we to display the person's name, current company and relevant information, 
 			* the years they spent at the iSchool, and their degree.  */
			$current = mysqli_query($link,$searchquery);
			while ($row = mysqli_fetch_array($current)){
   				print "<table>";
   				print "<tr><td>Name:</td><td>$row[Name]</td></tr>
          		<tr><td>Class of Year:</td><td>$row[Classyear]</td></tr>
          		<tr><td>Degree:</td><td>$row[Degree]</td></tr>
          		<tr><td>Current Job:</td><td>$row[Currenttitle] at $row[Currentcompany], $row[Location]</td></tr>
          		</table>";
   			}
			print "<p>Previous Experience:</p>";
			$searchquery="SELECT Company.Company AS Previouscompany
                     FROM People, Company, PreviousCompany, Main
					 WHERE $peopleid=People.PeopleID
					 AND People.PeopleID=Main.PeopleID
					 AND Main.MainID=PreviousCompany.MainID
					 AND PreviousCompany.CompanyID=Company.CompanyID
					 ";
			/* This produces another table to display their previous company. 
			 * We split the tables up because it was a way to error check 
			 * much easier than a single, massive query. */
			$current = mysqli_query($link,$searchquery);
			print "<table>";
			if(mysqli_num_rows($current)==0){
				print "<tr><td>Not Available</td></tr>"; //If the person does not have previous experience record then print NA.
			} else { 					//Else print the record
				while ($row = mysqli_fetch_array($current)){
   					print "<tr><td>$row[Previouscompany]</td></tr>";
   				}
			}
			print "</table>";
			$searchquery="SELECT InternTitle.InternTitle AS Internship
                     FROM People, InternTitle, Internship, Main
					 WHERE $peopleid=People.PeopleID
					 AND People.PeopleID=Main.PeopleID
					 AND Main.MainID=Internship.MainID
					 AND InternTitle.ITID=Internship.ITID";
			$current = mysqli_query($link,$searchquery);
/* The query about internships. */
			print "<p>Internships:</p>";
			print "<table>";
			if(mysqli_num_rows($current)==0){		//If the person does not have internship record then print NA.
                                print "<tr><td>Not Available</td></tr>"; 
                        } else {					//Else print the record.
				while ($row = mysqli_fetch_array($current)){
   					print "<tr><td>$row[Internship]</td></tr>";
   				}
			}
			print "</table>";
			$searchquery="SELECT Course.Courses AS Courses
                     FROM People, Course, Main
					 WHERE $peopleid=People.PeopleID
					 AND People.PeopleID=Main.PeopleID
					 AND Main.MainID=Course.MainID";
			$current = mysqli_query($link,$searchquery);
/* Finally, the query about classes taken. Note below that this doesn't generate a table. The classes taken was one text string per 
student. This was admittedly a time-saving measure for the end of the year, but it also reflects an important part of our data. Some 
students only listed a fraction of their courses. Some listed none. */
			print "<p>Courses Taken:</p>";
			print "<table>";
			if(mysqli_num_rows($current)==0){		//If the person does not have courses record then print NA.
                                print "<tr><td>Not Available</td></tr>"; 
                        } else {					//Else print the record.
				while ($row = mysqli_fetch_array($current)){
   					print "<tr><td>$row[Courses]</td></tr>";
	   			}
			}
			print "</table>";
?>
		</div>
	</div>
</div>

<!-- This is the button at the right corner which can make the page scroll up to the top. -->
<a href="" id="return-to-top"><i class="glyphicon glyphicon-chevron-up"></i></a>

</div>

</body>
</html>
