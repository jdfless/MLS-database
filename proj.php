<?php ini_set('display_errors', 'On'); ?>
<!DOCTYPE html>
<html><head><title>Flessner Database Project</title>
<link rel="stylesheet" type="text/css" href="projStyle.css">
<meta charset="UTF-8">
<script src="jQuery/jQuery2.1.0.js"></script>
<script src="jQuery/form-validator/jquery.form-validator.js"></script>
</head><body>

<h2>MLS Database</h2>

<div class="teamsForm">
	<form method="post">
	<legend><b>Enter a team</b></legend>
	<p>Team Name: <input name="tName" 
		data-validation="required alphanumeric" data-validation-allowing=" " 
		data-validation-error-msg="Enter a valid team name."></p>
	<p>Conference:
		<input type="checkbox" name="division" value="East"
		data-validation="checkbox_group" data-validation-qty="0-1" data-validation-error-msg="&#8592; Select one conference.">East
		<input type="checkbox" name="division" value="West"
		data-validation="checkbox_group" data-validation-qty="0-1" data-validation-error-msg="&#8592; Select one conference.">West
	</p>
	<p>Year Founded: <input name="founded"
		data-validation="number length" data-validation-allowing="range[1900;2020]" data-validation-optional="true"
		data-validation-length="max4" data-validation-error-msg="Enter the year only. Ex. '1974'"></p>
	<p>First MLS Season: <input name="joinedMLS"
		data-validation="number length" data-validation-allowing="range[1996;2020]" data-validation-optional="true"
		data-validation-length="max4" data-validation-error-msg="Enter the year only since 1996. "></p>
	<p>Stadium: <input name="stadium" 
		data-validation="required" data-validation-error-msg="Enter the stadium name."></p>
	<p>US Open Cup Titles: <input name="numUSOpenCups"
		data-validation="number length" data-validation-allowing="range[0;99]" data-validation-optional="true"
		data-validation-length="max2" data-validation-error-msg="Enter no. of US Open Cup titles."></p>
	<p>Supporter's Shields: <input name="numSS"
		data-validation="number length" data-validation-allowing="range[0;99]" data-validation-optional="true"
		data-validation-length="max2" data-validation-error-msg="Enter no. of Supporter's Shields."></p>
	<p>MLS Cups Won: <input name="numMLSCups"
		data-validation="number length" data-validation-allowing="range[0;99]" data-validation-optional="true"
		data-validation-length="max2" data-validation-error-msg="Enter no. of MLS Cups won."></p>
	<input type="submit" name="teamSubmit" value="Add to Database">
	</form>
