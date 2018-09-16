<?
if($_POST)
{
	if($action === 'add')
		$query = $dbh->prepare("INSERT INTO authors(Id, FirstName, LastName, Birthday) VALUES (NULL, (:first_name), (:last_name), (:birthday))");
	else
	{
		$query = $dbh->prepare("UPDATE authors SET FirstName=(:first_name), LastName=(:last_name), Birthday=(:birthday) WHERE Id = (:id)");
		$query->bindParam(':id', $id);
	}
	$query->bindParam(':first_name', $_POST['first_name']);
	$query->bindParam(':last_name', $_POST['last_name']);
	$query->bindParam(':birthday', $_POST['birthday']);
	
	$query->execute();
}
if($action == null){ ?>


<div class="row" style="padding: 20px;">
	<?php
	foreach($dbh->query("SELECT * FROM authors") as $author) : ?>

	<div class="col-md-3">
		<div class="panel panel-info">
			<div class="panel-heading">
				<?php echo 'Автор <strong>' . $author['FirstName'] . ' '. $author['LastName'] . '</strong>'; ?>
			</div>
			<div class="panel-body">
				<?php echo 'День рождения <strong>' . $author['Birthday'] . '</strong>'; ?> <br/>
				<a href="/authors?action=edit&id=<?php echo $author['Id']; ?>" class="btn btn-info btn-sm">Редактировать</a>
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
		$query = $dbh->prepare("SELECT * from authors where Id = ?");
		$query->execute(array($id));
		$author = $query->fetch();
	}
?>
<div class="container">
<form method="post" id="author_send">
	<div class="form-group">
		<label>Имя автора:</label>
		<input type="text" class="form-control" name="first_name" value="<?php echo $author['FirstName']; ?>"/>
	</div>
	<div class="form-group">
		<label>Фамилия автора:</label>
		<input type="text" class="form-control" name="last_name" value="<?php echo $author['LastName']; ?>"/>
	</div>
	<div class="form-group">
		<label>День рождения:</label>
		<input type="date" class="form-control" name="birthday" value="<?php echo $author['Birthday']; ?>"/>
	</div>
<input type="submit" onclick="send('/authors?action=<?php echo $action . (($id !== null) ? '&id=' . $id : ''); ?>', '#author_send'); return false;">
</form>
</div>
<?php
}