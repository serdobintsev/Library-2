<?
if($_POST)
{
	if($action === 'add')
		$query = $dbh->prepare("INSERT INTO publishing_house(Id, Name, Country, Address, year_of_foundation) VALUES (NULL, (:name), (:country), (:address), (:year))");
	else
	{
		$query = $dbh->prepare("UPDATE publishing_house SET Name=(:name), Country=(:country), Address=(:address), year_of_foundation=(:year) WHERE Id = (:id)");
		$query->bindParam(':id', $id);
	}
	$query->bindParam(':name', $_POST['name']);
	$query->bindParam(':year', $_POST['year']);
	$query->bindParam(':address', $_POST['address']);
	$query->bindParam(':country', $_POST['country']);
	
	$query->execute();
}
if($action == null){ ?>

<div class="row" style="padding: 20px;">

	<?php
	foreach($dbh->query("SELECT publishing_house.Id, publishing_house.Name, countries.Name as CountryName, Country, Address, year_of_foundation FROM publishing_house INNER JOIN countries ON Country = countries.Id") as $publishing_house) : ?>
	<div class="col-md-3">
		<div class="panel panel-info">
			<div class="panel-heading">
				<?php echo 'Издание <strong>' . $publishing_house['Name'] . '</strong>'; ?>
			</div>
			<div class="panel-body">
				<?php echo 'Основано в <strong>' . $publishing_house['year_of_foundation'] . '</strong> году, страна <strong>' . $publishing_house['CountryName'] . '</strong>.' .
					'<br/>' . $publishing_house['Address']; ?> <br/>
				<a href="/publishing_house?action=edit&id=<?php echo $publishing_house['Id']; ?>" class="btn btn-info btn-sm">Редактировать</a>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>
<?php
}
else
{
	if($action === 'edit')
	{
		$query = $dbh->prepare("SELECT * from publishing_house where Id = ?");
		$query->execute(array($id));
		$publishing_house = $query->fetch();
	}
	$countries = $dbh->query("SELECT Id, Name FROM countries");
?>
<div class="container">
<form method="post" id="ph_send">
	<div class="form-group">
		<label>Назвние издания:</label>
		<input type="text" class="form-control" name="name" value="<?php echo $publishing_house['Name']; ?>"/>
	</div>
	<div class="form-group">
		<label>Год основания:</label>
		<input type="number" class="form-control" name="year" value="<?php echo $publishing_house['year_of_foundation']; ?>"/>
	</div>
	<div class="form-group">
		<label>Адрес:</label>
		<textarea name="address" class="form-control"><?php echo $publishing_house['Address']; ?></textarea>
	</div>
<?php
	print_select($countries, 'country', $publishing_house['Country']);
	echo "<br/>";
?>
<input type="submit" onclick="send('/publishing_house?action=<?php echo $action . (($id !== null) ? '&id=' . $id : ''); ?>', '#ph_send'); return false;" />
</form>
</div>
<?php
}