<?php
header("Content-type: application/json");

require_once 'connect.php';
require_once 'comment.class.php';
$arr = array();

$res = Comment::validate($arr);
/**
 * 判断检查用户提交的数据是否合法
 */
if ($res) {
	/*
	 * 合法
	 * */
	$sql = "INSERT comments(username, email, url, face, content, pubTime) VALUES(?,?,?,?,?,?)";
	$stmt = $mysqli->prepare($sql);
	$arr['pubTime'] = time();
	$stmt->bind_param('sssisi', $arr['username'], $arr['email'], $arr['url'], $arr['face'], $arr['content'], $arr['pubTime']);
	$flag = $stmt->execute();
	$comment = new Comment($arr);
	echo json_encode(array('flag'=>$mysqli->error,'status' => 1, 'html' => $comment->output())); // 输出一个 json
} else {
	echo '{"status": 0, "errors": ' . json_encode($arr) . '}'; // 输出一个 json
}