<?php 
//mysqli connection
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","flessnej-db","R096iQvGYroJUKhJ","flessnej-db");
if (!$mysqli || $mysqli->connect_errno){
	echo "Connection error" . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

//team submit
if(isset($_POST['teamSubmit'])){
	$tQuery = $mysqli->prepare("INSERT INTO teams(name, division, founded, joinedMLS, stadium, numUSOpenCups, numSS, numMLSCups) 
		VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
	$tQuery->bind_param("ssiisiii", $_POST['tName'], $_POST['division'], $_POST['founded'], $_POST['joinedMLS'], 
		$_POST['stadium'], $_POST['numUSOpenCups'], $_POST['numSS'], $_POST['numMLSCups']);
	if($tQuery->execute())
		echo "Team successfully added.";
	elseif(!$tQuery->execute() && $mysqli->errno == 1062)
		echo "Error! Team already exists.";
	elseif(!$tQuery->execute())
		echo "Error: team not added.";
	$tQuery->close();
}
$mysqli->close();

?>
</div>

<div class="playersForm">
	<form method="post">
	<legend><b>Enter a player</b></legend>
	<p>Player Name: <input name="pName" data-validation="required" 
		data-validation-error-msg="Enter the full player name."></p>
	<p>Birthdate: <input name="dob" data-validation="birthdate" data-validation-optional="true"
		data-validation-format="mm/dd/yyyy" data-validation-error-msg="Enter DOB as: mm/dd/yyyy"></p>
	<p>Team: <input name="pTeam" data-validation="alphanumeric" data-validation-optional="true"
		data-validation-allowing=" " data-validation-error-msg="Enter a valid team name."></p>
	<input type="submit" name="playerSubmit" value="Add to Database">
	</form>
<?php 
//mysqli connection
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","flessnej-db","R096iQvGYroJUKhJ","flessnej-db");
if (!$mysqli || $mysqli->connect_errno){
	echo "Connection error" . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

//player submit
if(isset($_POST['playerSubmit'])){
	//translate date from php to mysql
	if(!empty($_POST['dob'])){
		$dob = date('Y-m-d', strtotime($_POST['dob']));
	}
	else
		$dob = null;

	$pQuery = $mysqli->prepare("INSERT INTO players(name, dob, team) VALUES(?,?,?)");
	$pQuery->bind_param("sss", $_POST['pName'], $dob, $_POST['pTeam']);
	if($pQuery->execute())
		echo "Player successfully added.";
	elseif(!$pQuery->execute() && $mysqli->errno == 1452)
		echo "Error! Player must be added to a team that already exists.";
	elseif(!$pQuery->execute() && $mysqli->errno == 1062)
		echo "Error! That player has already been entered.";
	elseif(!$pQuery->execute())
		echo "Uh-oh, something went wrong. Try entering different data.";
	$pQuery->close();
}
$mysqli->close(); 
?>
</div>

<div class="breakDiv">
<hr>
</div>

<div class="stadForm">
	<form method="post">
	<legend><b>Enter a stadium</b></legend>
	<p>Stadium Name: <input name="sName" data-validation="required"
		data-validation-error-msg="Enter the stadium name."></p>
	<p>City: <input name="sCity" data-validation="required"
		data-validation-error-msg="City name is required."></p>
	<p>Avg. Attendance: <input name="avgAtt" data-validation="number" data-validation-allowing="range[0;99999]"
		data-validation-optional="true" data-validation-error-msg="Enter fans/game from last season."
		placeholder="From last full season."></p>
	<p>Home team: <input name="sTeam" data-validation="required"
		data-validation-error-msg="Home team is required."></p>
	<input type="submit" name="stadiumSubmit" value="Add to Database">
	</form>
<?php 
//mysqli connection
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","flessnej-db","R096iQvGYroJUKhJ","flessnej-db");
if (!$mysqli || $mysqli->connect_errno){
	echo "Connection error" . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

//stadium submit
if(isset($_POST['stadiumSubmit'])){
	$sQuery = $mysqli->prepare("INSERT INTO stadiums(name, city, avgAttendance, team) VALUES(?,?,?,?)");
	$sQuery->bind_param("ssis", $_POST['sName'], $_POST['sCity'], $_POST['avgAtt'], $_POST['sTeam']);
	if($sQuery->execute())
		echo "Stadium successfully added.";
	elseif(!$sQuery->execute() && $mysqli->errno == 1452)
		echo "Error! Stadium must have a team that already exists.";
	elseif(!$sQuery->execute() && $mysqli->errno == 1062)
		echo "Error! That team already has a stadium.";
	elseif(!$sQuery->execute())
		echo "Error: stadium not added.";
	$sQuery->close();
}
$mysqli->close();
?>
</div>

<div class="coachesForm">
	<form method="post">
	<legend><b>Enter a coach</b></legend>
	<p>Coach Name: <input name="cName" data-validation="required" 
		data-validation-error-msg="Enter the full coach name."></p>
	<p>Birthdate: <input name="Cdob" data-validation="birthdate" data-validation-optional="true"
		data-validation-format="mm/dd/yyyy" data-validation-error-msg="Enter DOB as: mm/dd/yyyy"></p>
	<p>Team: <input name="cTeam" data-validation="alphanumeric" data-validation-optional="true"
		data-validation-allowing=" " data-validation-error-msg="Enter a valid team name."></p>
	<input type="submit" name="coachSubmit" value="Add to Database">
	</form>
<?php 
//mysqli connection
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","flessnej-db","R096iQvGYroJUKhJ","flessnej-db");
if (!$mysqli || $mysqli->connect_errno){
	echo "Connection error" . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

//coach submit - nearly identical to player submit
if(isset($_POST['coachSubmit'])){
	//translate date from php to mysql
	if(!empty($_POST['Cdob'])){
		$dob = date('Y-m-d', strtotime($_POST['Cdob']));
	}
	else
		$dob = null;

	$cQuery = $mysqli->prepare("INSERT INTO coaches(name, dob, team) VALUES(?,?,?)");
	$cQuery->bind_param("sss", $_POST['cName'], $Cdob, $_POST['cTeam']);
	if($cQuery->execute())
		echo "Coach successfully added.";
	elseif(!$cQuery->execute() && $mysqli->errno == 1452)
		echo "Error! Coach must be added to a team that already exists.";
	elseif(!$cQuery->execute() && $mysqli->errno == 1062)
		echo "Error! That coach has already been entered <br>OR that team already has a coach.";
	elseif(!$cQuery->execute())
		echo "Uh-oh, something went wrong. Try entering different data.";
	$cQuery->close();
}
$mysqli->close(); 
?>
</div>

<div class="breakDiv">
<hr>
</div>

<div class="ocTeams">
	<form method="post">
	<legend><b>Enter coaches former teams</b></legend>
	<p>Coach Name: <input name="ocName" data-validation="required" 
		data-validation-error-msg="Enter the full coach name."
		placeholder="Coach must be in DB"></p>
	<p>Team: <input name="ocTeam" data-validation="alphanumeric" data-validation-optional="true"
		data-validation-allowing=" " data-validation-error-msg="Enter a valid team name."
		placeholder="Team must be in DB"></p>
	<input type="submit" name="ocSubmit" value="Add to Database">
	</form>
<?php 
//mysqli connection
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","flessnej-db","R096iQvGYroJUKhJ","flessnej-db");
if (!$mysqli || $mysqli->connect_errno){
	echo "Connection error" . $mysqli->connect_errno . " " . $mysqli->connect_error;
}


//coach former teams
if(isset($_POST['ocSubmit'])){
	$ocQuery = $mysqli->prepare("INSERT INTO coachTeams(cid, tid) VALUES 
		((SELECT id FROM coaches WHERE name  =?), (SELECT id FROM teams WHERE name =?))");
	$ocQuery->bind_param("ss", $_POST['ocName'], $_POST['ocTeam']);
	if($ocQuery->execute())
		echo "Coaches former team added successfully.";
	elseif(!$ocQuery->execute() && $mysqli->errno == 1452)
		echo "Error! Coach and team must already exist.";
	elseif(!$ocQuery->execute() && $mysqli->errno == 1062)
		echo "Error! That coaches former team already exists.";
	elseif(!$ocQuery->execute())
		echo "Uh-oh, something went wrong. Try entering different data.";
	
	$ocQuery->close();
}
$mysqli->close();
?>
</div>

<div class="opTeams">
	<form method="post">
	<legend><b>Enter players former teams</b></legend>
	<p>Player Name: <input name="opName" data-validation="required" 
		data-validation-error-msg="Enter the full player name."
		placeholder="Player must be in DB"></p>
	<p>Team: <input name="opTeam" data-validation="alphanumeric" data-validation-optional="true"
		data-validation-allowing=" " data-validation-error-msg="Enter a valid team name."
		placeholder="Team must be in DB"></p>
	<input type="submit" name="opSubmit" value="Add to Database">
	</form>
<?php 
//mysqli connection
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","flessnej-db","R096iQvGYroJUKhJ","flessnej-db");
if (!$mysqli || $mysqli->connect_errno){
	echo "Connection error" . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

//player former teams
if(isset($_POST['opSubmit'])){
	$opQuery = $mysqli->prepare("INSERT INTO playerTeams(pid, tid) VALUES 
		((SELECT id FROM players WHERE name  =?), (SELECT id FROM teams WHERE name =?))");
	$opQuery->bind_param("ss", $_POST['opName'], $_POST['opTeam']);
	if($opQuery->execute())
		echo "Players former team added successfully.";
	elseif(!$opQuery->execute() && $mysqli->errno == 1452)
		echo "Error! Player and team must already exist.";
	elseif(!$opQuery->execute() && $mysqli->errno == 1062)
		echo "Error! That players former team already exists.";
	elseif(!$opQuery->execute())
		echo "Uh-oh, something went wrong. Try entering different data.";
	
	$opQuery->close();
}
$mysqli->close(); 
?>
</div>

<div class="breakDiv">
<hr>
</div>

<div class="tShowDiv">
	<form method="post">
	<legend><b>Show Conferences</b></legend>
	<p><select name="showTeams">
		<option value="none">Select a conference...</option>
		<option value="East">East</option>
		<option value="West">West</option>
	</select></p>
	<input type="submit" name="showConfs" value="Show Conference">
	<input type="submit" name="clearConfs" value="Hide Table">
	</form>
<?php
//mysqli connection
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","flessnej-db","R096iQvGYroJUKhJ","flessnej-db");
if (!$mysqli || $mysqli->connect_errno){
	echo "Connection error" . $mysqli->connect_errno . " " . $mysqli->connect_error;
} 
//show teams in conferences
if(isset($_POST['showConfs'])){
	if($_POST['showTeams'] == 'none')
		echo "Please select a conference";
	else {
		$scQuery = $mysqli->prepare("SELECT name, stadium FROM teams WHERE division=?");
		$scQuery->bind_param("s", $_POST['showTeams']);
		$scQuery->execute();
		$scQuery->bind_result($teamName, $teamStad);
		echo 
			"<table>
			<tr>
			<th>Team Name</th>
			<th>Stadium</th>
			</tr>";

		while($scQuery->fetch())
		{
			echo "<tr>";
			echo "<td>" . $teamName . "</td>";
			echo "<td>" . $teamStad . "</td>";
			echo "</tr>";
		}
		echo "</table>";
		$scQuery->close();
	}
}

if(isset($_POST['clearConfs']))
	echo '';

$mysqli->close();
?>
</div>

<div class="pShowDiv">
	<form method="post">
	<legend><b>View players</b></legend>
	<p>Number in table: <input name="limit" type="number" min="1" max="100" value="10"></p>
	<input type="submit" name="viewPlayers" value="View Players">
	<input type="submit" name="clearMates" value="Hide Table">
	</form>
	

<?php 
//mysqli connection
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","flessnej-db","R096iQvGYroJUKhJ","flessnej-db");
if (!$mysqli || $mysqli->connect_errno){
	echo "Connection error" . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

//show players
if(isset($_POST['viewPlayers'])){
	$smQuery = $mysqli->prepare("SELECT name, team FROM players ORDER BY team ASC LIMIT ?");
	$smQuery->bind_param("i", $_POST['limit']);
	$smQuery->execute();
	$smQuery->bind_result($showMates, $playTeam);
	echo 
		"<table>
		<tr>
		<th>Name</th>
		<th>Team</th>
		</tr>";

	while($smQuery->fetch())
	{
		echo "<tr>";
		echo "<td>" . $showMates . "</td>";
		echo "<td>" . $playTeam . "</td>";
		echo "</tr>";
	}
	echo "</table>";
	$smQuery->close();
}

if(isset($_POST['clearMates']))
	echo '';

$mysqli->close();
?>
</div>

<div class="breakDiv">
<hr>
</div>

<div class="sShowDiv">
	<form method="post">
	<legend><b>View Stadiums</b></legend>
	<p><input type="submit" name="showStads" value="View Stadiums">
	<input type="submit" name="clearStads" value="Hide Table"></p>
	</form>

<?php
//mysqli connection
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","flessnej-db","R096iQvGYroJUKhJ","flessnej-db");
if (!$mysqli || $mysqli->connect_errno){
	echo "Connection error" . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

if(isset($_POST['showStads'])){
	$stadOut = $mysqli->query("SELECT name, city, team FROM stadiums");
	echo "<table>
	<tr>
	<th> Stadium Name </th>
	<th> Location </th>
	<th> Home Team </th>
	</tr>";

	while($row = mysqli_fetch_array($stadOut))
	{
		echo "<tr>";
		echo "<td>" . $row['name'] . "</td>";
		echo "<td>" . $row['city'] . "</td>";
		echo "<td>" . $row['team'] . "</td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "<br>";
}
$mysqli->close();
?>
</div>

<div class="cShowDiv">
	<form method="post">
	<legend><b>View Coaches</b></legend>
	<p><input type="submit" name="showCoaches" value="View Coaches">
	<input type="submit" name="clearCoaches" value="Hide Table"></p>
	</form>

<?php
//mysqli connection
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","flessnej-db","R096iQvGYroJUKhJ","flessnej-db");
if (!$mysqli || $mysqli->connect_errno){
	echo "Connection error" . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

if(isset($_POST['showCoaches'])){
	$coachOut = $mysqli->query("SELECT name, team FROM coaches");
	echo "<table>
	<tr>
	<th> Coach Name </th>
	<th> Current Team </th>
	</tr>";

	while($row = mysqli_fetch_array($coachOut))
	{
		echo "<tr>";
		echo "<td>" . $row['name'] . "</td>";
		echo "<td>" . $row['team'] . "</td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "<br>";
}
$mysqli->close();
?>
</div>

<div class="breakDiv">
<hr>
</div>

<div class="ocShowDiv">
	<form method="post">
	<legend><b>Coach Former MLS Clubs</b></legend>
	<p>Enter a coach: <input name="ocShow" placeholder="to see former clubs."></p>
	<input type="submit" name="ocShowSub" value="Show Former Teams">
	<input type="submit" name="ocClear" value="Hide Table">
	</form>

<?php
//mysqli connection
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","flessnej-db","R096iQvGYroJUKhJ","flessnej-db");
if (!$mysqli || $mysqli->connect_errno){
	echo "Connection error" . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

if(isset($_POST['ocShowSub'])){
	$ocOut = $mysqli->prepare("SELECT name FROM teams t
		INNER JOIN coachTeams ct ON t.id=ct.tid
		WHERE ct.cid=(SELECT id FROM coaches WHERE name =?)");
	$ocOut->bind_param("s", $_POST['ocShow']);
	$ocOut->execute();
	$ocOut->bind_result($ocShowTeams);
	echo 
		"<table>
		<tr>
		<th> Former Teams of " . $_POST['ocShow'] . "</th>
		</tr>";

	while($ocOut->fetch())
	{
		echo "<tr>";
		echo "<td>" . $ocShowTeams . "</td>";
		echo "</tr>";
	}
	echo "</table>";
	$ocOut->close();
}

$mysqli->close();
?>
</div>

<div class="opShowDiv">
	<form method="post">
	<legend><b>Player Former MLS Clubs</b></legend>
	<p>Enter a player: <input name="opShow" placeholder="to see former clubs."></p>
	<input type="submit" name="opShowSub" value="Show Former Teams">
	<input type="submit" name="opClear" value="Hide Table">
	</form>

<?php
//mysqli connection
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","flessnej-db","R096iQvGYroJUKhJ","flessnej-db");
if (!$mysqli || $mysqli->connect_errno){
	echo "Connection error" . $mysqli->connect_errno . " " . $mysqli->connect_error;
}

if(isset($_POST['opShowSub'])){
	$opOut = $mysqli->prepare("SELECT name FROM teams t
		INNER JOIN playerTeams pt ON t.id=pt.tid
		WHERE pt.pid=(SELECT id FROM players WHERE name =?)");
	$opOut->bind_param("s", $_POST['opShow']);
	$opOut->execute();
	$opOut->bind_result($opShowTeams);
	echo 
		"<table>
		<tr>
		<th> Former Teams of " . $_POST['opShow'] . "</th>
		</tr>";

	while($opOut->fetch())
	{
		echo "<tr>";
		echo "<td>" . $opShowTeams . "</td>";
		echo "</tr>";
	}
	echo "</table>";
	$opOut->close();
}

$mysqli->close();
?>
</div>

<script> 
$.validate({
	modules : 'date',
	borderColorOnError: '#E42217'
}); 
</script>

</body></html>