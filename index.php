<? php


define( 'FILENAME', './messages.txt');
date_default_timezone_set('Asia/Tokyo');

$now_date = null;
$data  = null;
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();
$success_message = null;
$error_message = array();
$clean = array();



if( !empty($_POST['btn_submit']) ) {
	
	// 表示名の入力チェック
	if( empty($_POST['name']) ) {
		$error_message[] = '表示名を入力してください。';
	} else {
		$clean['name'] = htmlspecialchars( $_POST['name'], ENT_QUOTES);
	}
	
	// メッセージの入力チェック
	if( empty($_POST['message']) ) {
		$error_message[] = 'ひと言メッセージを入力してください。';
	} else {
		$clean['message'] = htmlspecialchars( $_POST['message'], ENT_QUOTES);
		$clean['message'] = preg_replace( '/\\r\\n|\\n|\\r/', '<br>', $clean['message']);
	}

	if( empty($error_message) ) {

		if( $file_handle = fopen( FILENAME, "a") ) {
	
		    // 書き込み日時
			$now_date = date("Y-m-d H:i:s");
		
			// 書き込むデータ
			$data = "'".$clean['name']."','".$clean['message']."','".$now_date."'\n";
		
			fwrite( $file_handle, $data);
		
			fclose( $file_handle);
	
			$success_message = 'メッセージを書き込みました。';
		}
	}
}



if( $file_handle = fopen( FILENAME, 'r')) {
  while( $data = fgets($file_handle)) {

    $split_data = preg_split( '/\'/', $data);

    $message = array(
        'name' => $split_data[1],
        'message' => $split_data[3],
        'post_date' => $split_data[5]
    );
    array_unshift( $message_array, $message);
  }

  fclose($file_handle);
}


?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>シフト管理</title>
  <link rel="stylesheet" href="css/styles.css">
</head>

<body>
  <script src="js/main.js"></script>
  <h1>シフト管理</h1>

  <?php if ( !empty($success_message)): ?>
  <p class="success_message"><?php echo $success_message; ?></p>
  <?php endif; ?>

  <?php if ( !empty($error_message)): ?>
    <ul>
    <?php foreach ($error_message as $value ): ?>
      <li><?php echo $value; ?></li>
    <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <form method="post">
  <div>
    <label for="name">名前</label><br>
    <input id="name"type="text" name="name">
  </div>
  <div>
    <label for="message">メッセージ</label><br>
    <textarea name="message" id="message" cols="50" rows="4"></textarea>
  </div>

  <input type="submit" name="btn_submit" value="書き込む">
  </form>

  <hr>

  <section>
    <?php if( !empty($message_array)): ?>
      <?php foreach( $message_array as $value ): ?>

        <article>

          <div>
            <h2> <?php echo $value['name']; ?> </h2>
            <time> <?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time>
          </div>
            
          <p> <?php echo $value['message']; ?> </p>
        </article>

          <?php endforeach ?>
          <?php endif ?>
      </section>

</body>
</html>
