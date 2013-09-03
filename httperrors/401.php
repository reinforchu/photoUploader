<?php header("HTTP/1.1 401 Unauthorized"); ?>
<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
		<meta name="author" content="reinforchu">
		<meta name="description" content="Server error">
		<meta name="keywords" content="リインフォース,reinforchu,reinforce">
		<meta name="robots" content="noindex,nofollow,noarchive">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>401 Authorization Required</title>
		<link rel="shortcut icon" href="http://xn--eckk2c4f8d7b7c.jp/schwertkreuz.ico" type="image/vnd.microsoft.icon">
		<link rel="stylesheet" href="http://xn--eckk2c4f8d7b7c.jp/files/bootstrap/index/css/bootstrap.min.css" type="text/css">
		<style type="text/css">
			body {
				color: #FFFFFF;
				background-color: #1173AA;
				margin-top: 100px;
				margin-bottom: 0px;
				margin-left: 300px;
				margin-right: 0px;
				line-height: 100%;
			}
			#description {
				font-size: 200%;
				font-family:"trebuchet MS", Verdana, sans-serif;
				letter-spacing: 0.025em;
				margin-left: 20px;
			}
			#guide {
				font-size: 110%;
				font-family:"trebuchet MS", Verdana, sans-serif;
				font-style: italic;
				letter-spacing: 0.01em;
				margin-left: 20px;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="span12">
				<img src="http://xn--eckk2c4f8d7b7c.jp/files/Reinforce-Zwei_Silhouette_white.png" width="106" height="128" border="0" title="Reinforce-Zwei Silhouette">
				<p><br></p>
				<p id="description">This server could not verify that you are authorized to access the document requested. Either you supplied the wrong credentials (e.g., bad password), or your browser doesn't understand how to supply the credentials required.</p>
				<p><br></p>
				<p id="guide"><?php print($_SERVER["SERVER_SOFTWARE"]); ?> Server at <?php print($_SERVER["SERVER_NAME"]); ?> Port <?php print($_SERVER["SERVER_PORT"]); ?></p>
			</div><!-- span12 -->
		</div><!-- container -->
	</body>
</html>