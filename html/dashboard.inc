<?php
require_once('./resource/MySQL/MySQL.php');
$mysql = new MySQL();
$photosCount = $mysql->getPhotosCount($_SESSION['response']->id);
$photos = $mysql->getPhotos($_SESSION['response']->id);
$recentPhotos = $mysql->getRecentPhotos($_SESSION['response']->id);
?>
<!DOCTYPE html>
<html lang="ja-JP">
	<head>
		<meta charset="utf-8">
		<meta name="author" content="reinforchu">
		<meta name="description" content="Photo upload with twitter">
		<meta name="keywords" content="リインフォース,reinforchu,twitter">
		<meta name="robots" content="index,follow,noarchive">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>pic.reinforce.tv</title>
		<link rel="shortcut icon" href="http://xn--eckk2c4f8d7b7c.jp/schwertkreuz.ico" type="image/vnd.microsoft.icon">
		<link href="http://file.reinforce.tv/bootstrap/v2.1.1/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" href="./css/common.css" type="text/css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script src="http://file.reinforce.tv/bootstrap/v2.1.1/js/bootstrap.min.js"></script>
		<script src="http://file.reinforce.tv/bootstrap/v2.1.1/assets/js/bootstrap-transition.js"></script>
		<script src="http://file.reinforce.tv/bootstrap/v2.1.1/assets/js/bootstrap-dropdown.js"></script>
		<script src="http://file.reinforce.tv/bootstrap/v2.1.1/assets/js/bootstrap-collapse.js"></script>
		<script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script><!-- hatena bookmark -->
		<style type="text/css">
			body {
				color: #072766;
				background: transparent url('<?php print($_SESSION['response']->profile_background_image_url_https); ?>') no-repeat;
				background-color: #<?php print($_SESSION['response']->profile_background_color); ?>;
			}
		</style>
		<link href="http://file.reinforce.tv/bootstrap/v2.1.1/css/bootstrap-responsive.min.css" rel="stylesheet">

		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>
	<body>
		<!-- facebook SDK -->
		<div id="fb-root"></div>
			<script>(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/ja_JP/all.js#xfbml=1";
				fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>
		<!-- End of facebook SDK -->

		<div class="navbar navbar-inverse navbar-fixed-top BarBoxShadow">
			<div class="navbar-inner">
				<div class="container">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="http://pic.reinforce.tv/">pic.reinforce.tv</a>
					<div class="nav-collapse collapse">
						<ul class="nav">
							<li><a href="http://pic.reinforce.tv/" title="home" target="_self">Home</a></li>
							<li><a href="http://pic.reinforce.tv/plugin/canvas/canvas.html" title="Drawing" target="_blank">お絵かき</a></li>
							<li><a href="http://pic.reinforce.tv/?wipe=1" title="ログアウト" target="_self">Logout</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>

	<div class="hero-unit anzu summary" style="position: relative; top:-15px; left: auto;">
		<div class="row box" style="position: relative; top:-25px; left: auto;">
			<div class="span4">
				<img align="left" class="img-polaroid" src="<?php print($_SESSION['response']->profile_image_url); ?>">
				<?php print("<p><span  style=\"font-size:30px; font-weight:bold;\">&nbsp;{$_SESSION['response']->name}</span><br>"); ?>
				<?php print("&nbsp;&nbsp;<a href=\"https://twitter.com/{$_SESSION['response']->screen_name}\" target=\"_blank\">@{$_SESSION['response']->screen_name}</a><br>"); ?>
				<?php print("{$_SESSION['response']->description}</p>"); ?>
				<br clear="all">
			</div>
			<div class="span4">
				<?php print("<h3>{$photosCount}&nbsp;Photos</h3>"); ?>
				<?php
					while ($row = $recentPhotos->fetch_assoc()) 
					print("<a href=\"http://pic.reinforce.tv/event/view/?{$row['imgid']}\"><img hspace=\"3\" height=\"80px\" width=\"80px\" class=\"img-polaroid ImgBoxShadow\" src=\"data:image/jpg;base64,{$row['thumb']}\"></a>");
				?>
			</div>
		</div>
	</div><!-- hero-unit -->
	
	<div class="hero-unit dashboard anzu" style="position: relative; top:-45px; left: auto;">
		<div class="tabbable">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#photos" data-toggle="tab"><i class="icon-user"></i>ギャラリー</a></li>
				<li><a href="#upload" data-toggle="tab"><i class="icon-heart"></i>アップロード</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="photos">
					<?php
					$count = 0;
					while ($row = $photos->fetch_assoc()) {
						$date = date("Y/m/d H:i:s", $row['date']);
						print("<a href=\"http://pic.reinforce.tv/event/view/?{$row['imgid']}\"><img hspace=\"6\" vspace=\"6\" align=\"left\" class=\"img-polaroid ImgBoxShadow\" src=\"data:image/jpg;base64,{$row['thumb']}\"></a>\n");
						$count++;
						if ($count > 3) { $count = 0; print("<br clear=\"all\">\n"); }
					}
					?>
				</div><!-- tab-pane -->
				<div class="tab-pane" id="upload">
					<form id="fileupload" action="http://pic.reinforce.tv/event/upload/index.php" method="POST" enctype="multipart/form-data">
						<div class="row fileupload-buttonbar">
							<div class="span8">
								<fieldset>
									<h2>Select photo</h2>
									<span class="btn btn-success fileinput-button">
										<i class="icon-plus icon-white"></i>
										<span>ファイルの選択</span>
										<input type="file" name="file" required>
									</span>
									<h2>Caption</h2>
									<div class="input-append">
										<textarea class="span3" id="appendedInput" placeholder="キャプション" type="text" name="description" maxlength="80"></textarea><br>
									</div>
									<h2>Post&nbsp;Options</h2>
									<label><input type="checkbox" name="tweet" value="1" checked>&nbsp;Twitterへ共有する</label>
									<br>
									<select class="whitelight" name="ac" style="width:150px;">
										<option value="0" selected>一般公開</option>
										<option value="1">フォロワーのみ</option>
										<option value="2">friendのみ</option>
										<option value="3">非公開</option>
									</select>
									<br>
									<button type="submit" class="btn btn-primary start">
									<i class="icon-upload icon-white"></i>
									<span>アップロード</span>
									</button>
								</fieldset>
							</div>
						</div>
					</form>
				</div><!-- tab-pane -->
			</div><!-- tab-content -->
		</div><!-- tabbable -->
	</div><!-- hero-unit -->

	<div class="hero-unit footer anzu" style="position: relative; top:-75px; left: auto;">
		<footer class="row box">
			<div class="span2">
				<!-- twitter Share button -->
				<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://pic.reinforce.tv/" data-text="twitRein -" data-lang="ja" data-size="small" data-hashtags="twitRein">Share!</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
				<!-- End of twitter Share button -->
			</div>
			<div class="span2">
				<!-- facebook like button -->
				<div class="fb-like" data-href="http://pic.reinforce.tv/" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>
				<!-- End of facebook like button -->
			</div>
			<div class="span2">
				<!-- hatena bookmark -->
				<a href="http://b.hatena.ne.jp/entry/http://pic.reinforce.tv/" class="hatena-bookmark-button" data-hatena-bookmark-title="twitRein" data-hatena-bookmark-layout="standard" title="このエントリーをはてなブックマークに追加"><img src="http://b.st-hatena.com/images/entry-button/button-only.gif" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" /></a>
				<!-- End of hatena bookmark -->
			</div>
			<div class="span2">
				<a href="http://www8.plala.or.jp/p_dolce/" title="あんずもじ" target="_blank"><img src="http://xn--eckk2c4f8d7b7c.jp/files/fontba30.gif" alt="あんずもじ" width="30" height="30"></a>
			</div>
			<div class="span2">
				<img alt="Contact us" src="http://file.reinforce.tv/images/reinforchu_gmail_com.png">
			</div>
		</footer>
	</div>

	</body>
</html>