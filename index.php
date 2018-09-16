<?php
function print_select($collection, $name, $selected_id = null)
{
	echo "<select class=\"form-control\" name=\"$name\">";
	foreach($collection as $item)
		echo '<option ' . (($selected_id != null && $selected_id == $item['Id']) ? 'selected ' : '') . 'value="' . $item['Id'] . '">' . $item['Name'] . '</option>' . "\n";
	echo "</select>";
}
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" crossorigin="anonymous">
	<title>Библиотека</title>
</head>
<body>
	<div class="navbar navbar-inverse">
      <div class="container">
        <div class="navbar-header">
          <button class="navbar-toggle" data-toggle="collapse" data-target="#menu">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">Библиотека</a>
      </div>
      <div class="collapse navbar-collapse" id="menu">
          <ul class="nav navbar-nav">
          	<li class="dropdown">
          		<a href="#" class="dropdown-toggle" data-toggle="dropdown">Просмотреть</a>
          		<ul class="dropdown-menu">
          			<li><a href="/books">Книги</a></li>
          			<li><a href="/authors">Авторов</a></li>
          			<li><a href="/genres">Жанры</a></li>
          			<li><a href="/publishing_house">Издания</a></li>
          		</ul>
          	</li>
          	<li class="dropdown">
          		<a href="#" class="dropdown-toggle" data-toggle="dropdown">Добавить</a>
          		<ul class="dropdown-menu">
          			<li><a href="/books?action=add">Книгу</a></li>
          			<li><a href="/authors?action=add">Автора</a></li>
          			<li><a href="/genres?action=add">Жанр</a></li>
          			<li><a href="/publishing_house?action=add">Издание</a></li>
          		</ul>
          	</li>
          </ul>
        </div>
      </div>
    </div>
		

<?php
	$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$uri_segments = explode('/', $uri_path);
	$table = null;
	if($_GET['action'] && ($_GET['action'] === 'add' || $_GET['action'] === 'edit'))
	{
		$action = $_GET['action'];
		if($action === 'edit')
			if($_GET['id'])
				$id = $_GET['id'];
			else
				throw new exception('Не передан id');
	}
	
	if(file_exists($uri_segments[1] . '.php'))
	{
		require_once "connection.php";
		$dbh = new PDO($dsn, $user, $password, $options);
		require_once $uri_segments[1] . '.php';
	}
?><script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script type="text/javascript">
	
	function send(_url, id)
	{
		var formData = $(id).serialize();
		$.ajax({
			url: _url,
			method: 'POST',
			data: formData,
			success: function(data)
			{
				alert("Данные успешно сохранены!");
			}
		});
	}

</script>
</body>
</html>