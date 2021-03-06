<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/functions.php";


// Log all of the entries to the form
// DEBUG ONLY
// foreach ($_GET as $data) {
// 	consoleLog($data);
// }


// if branch is set, get it from the form
if(isset($_GET['branch'])) {
	$branch_query = $_GET['branch'];
	$branch = $_GET['branch'];
} else {
	$branch = "*";
}

// if the rank is set, get it from the form
if (isset($_GET['rank'])) {
	$rank_query = ucwords($_GET['rank']);
	$rank = $_GET['rank'];
} else {
	$rank_query = "Enlisted";
}

// Format table names for use in query
switch(isset($branch)) {
	case ($branch == 'airforce'):
		$branch_query = 'airforce';
		break;
	case ($branch == 'army'):
		$branch_query = 'army';
		break;
	case ($branch == 'navy'):
		$branch_query = 'navy';
		break;
	case ($branch == 'marines'):
		$branch_query = 'marines';
		break;
	case ($branch == 'coastguard'):
		$branch_query = 'coastguard';
		break;
	default:
		$branch_query = 'army';
		break;
}

if(isset($_GET['series']) && $branch = "army") {
	if ($_GET['series'] == "") {
		unset($_GET['series']);
	} else {
		$series_query = $_GET['series'] . "%";
		$series = $_GET['series'];
	}
	
}


// Only if the series is set, use the series data in the query
if(isset($series_query)) {
	$query = 'SELECT * FROM "' . $branch_query . '" WHERE "rank" LIKE "%' . $rank_query . '%" AND "mos" LIKE "' . $series_query . '";';
	consoleLog("Query with series used");
	consoleLog("Query: " . $query);
} elseif(isset($rank_query)) {
	$query = 'SELECT * FROM "' . $branch_query . '" WHERE "rank" LIKE "%' . $rank_query . '%";';
	consoleLog("Query without series used");
	consoleLog("Query: " . $query);
}


$dir = 'sqlite:db/mos-db.db';
$dbh  = new PDO($dir) or die("Cannot open the database");

?>
<html lang="en">

<head>
	<title>Military Job Consideration Tool</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS v5.0.2 -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
		integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link rel="stylesheet" href="/style.css">
	<script src="/showSeries.js"></script>
	<?php
		include_once $_SERVER['DOCUMENT_ROOT'] . "/elements/favicon.php";
	?>
</head>

<body>
	<nav class="navbar navbar-expand-sm navbar-light bg-light">
		<div class="container-fluid">
			<div class="navbar-brand">Military Job Search</div>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navab"
				aria-controls="navab" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navab">
				<div class="navbar-nav">
					<a class="nav-link active" aria-current="page" href="/">Home</a>
					<a class="nav-link" aria-current="page" href="/bases/">Bases</a>
					<a class="nav-link" aria-current="page" href="/pay/">Pay Scales</a>
					<?php
						include $_SERVER['DOCUMENT_ROOT'] . "/elements/branch_dropdown.php";
					?>
				</div>
			</div>
			<span class="navbar-text">
				Last Updated: 20220602
			</span>
		</div>
	</nav>

	<form action="<?php echo $_SERVER['PHP_SELF']?>" method="GET" class="form-horizontal">
		<div class="container">
			<div class="row">
				<div class="col-1"></div>
				<div class="col-10">
					<section>
						<br>
						<small class="text-muted">Select the branch you are interested in</small>
						<div class="form-check">
							<input class="form-check-input" type="radio" value="airforce" id="airforce" name="branch">
							<label class="form-check-label" for="airforce">
								<i class="fa fa-fighter-jet" aria-hidden="true"></i> Air Force
							</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="radio" value="army" id="army" name="branch">
							<label class="form-check-label" for="army">
								<i class="fa fa-star" aria-hidden="true"></i> Army
							</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="radio" value="navy" id="navy" name="branch">
							<label class="form-check-label" for="navy">
								<i class="fa fa-ship" aria-hidden="true"></i> Navy
							</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="radio" value="marines" id="marines" name="branch">
							<label class="form-check-label" for="marines">
								<i class="fa fa-globe-americas" aria-hidden="true"></i> Marines
							</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="radio" value="coastguard" id="coastguard"
								name="branch">
							<label class="form-check-label" for="coastguard">
								<i class="fa fa-life-ring" aria-hidden="true"></i> Coast Guard
							</label>
						</div>
					</section>
					<section>
						<small class="text-muted">Select type of rank held</small>
						<div class="form-check">
							<input class="form-check-input" type="radio" value="enlisted" id="enlisted" name="rank">
							<label class="form-check-label" for="enlisted">
								<i class="fa fa-warehouse" aria-hidden="true"></i> Enlisted
							</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="radio" value="officer" id="officer" name="rank">
							<label class="form-check-label" for="officer">
								<i class="fa fa-graduation-cap" aria-hidden="true"></i> Officer
							</label>
						</div>
					</section>
					<section id="seriesSection">
						<small class="text-muted">Type in the series code (Army only; Optional)</small>
						<div class="form-check sm">
							<input type="number" class="form-control" name="series" placeholder="Army Series Code"
								aria-label="Army Series Code">
						</div>
					</section>
					<section>
						<br>
						<button type="submit" class="btn btn-primary">Submit</button>
					</section>
				</div>
				<div class="col-1"></div>
			</div>
		</div>
	</form>
	<hr>
	<section>
		<div class="container">
			<div class="row">
				<div class="col-1"></div>
				<div class="col-10">
					<div class="text-secondary">
						<small>You chose:</small>
					</div>
					<?php
						$branch_pretty = ucwords($branch);
						$rank_pretty = ucwords($rank);

						echo "<b>Branch: </b><i>" . $branch_pretty . "</i><br>";
						if (isset($rank_pretty) && $rank_pretty != "") {
							echo "<b>Rank: </b><i>" . $rank_pretty . "</i><br>";
						}
						echo "<b>Query: </b><code>" . $query . "</code><br>";
						if($branch_pretty == "Army"){
							echo "<b>Series: </b><i>" . $series . "</i><br>";
						}
						echo "<b>Result: </b>";
						?>
					<table class="table table-secondary table-striped table-hover">
						<thead>
							<tr>
								<th scope="col">Code</th>
								<th scope="col">Job Title</th>
								<th scope="col">Rank held</th>
							</tr>
						</thead>
						<?php
						
						foreach ($dbh->query($query) as $row)
							{
								// DEBUG ONLY
								// consoleLog($row);
								echo "<tr><th scope=\"row\">" . $row[0] . "</th><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>";
							}
						$dbh = null;
						
						?>
					</table>
				</div>
				<div class="col-1"></div>
			</div>
		</div>
	</section>
	<hr>
	<?php
		include $_SERVER['DOCUMENT_ROOT'] . "/elements/footer.php";
	?>
</body>

</html>