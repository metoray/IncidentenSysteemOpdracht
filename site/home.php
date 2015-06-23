<?php
include 'include/connect.php';

$query = "SELECT * ";
$query .= "FROM incidenten" ;
$query .= " WHERE gebruiker_id = ".$_SESSION["user"]->id ;
$query .= " ORDER BY inc_id DESC";
$query .= " LIMIT 5";



$result = mysqli_query($con, $query);

//Zet de resultaten van de query in een associative array.
while ($incidenten_query = mysqli_fetch_assoc($result)) {
    $incidenten[] = array('IncidentId' => intval($incidenten_query["inc_id"]), 'StartDatum' => $incidenten_query["start_incident"], 'Omschrijving' => $incidenten_query["Omschrijving"],'status' => $incidenten_query["status"]);
}

echo "<table border=1><tr><td><b>Incident ID</b></td><td><b>Omschrijving</b></td><td><b>Aanmeld datum</b></td><td><b>Status</b></td></tr>";

foreach ($incidenten as $i) {
    echo"<tr><td>". "<a href=\"incidentinformation.php?IncidentId=". $i['IncidentId']. "\">" . $i['IncidentId']."</a>"."</td><td> ". $i['Omschrijving']."</td><td>". $i['StartDatum']."</td><td>". $i['status']."</td></tr>";
}
    
?>

    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="col-md-4 col-md-offset-2" style="padding-top: 100px;">
       
            <div class="panel-heading"><center><h1>Welkom <?php echo $_SESSION["user"]->name; ?></h1></center></div>
            <ul class="list-group">
                <a href="new_incident.php" class="btn btn-primary btn-lg btn-block" role="button">Incident melden</a>
                <a href="my_incidents.php" class="btn btn-primary btn-lg btn-block" role="button">uw gemelde incidenten bekijken</a>
                <?php if ($_SESSION["user"]->roleID == 4) {
                    echo '<a href="incidentenoverview.php" class="btn btn-primary btn-lg btn-block" role="button">Alle incidenten inzien</a>';}?>
            </ul>
        
    </div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
