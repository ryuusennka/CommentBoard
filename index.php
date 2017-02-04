<?php
require_once 'connect.php';
require_once 'comment.class.php';

$sql = "SELECT * FROM comments";
$res = $mysqli->query($sql);
$comments = array();
if ($res && $res->num_rows > 0) {
	while($row = $res->fetch_assoc()) {
		$comments[] = new Comment($row);
	}
} else {
	// 暂无数据
}

?>
<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
	<title>Document</title>
	<link rel="stylesheet" type="text/css" href="./style/style.css"/>
	<script src="./script/jquery.min.js"></script>
</head>
<body>
<h1>慕课网评论系统</h1>
<div id='main'>
	<?php
		foreach($comments as $comment) {
			echo $comment->output();
		}
	?>
	<div id='addCommentContainer'>
		<form id="addCommentForm" method="post" action="">
			<div>
				<label for="username">昵称</label>
				<input type="text" name="username" id="username" required placeholder='请输入您的昵称'/>

				<label for="face">头像</label>
				<div id='face'>
					<input type="radio" name="face" checked value="1"/><img src="./img/1.jpg"
					                                                        style="width: 50px;height: 50px;"/>
					<input type="radio" name="face" value="2"/><img src="./img/2.jpg"
					                                                style="width: 50px;height: 50px;"/>
					<input type="radio" name="face" value="3"/><img src="./img/3.jpg"
					                                                style="width: 50px;height: 50px;"/>
					<input type="radio" name="face" value="4"/><img src="./img/4.jpg"
					                                                style="width: 50px;height: 50px;"/>
					<input type="radio" name="face" value="5"/><img src="./img/5.jpg"
					                                                style="width: 50px;height: 50px;"/>
				</div>
				<label for="email">邮箱</label>
				<input type="email" name="email" id="email" required placeholder='请输入合法邮箱'/>

				<label for="url">个人博客</label>
				<input type="url" name="url" id="url"/>

				<label for="content">评论内容</label>
				<textarea name="content" id="content" cols="20" rows="5" required='required'
				          placeholder='请输入您的评论...'></textarea>
				<input type="submit" id="submit" value="发布评论"/>
			</div>
		</form>
	</div>
</div>
<script>
	$(function () {
		//此标志用于标志是否提交，防止多次提交
		var flag = false;
		//监测是否提交
		$('#addCommentForm').submit(function (e) {
			//阻止表单的自动提交
			e.preventDefault();
			if (flag) return false; // 第一次点击提交的时候不会进来这里
			flag = true;
			$('#submit').val('发布中');
			$('span.error').remove();
			//通过Ajax发送数据
			$.post('doAction.php', $(this).serialize(), function (msg) {

				flag = false;
				$('#submit').val('发布评论');
				if (msg.status) {
					$(msg.html).hide().insertBefore('#addCommentContainer').slideDown();
					$('#content').val('');
				} else {
					$.each(msg.errors, function (k, v) {
						$('label[for=' + k + ']').append('<span class="error">' + v + '</span>');
					});
				}
			}, 'json');
		});
	});
</script>
</body>
</html>