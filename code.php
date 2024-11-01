<?php
define('TWO_DIMENSIONAL_DIR', dirname(__FILE__));
define('TWO_DIMENSIONAL_URL', str_replace('/code.php', '', 'http://'. $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']) );
define('QRCODE_PATH', '/png/');
require_once( TWO_DIMENSIONAL_DIR . '/qrcode.php');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<title><?php _e("Mobile two-dimensional code scanning"); ?></title>
<meta name="keywords" content="<?php _e("generate two-dimensional code, mobile two-dimensional code, make two-dimensional code");?>" />
<meta name="description" content="<?php _e("Two-dimensional code generated tools online");?>" />
<style>
	body { margin:0 auto; width:700px; background-color:#AEE2FA; text-align:center;}
	#all { margin-top:50px; font-size:18px;}
	#title { background-color:#FFF; line-height:60px; font-size:26px;height:60px; font-weight:bold;}
	#iform {}
	#now {}
	#now p{ height:40px; line-height:40px; overflow:hidden; width:100%;}
	#left { height:300px; width:400px; background-color:#FFF; float:left; margin-top:10px;padding-top:40px;overflow:hidden;}
	#content {height:200px;width:350px;}
	#right { height:340px; width:300px; overflow:hidden; background-color:#FFF; float:right; margin-top:10px;padding:40px auto;}
	.code {padding-top:70px;}
	.clear {clear:both;}
	#footer { color:gray; background-color:#FFF;line-height:30px; font-size:16px;height:30px;margin-top:10px;}
	#footer a{ color:gray; text-decoration:none;}
</style>

</head>

<!--
made: chenxue4076(chenxue4076@gmail.com,chenxue4076@163.com)
blog: http://blog.windigniter.com
demo: http://blog.windigniter.com/wp-content/plugins/two-dimensional-code/code.php
version: 1.2
-->

<body>
<div id="all">
   <div id="title"><?php _e("Two-dimensional code generated tools online");?></div>
<div id="left"><form id="iform" name="iform" method="post" action=""><textarea name="content" id="content"><?php echo $_POST['content']; ?></textarea><br />
<div id="now">
<p>
<?php _e("Input content here,");?><input name="go" type="submit" id="go" onclick="" value="<?php _e("Generate Now");?>" />
<input name="done" type="hidden" value="done" />
</p>
</div>
</form>
</div>

<div id="right">
<div class="code">
<?php 
if ($_POST['done']){
   if($_POST['content']){
	$c = $_POST['content'];

	$len = strlen($c);
	   if ($len <= 360){
	    $file = fopen("num.txt","r+");
	    flock($file,LOCK_EX);
	      if($file) {
	       $get_file = fgetss($file);
	       $t = $get_file+1;
	       $file2 = fopen("num.txt","w+");
	       fwrite($file2,$t);	
	       }
	    flock($file,LOCK_UN);
	    fclose($file);
	    fclose($file2);
	
	   QRcode::png($c, TWO_DIMENSIONAL_DIR . QRCODE_PATH .$t.'.png');	
	   $sc = urlencode($c);
	   echo '<img src="'.TWO_DIMENSIONAL_URL . QRCODE_PATH.$t.'.png" /><br />'.$c; 
	   }
	   else {
	     _e("Too large content.");
	   }	
    }
    else {
     _e("Empty content");
    }
}	
else {
  _e("Two-dimensional code will be displayed here.");
}
	?>
	</div>
</div>
</div>
<div class="clear"></div>
<div id="footer">&copy; 2014 windigniter.com V1.2 <a href="http://blog.windigniter.com/wp-content/uploads/2014/05/two-dimensional-code.zip" target="_blank">Download</a>
<p>This plugs based on <a href="http://phpqrcode.sourceforge.net/" target="_blank" >PHPQRCode</a></p>
</div>	
</body>
</html>
