<h1 style="width: 7em; margin-left: auto; margin-right: auto">Skybug Tracker</h1>
<div style="width:20em; margin-left: auto; margin-right: auto">
	<form action="submit.php" method="post">
		<fieldset>
			<label>
				Name:<br />
				<input type="text" name="name"/>
			</label>
			<br />
			<label>
				Description:<br />
				<textarea name="description" rows="4" cols="20"></textarea>
			</label>
			<br />
			<label>
				<input type="radio" name="kind" value="B" checked="checked"/>
				Bug Report
			</label>
			<br />
			<label>
				<input type="radio" name="kind" value="F"/>
				Feature Request
			</label>
			<br />
			<label>
				<input type="radio" name="kind" value="S"/>
				Skybug Specific
			</label>
			<br />
			<input type="submit" value="Add to Skybug"/>
		</fieldset>
	</form>
</div>

<form action="vote.php" method="post">
<table align="center" border="1px">
	<tr>
		<th rowspan=2>Name</th>
		<th rowspan=2>Kind</th>
		<th rowspan=2>Description</th>
		<th colspan=3>Priority</th>
	</tr>
	<tr>
		<th>High</th>
		<th />
		<th>Low</th>
	</tr>
	<?php
	require("server.php");

	if(mysqli_connect_errno()) {
		echo "Connection Failed: " . mysqli_connect_errno();
		exit();
	}
	
	if($stmt = $skybug -> prepare("SELECT ID, Name, Description, Kind, Likes, Votes FROM bugs ORDER BY Rate DESC LIMIT 50")) {
		$stmt -> execute();
		$stmt -> bind_result($id, $name, $description, $kind, $likes, $votes);
		while($stmt -> fetch()) {
			?>
			<tr>
				<td style="text-align:center">
					<?= stripslashes($name) ?>
					</td>
				<td style="text-align:center">
					<?=
						(($kind=="B")?"Bug":
						(($kind=="F")?"Feature":
						(($kind=="S")?"Skybug":
						$kind)))
					?>
				</td>
				<td style="text-align:center">
					<?= stripslashes($description) ?>
				</td>
				<td style="text-align:center">
					<input type="radio" name=<?= $id ?> value="+"/>
				</td>
				<td style="text-align:center; padding-left:4; padding-right:4">
					<?= $likes."/".$votes ?>
				</td>
				<td style="text-align:center">
					<input type="radio" name=<?= $id ?> value="-"/>
				</td>
			</tr>
			<?php
		}
		$stmt -> close();
	} else {
		
		?>
		<div style="text-align: center">
			There was an error fetching the bug table. Please try again, or contact a moderator.<br />
			<a href="index.php">return</a>
		</div>
		<?php
		
	}
	
	$skybug -> close();
	?>
	<tr>
		<td colspan=3 />
		<td colspan=3 style="text-align:center">
			<input type="submit" value="Vote"/>
			<input type="reset" value="Clear"/>
		</td>
	</tr>
</table>
