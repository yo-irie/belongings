<?php
/**
	 * XSS対策
	 * @param string $str 対象の文字列
	 * @return string 処理された文字列
	 */
	function h($str) 
	{
		return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
	}

	/**
	 * CSRF対策
	 * @param void
	 * return string $cstf_token
	 */
	function setToken()
	{
		$csrf_token = bin2hex(random_bytes(32));
		$_SESSION['csrf_token'] = $csrf_token;

		return $csrf_token;
	}

	/**
	 * HTTPホスト文字列からクエリ文字列部分を取り除く
	 * 
	 * @param string $host $_SERVER['REQUEST_URI']の値
	 * @return string クエリ文字列が取り除かれたホスト名
	 */
	function cleanHostString($host) {
	// クエリ文字列の開始文字'?'の位置を検索
		$queryPosition = strpos($host, '?');
		// クエリ文字列が存在する場合は取り除く
		if ($queryPosition !== false) {
				return substr($host, 0, $queryPosition) . '/';
		}
		// クエリ文字列が存在しない場合は元の文字列をそのまま返す
		return $host;
}
?>