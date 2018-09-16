<?
if($_POST)
{
	if($action === 'add')
		$query = $dbh->prepare("INSERT INTO genres(Id, Name, Description) VALUES (NULL, (:name), (:description))");
	else
	{
		$query = $dbh->prepare("UPDATE genres SET Name = (:name), Description = (:description) WHERE Id = (:id)");
		$query->bindParam(':id', $id);
	}
	$query->bindParam(':name', $_POST['name']);
	$query->bindParam(':description', $_POST['description']);
	
	$query->execute();
}
if($action == null){ ?>

<div class="row" style="padding: 20px;">
	<?php
	foreach($dbh->query("SELECT * FROM genres") as $genre) : ?>

	<div class="col-md-3">
		<div class="panel panel-info">
			<div class="panel-heading">
				<?php echo 'Жанр <strong>' . $genre['Name'] . '</strong>';?>
			</div>
			<div class="panel-body">
				<?php echo $genre['Description']; ?> <br/>
				<a href="/genres?action=edit&id=<?php echo $genre['Id']; ?>" class="btn btn-info btn-sm">Редактировать</a>
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
		$query = $dbh->prepare("SELECT * from genres where Id = ?");
		$query->execute(array($id));
		$genre = $query->fetch();
	}	
?>
<form action="/genres?action=<?php echo $action . (($id !== null) ? '&id=' . $id : ''); ?>" method="post" id="genre_send">
	<div class="form-group">
		<label>Назвние жанра:</label>
		<input type="text" class="form-control" name="name" value="<?php echo $genre['Name']; ?>"/>
	</div>
	<div class="form-group">
		<label>Описание:</label>
		<textarea class="form-control" name="description"><?php echo $genre['Description']; ?></textarea>
	</div>
<input type="submit" onclick="send('/genres?action=<?php echo $action . (($id !== null) ? '&id=' . $id : ''); ?>', '#genre_send'); return false;"/>
</form>
<?php
}