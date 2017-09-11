<?php
require_once('/func.php');

if(isset($_SESSION['msg'])) {
	$msg = text($_SESSION['msg']);
	echo '<p align="center">'.$msg.'</p>';
	unset($_SESSION['msg']);
}
	
	/* Если активирована кнопка "Удалить" */
	if (isset($_GET['post_id'])) {
		$post_id = $_GET['post_id'];
		$stmt = $pdo->prepare('DELETE FROM entries WHERE id = :id');
		$stmt->execute([':id' => $post_id]);
		
		$_SESSION['msg'] = 'Post deleted';
		header('location: /index.php');
	}
	
	/* Если активирована кнопка "Редактировать" */
	if (isset($_GET['edit_post_id'])) {
		$_SESSION['edit_post'] = $_GET['edit_post_id'];
		
		header('location: /edit.php');
	}
	
	
	
		
		/* Подсчитываем общее количество записей в базе данных */
        $stmt = $pdo->query('SELECT * FROM entries');
		$records_count = $stmt->rowCount();
		
		//Количество записей выводимых на страницу
		$records_on_page = 5;
		
		//Общее количество страниц
		$pages_quantity = ceil($records_count/$records_on_page);
		
		// Если параметр не определен, то текущая страница равна последней странице
		$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

		// Если текущая страница меньше единицы, то страница равна 1
		if ($current_page < 1)
		{
			$current_page = 1;
		}
		// Если текущая страница больше общего количества страница, то текущая страница равна количеству страниц
		elseif ($current_page > $pages_quantity)
		{
		$current_page = $pages_quantity;
		}
		
		// Начать получение данных от числа (текущая страница - 1) * количество записей на странице
		$start_from = ($current_page - 1) * $records_on_page;
					
			
?> 

		
				
<!DOCTYPE html>
<html>
  <head>
    <title>Гостевая книга</title>
    <!-- Bootstrap -->
    <link href="/css/bootstrap.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
  </head>
  <body>
  <div id="wrap">
      <div class="container">
        <header class="row">
            <h2><p class="text-center"><a href="/index.php">Address Book</a></p></h2>
        </header>
          <div class="row"><p class="text-center">Hello! Click the button below to add a new post</p></div>
        
		<div clas="row"><p class="text-center"></p></div>
		<div clas="row">
	<?php 
	echo '<table class="table"><tbody><tr><p align="center"><a href="/add.php"><button>Add New Post</button></a></p></tr></tbody></table>';

	$stmt = $pdo->prepare('SELECT * from entries ORDER BY creation_date DESC LIMIT :start, :quantity');
	$stmt->bindParam(':start', $start_from, PDO::PARAM_INT);
	$stmt->bindParam(':quantity', $records_on_page, PDO::PARAM_INT);
		
	$stmt->execute(); 
	

	foreach ($stmt as $row)
	{
		echo '<table class="table"><tbody>';
		echo '<tr><th>'.$row['title'].'</th></tr>';
		echo '<tr><td>Posted By '.$row['author'].' · '.date('d M Y H:i', $row['creation_date']).'</td></tr>';
        echo "<tr><td>".$row['description']."</td></tr>";
		echo '<tr><td><a href="?edit_post_id='.$row['id'].'">Редактировать</a> <a href="?post_id='.$row['id'].'">Удалить</a></td></tr>';
		echo '</tbody></table>';
	}
	echo "<table class=\"table\"><tbody><tr><th></th></tr></tbody></table>";
	?>
		</div>  
		
      </div>
	 
		<div id="push"></div>
	</div>  
	
	<div id="footer">
      <div class="container">
		<?php 
		echo "<div class=\"row\"><p class=\"text-center\">";
			for ($page = 1; $page <= $pages_quantity; $page++)
			{
				if ($page == $current_page)
			{
			echo '<strong>'.$page.'</strong> &nbsp;';
			}
			else
			{
			echo '<a href="?page='.$page.'">'.$page.'</a> &nbsp;';
			}
		}
		echo "</p></div>";
		?>
        <p align="center">Copyright 2017</p>
      </div>
    </div>
	
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>