<?
if($_POST)
{
	if($action === 'add')
		$query = $dbh->prepare("INSERT INTO books(Id, Name, year_of_writing, Description, genre_id, author_id, publishing_house_id)
								VALUES (NULL,(:name),(:year),(:description),(:genre_id),(:author_id),(:publishing_house_id))");
	else
	{
		$query = $dbh->prepare("UPDATE books SET Name = (:name), year_of_writing = (:year), Description = (:description),
												 genre_id = (:genre_id), author_id = (:author_id), publishing_house_id = (:publishing_house_id) WHERE Id = (:id)");
		$query->bindParam(':id', $id);
	}
	$query->bindParam(':name', $_POST['name']);
	$query->bindParam(':year', $_POST['year']);
	$query->bindParam(':description', $_POST['description']);
	$query->bindParam(':genre_id', $_POST['genres']);
	$query->bindParam(':author_id', $_POST['authors']);
	$query->bindParam(':publishing_house_id', $_POST['publishing_house']);
	
	$query->execute();
}
if($action == null){ ?>


<div class="row" style="padding: 20px;">
	<?php
	foreach($dbh->query("SELECT books.Id as books_id, books.Name as book_name, year_of_writing, books.Description as book_description, genres.Name as genre_name,
						concat(authors.FirstName, ' ', authors.LastName) as author_name, publishing_house.Name as publishing_house_name
						FROM books INNER JOIN genres ON genre_id = genres.Id INNER JOIN authors ON author_id = authors.Id INNER JOIN publishing_house ON publishing_house_id = publishing_house.Id") as $book) : ?>

	<div class="col-md-3">
		<div class="panel panel-info">
			<div class="panel-heading">
				<?php echo 'Книга <strong>' . $book['book_name'] . '</strong>';?>		
			</div>
		<div class="panel-body">
			<?php echo 'Написана в <strong>' . $book['year_of_writing'] . '</strong> году, автор <strong>' . $book['author_name'] . '</strong>, жанр <strong>' .
						$book['genre_name'] . '</strong>, издательство <strong>' . $book['publishing_house_name'] . '</strong><br/>' . $book['book_description']; ?> <br/>
			<a href="/books?action=edit&id=<?php echo $book['books_id']; ?>" class="btn btn-info btn-sm">Редактировать</a>
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
		$query = $dbh->prepare("SELECT * from books where Id = ?");
		$query->execute(array($id));
		$book = $query->fetch();
	}
	$genres = $dbh->query("SELECT Id, Name FROM genres");
	$authors = $dbh->query("SELECT Id, concat(FirstName, ' ', LastName) as Name FROM authors");
	$publishing_house = $dbh->query("SELECT Id, Name FROM publishing_house");	
?>
<div class="container">
<form method="post" id="book_send">
	<div class="form-group">
		<label>Назвние книги:</label>
		<input type="text" class="form-control" name="name" value="<?php echo $book['Name']; ?>"/>
	</div>
	<div class="form-group">
		<label>Год написания книги:</label>
		<input type="number" class="form-control" name="year" value="<?php echo $book['year_of_writing']; ?>"/>
	</div>
	<div class="form-group">
		<label>Описание книги:</label>
		<textarea class="form-control" name="description"><?php echo $book['Description']; ?></textarea>
	</div>
<?php
	print_select($genres, 'genres', $book['genre_id']);
	echo "<br/>";
	print_select($authors, 'authors', $book['author_id']);
	echo "<br/>";
	print_select($publishing_house, 'publishing_house', $book['publishing_house_id']);
	echo "<br/>";
?>
<input type="submit" onclick="send('/books?action=<?php echo $action . (($id !== null) ? '&id=' . $id : ''); ?>', '#book_send'); return false;">
</form>
</div>
<?php
}