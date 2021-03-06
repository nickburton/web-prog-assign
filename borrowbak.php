<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" 
  xml:lang="en" lang="en">

<head>

 <title>rmitlibms | Borrow</title>

 <meta name="description" content="Students borrow from the RMIT Library Management System."/>
 <meta name="keywords" content="books, library, borrow books, library books, library staff, university booklist"/>

 <link type="text/css" rel="stylesheet" href="style.css" />

</head> 

<body>

  <div class="navbarl">

   <a href="login.php">Logout</a>
   | <a href="home.php">Home</a>
 
 </div>

 <div class="navbarr">

  <a href="search.html">Search</a>
  | <strong>Borrow</strong>
  | <a href="return.html">Return</a>

 </div>
 <div><br/></div>
<hr/>

<?php

	$button1 = 'unchecked';
	$button2 = 'unchecked';
	$button3 = 'unchecked';
	$button4 = 'unchecked';
	
	$searched = $_GET['type'];
	
	if (isset($searched))
	{
		$selected_button = $_GET['type'];

		if ($selected_button == "author")
		{
			$button1 = "checked";
		}
		else if ($selected_button == "title")
		{
			$button2 = "checked";
		}
		else if ($selected_button == "keywords")
		{
			$button3 = "checked";
		}
		else if ($selected_button == "ISBN")
		{
			$button4 = "checked";
		}
	}
	else
	{
		$button2 = "checked";
	}
?>

<form action="borrow.php" method="get">
 <div class="homesign">
  <a href="search.html"><img src="logoxsmall.jpg" alt="rmitlibs" /></a><br/>
  <input type="text" name="search" maxlength="110" size="43" value="" />
  <input type="submit" value="Search" /><br/>
  Search:
  <input type="radio" name="type" value="author" <?php if($button1 == "checked"){echo "checked='checked'";} ?> />author
  <input type="radio" name="type" value="title"  <?php if($button2 == "checked"){echo "checked='checked'";} ?> />title
  <input type="radio" name="type" value="keywords"  <?php if($button3 == "checked"){echo "checked='checked'";} ?> />keywords
  <input type="radio" name="type" value="ISBN" <?php if($button4 == "checked"){echo "checked='checked'";} ?> />ISBN<br/><br/>
 </div>
</form>

<div class="results">
<strong>Search Results</strong>
</div>

<table class="borrow">

<?php

function borrow_button()
{
	echo "<tr><td></td>";
	echo "<td><p>";
	echo "<form action='students.php' method='post'>";
	echo "<input type='submit' name='borrow' value='Borrow'/>";
	echo "</form>";	
	echo "</p></td></tr>";
}

function echoLine($line)
{
	//This is possibly the coolest part of my assignment!!

	$searchType = $_GET['type'];
	
	echo "<tr><td>";
	echo "<img src='return.png' alt='Book Photo' width='60' height='60' />";
	echo "</td>";

	echo "<td><p>";
	echo "<strong><a href='./borrow.php?search=" . $line[0] . "&type=" . $searchType . "'>" . $line[0] . "</a></strong><br/>";
	echo "<font color='grey'>by " . $line[1] . " - " . $line[2] . " - " . $line[3] . " - " . $line[4] . " pages</font><br/>";
	echo $line[5] . "<br/>";
	echo "<font color='#B22222'>Call Number: " . $line[6] . " | " . "Status: " . $line[7] . "</font>";
	echo "<br/>";
	echo "</p></td></tr>";
	echo "\n";
}

function showBooks($fp)
{
	while (!feof($fp))
	{			
		$line = fgets($fp);
		$element = split("\|", $line);
		echoLine($element);
	}
}

function getBooks()
{
	$fp = fopen("books.txt","r"); 
	rewind($fp);
	
	$searched = $_GET['type'];
	$_SESSION['search'] = $_GET['search'];

	if(isset($searched))
	{
		if($_SESSION['search'] != '')
		{
			$searchType = $_GET['type'];
			
			$titles = array("title","author","category","year","pages","notes","ISBN","status","keywords");
				
			for($counter=0;$counter<count($titles);$counter++)
			{
				if($searchType == $titles[$counter])
				{
					$arrayNum = $counter;
				}
			} 
			
			while(!feof($fp))
			{
				$line = fgets($fp);
				$element = split("\|", $line);
									
				if(stristr($element[$arrayNum],$_SESSION['search']) == TRUE)
				{
					echoLine($element);
					if(strcmp($element[$arrayNum],$_SESSION['search']) == 0)
					{
						borrow_button();
					}
				}
			}
		}
	}
	else
	{	
		showBooks($fp);
	}	
}
getBooks();
		
?>
  
</table>

</body>
</html>
