<?php
require_once('/func.php');

    /* Если активирована кнопка "Отправить" */	
	if (isset($_REQUEST['send'])) {
		//Значения из POST
		$title = text($_POST['title']);
		$author = text($_POST['author']);
		$description = text($_POST['description']);
		$creation_date = strtotime($_POST['calendar']);
		
		
		/* Проверка заполнения */
		if (empty($title)) {  
			$err = 'Введите заголовок';
		}
		elseif (empty($author)) { 
			$err = 'Введите имя автора';
		}
		elseif (empty($description)) { 
			$err = 'Введите текст описания';
		}
		elseif (strlen($description) > 200) {
			$err = 'Длина описания должна быть не больше 200 символов';
		}
		elseif (strlen($title) > 50) {
			$err = 'Длина заголовка должна быть не больше 50 символов';
		}
		elseif (strlen($author) > 50) {
			$err = 'Имя автора должно быть не длиннее 50 символов';
		}


		##Если поля заполнены верно##
		if (!$err) {

			/* Записываем данные в файл */
			$sql = "INSERT INTO entries(title, author, description, creation_date) VALUES (:title, :author, :description, :creation_date)";
		
			$stmt = $pdo->prepare($sql);
		
			$stmt->bindParam(':title', $title);
			$stmt->bindParam(':author', $author);
			$stmt->bindParam(':description', $description);
			$stmt->bindParam(':creation_date', $creation_date);
		
			$stmt->execute(); 
		
			$_SESSION['msg'] = 'Post added';
			header('location: /index.php');
		
		}
		else {
			echo '<p align="center" style="color:#ff0000; font-weight:bold; background:#ffffff;">'.$err.'</p>';
		}
		
	}
	
	/* Если активирована кнопка "Отмена" */
	if(isset($_POST['cancel'])) {
		header('location: /index.php');
	}

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
          <div class="row"><p class="text-center">Enter your text in text fields below</p></div>
        
		<div clas="row">
         <form action="" method="post">
                    <div class="form-group">
                    <label for="exampleInputName">Title</label>
                    <input type="text" name="title" class="form-control" id="exampleInputName" placeholder="Enter Title Here">
                </div>
				<div class="form-group">
                    <label for="exampleInputName">Author</label>
                    <input type="text" name="author" class="form-control" id="exampleInputName" placeholder="Enter Author Here">
                </div>
                <div class="form-group">
                    <label for="exampleInputText">Description</label>
                    <textarea name="description" class="form-control" rows="5" id="exampleInputText" placeholder="Enter Description Here"></textarea>
                </div>
				<div class="form-group">
                    <label for="exampleInputDate">Creation Date</label>
                    <input type="datetime-local" name="calendar" class="form-control" id="exampleInputDate" value="<?php echo date('Y-m-d\TH:i'); ?>">
                </div>
                <button type="submit" name="send" class="btn btn-default">Send</button>
				<button type="submit" name="cancel" class="btn btn-default">Cancel</button>
                </form>
		</div>
		<div clas="row"><p class="text-center"> </p></div>
		<div clas="row">
		</div>  
		
		
      </div>
	  <div id="push"></div>
	</div>
	  
	  <div id="footer">
      <div class="container">
        <p align="center">Copyright 2017</p>
      </div>
    </div>
	  
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>