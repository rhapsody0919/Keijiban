<?php
/*セッションスタート*/
session_start();
try {
	require('db_connect.php');
	$dbh = connectDatabase();
	$get_post_sql = 'SELECT `posts`.`id`,
					`member_id`,
					`members`.`name`,
					`title`,
					`content`,
					DATE_FORMAT(`create_datetime`, "%Y-%m-%d %H:%i") AS "create_datetime"
					FROM `posts`
					INNER JOIN `members`
					ON `member_id` = `members`.`id`
					WHERE `deleted_flag` = FALSE';
	$get_post_stm = $dbh->query($get_post_sql);
} catch (PDOException $e) {
	$exception_msg = 'ERROR:' . $e->getMessage();
	exit($exception_msg);
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>プロサー掲示板</title>
</head>
<body>
<h1>プロサー掲示板</h1>
<!--新規投稿へのリンク-->
<a href="new_post_form.php">新規投稿</a>
<!--セッション情報がある場合-->
<?php if (isset($_SESSION['member'])): ?>
<p>こんにちは、<?php echo $_SESSION['member']['user_name']; ?>さん</p>
<a href="./logout.php">ログアウト</a>
<?php else: ?>
<!--セッション情報がない場合-->
<a href="./login_form.php">ログイン</a>
<?php endif; ?>
<h2>投稿一覧</h2>
<!--テーブル作成-->
<table>
<tr>
<th>投稿ID</th>
<th>投稿者名</th>
<th>タイトル</th>
<th>本文</th>
<th>投稿日時</th>
</tr>
<?php foreach ($get_post_stm as $post): ?>
<tr>
<td><?php echo $post['id']; ?></td>
<td><?php echo $post['name']; ?></td>
<td>
<?php echo $post['title']; ?>
<!--セッション情報のIDと会員IDが一致している場合-->
<?php if (isset($_SESSION['member']) && $_SESSION['member']['user_id'] === $post['member_id']): ?>
: <a href="./post_edit_form.php?post_id=<?php echo $post['id']; ?>">編集</a>
: <a href="./post_delete.php?post_id=<?php echo $post['id']; ?>">削除</a>
<?php endif; ?>
</td>
<td><?php echo $post['content']; ?></td>
<td><?php echo $post['create_datetime']; ?></td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>

