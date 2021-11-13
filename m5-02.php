<?php
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        $sql = "CREATE TABLE IF NOT EXISTS tb_2"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date TEXT,"
    . "password TEXT"
    .");";
        $stmt = $pdo->query($sql);
   
 if (isset($_POST["edi"]) && strlen($_POST["passtwo"]) ) {
       $passtwo = $_POST["passtwo"];
       $edit=$_POST["edit"];
        $sql = 'SELECT * FROM tb_2 WHERE id=:id';
        $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
        $stmt->bindParam(':id', $edit, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
        $stmt->execute();    
         $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
     $passnew = $row['password'];
    }
      if($passnew == $passtwo) {
       $namenew = $row['name'];
       $commentnew = $row['comment'];
 }  
        }
           ?>

<!DOCTYPE html>
<html lang=ja>
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <?php
   
  /* $sql = 'DROP TABLE tb_2';
   $stmt = $pdo->query($sql); */

    
    
    $date = date('Y年m月d日 H:i:s');
    
    //送信ボタンが押されたとき
  if(!empty($_POST['submit'])) {
    $text = $_POST['text'];
    $comment = $_POST['comment'];
    $password = $_POST['password'];
    $editnum = $_POST['editnum'];
  }

  //削除ボタンが押されたとき
  if(!empty($_POST['delete'])) {
    $delete = $_POST['deletenum'];
    $passone = $_POST['passone'];
  }

  //編集ボタンが押されたとき
  if(!empty($_POST['edit'])) {
    $edit = $_POST['edit'];
    $passtwo = $_POST['passtwo'];
  }
  
  //削除機能
   if(!empty($delete)) {  
    $sql = 'SELECT * FROM tb_2 WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $delete, PDO::PARAM_INT); 
    $stmt->execute();
    $results = $stmt->fetchAll();
    foreach ($results as $row) {
        if ($passone == $row['password']){
            $sql = 'DELETE FROM tb_2 WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
            $stmt->execute();
            $sql = 'SELECT * FROM tb_2';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        echo $row['id'].' ';
        echo $row['name'].' ';
        echo $row['comment'].' ';
        echo $row['date'].'<br>';
        echo '<hr>';
        }
        }else{
            echo "パスワードが違います<br>";
        }
    }  
}

 //編集機能
/*  if(!empty($edi)) {
    $spl = 'SELECT * FORM tb_2 WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $edit,PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll();
    foreach($results as $row) {
      if($passtwo == $row['password']) {
        $editnum_form = $row['id'];
        $editname_form = $row['name'];
        $editcom_form = $row['comment'];
        $editpass_form = $row['password'];
      } else {

        echo "パスワードが違います";
      }
    }
  } */
  
  
   //名前とコメントとパスワードがあるなら
 if(!empty($text) && !empty($comment)){

  //編集番号が送信されたなら編集モード
  if(!empty($editnum)) {
      $sql = 'UPDATE tb_2 SET name=:name,comment=:comment,password=:password,date=:date WHERE id=:id';
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':name', $text, PDO::PARAM_STR);
      $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
      $stmt->bindParam(':password', $password, PDO::PARAM_STR);
      $stmt->bindParam(':date', $date, PDO::PARAM_STR);
      $stmt->bindParam(':id', $editnum, PDO::PARAM_INT);
      $stmt->execute();
    $sql = 'SELECT * FROM tb_2';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        echo $row['id'].' ';
        echo $row['name'].' ';
        echo $row['comment'].' ';
        echo $row['date'].'<br>';
        echo '<hr>';
}
  //編集番号が送信されてないなら追記モード
  }else{    
      $sql = $pdo -> prepare("INSERT INTO tb_2 (name, comment, password, date) VALUES (:name, :comment, :password, :date)");
      $sql -> bindParam(':name', $text, PDO::PARAM_STR);
      $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
      $sql -> bindParam(':password', $password, PDO::PARAM_STR);
      $sql -> bindParam(':date', $date, PDO::PARAM_STR);
      $sql -> execute();
        $sql = 'SELECT * FROM tb_2';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        echo $row['id'].' ';
        echo $row['name'].' ';
        echo $row['comment'].' ';
        echo $row['date'].'<br>';
        echo '<hr>';
  }
}

}

    
    
    ?>
    
     <form action="" method="post">
        name:
    <p><input type="text" name="text" value="<?php if(isset($edit)) {echo $namenew;} ?>"></p>   
        comment【好きな飲み物】:
    <p><input type="text" name="comment" value= "<?php if(isset($edit)) {echo $commentnew;} ?>"></p>
        password(必須):
    <p><input type="text" name="password" ></p>
    <input type="hidden" name="editnum" value="<?php if(isset($edit)) {echo $edit;} ?>">
    
     <p>   <input type="submit" name="submit"> </p>
        deletenumber:
        
    </form>
    
     <form action="" method="post">
    <p><input type="text" name="deletenum" ></p>
        password(必須):
    <p><input type="text" name="passone" required="required"></p>
     <p><input type="submit" name="delete" value="削除"></p>
        
    </form>
    
    <p><form action="" method="post"></p>
        edit:
    <p><input type="text" name="edit"></p>
        password(必須):
    <p><input type="text" name="passtwo" ></p>
     <input type="submit" name="edi" value="編集">
     
    </form>
    


</body>
</html>