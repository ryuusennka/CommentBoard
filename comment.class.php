<?php

/**
 * 用户能得到的信息（能提交的信息）都要进行过滤。
 * Class Comment
 */
class Comment
{
	private $data = array();

	function __construct($data)
	{
		$this->data = $data;
	}

	/**
	 * 过滤用户输入的内容的方法
	 * @param $arr
	 * @return bool
	 */
	static public function validate(&$arr)
	{
		/**
		 * 通过 PHP 内置过滤器filter
		 * filter_input 成功返回匹配的的值，失败返回false
		 */
		if (!($data['email'] = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL))) { // 判断是否是合法的 邮箱
			$errors['email'] = '请输入合法邮箱';
		}

		if (!($data['url'] = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL, array('options' => array('default' => 'https://baidu.com'))))) { // 判断是否是合法的 URL
			$errors['url'] = '请输入合法URL';
		}

		if (!($data['content'] = filter_input(INPUT_POST, 'content', FILTER_CALLBACK, array('options' => 'Comment::validate_str')))) { // 判断是否是合法的 content, 通过自定义的回调函数检验是否合法
			$errors['content'] = '请输入合法内容';
		}

		if (!($data['username'] = filter_input(INPUT_POST, 'username', FILTER_CALLBACK, array('options' => 'Comment::validate_str')))) { // 判断是否是合法的 username, 通过自定义的回调函数检验是否合法
			$errors['username'] = '请输入合法用户名';
		}

		$faceArr = array(
			'options' => array(
				'min_range' => 1, // 固定写法
				'max_range' => 5 // 固定写法
//				'default'=>100000 // 不合法的时候 filter_input 返回 100000
			)
		);

		if (!($data['face'] = filter_input(INPUT_POST, 'face', FILTER_VALIDATE_INT, $faceArr))) { // 判断是否是合法的 username, 通过自定义的回调函数检验是否合法
			$errors['face'] = '请选择合法头像';
		}

		if (!empty($errors)) {
			/**
			 * 有错误显示相应的错误
			 */
			$arr = $errors;
			return false;
		}
		/**
		 * 没有错误
		 */
		$arr = $data;
		$arr['email'] = strtolower(trim($arr['email']));
		return true;
	}

	/**
	 * 过滤用户输入的特殊字符
	 * @param string $str
	 * @return bool|string
	 */
	static public function validate_str($str)
	{
		if (mb_strlen($str, 'utf8') < 1) { // 如果没有输入内容
			return false;
		}
		$str = nl2br(htmlspecialchars($str, ENT_QUOTES)); // 1. 过滤用户输入的特殊字符 （双引号） 2. 把内容中的换行转换成 <br>
		return $str;
	}

	public function output()
	{
		if ($this->data['url']) {
			$link_start = "<a href='" . $this->data['url'] . "' target='_blank'>";

			$link_end = "</a>";
		}
		$dateStr = date("Y年m月d日 H:i:s", $this->data['pubTime']);
		$res = <<<EOF
		<div class='comment'>
			<div class='face'>
				{$link_start}
					<img width='50' height='50' src="img/{$this->data['face']}.jpg" alt="" />
				{$link_end}
			</div>
			<div class='username'>
				{$link_start}
				{$this->data['username']}
				{$link_end}		
			</div>
			<div class='date' title='发布于{$dateStr}'>
				{$dateStr}		
			</div>
			<p>{$this->data['content']}</p>		
		</div>
EOF;
		return $res;
	}

}