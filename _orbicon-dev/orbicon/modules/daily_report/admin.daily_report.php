<?php
	include 'settings.php';
?>

<p>
<iframe src="<?php echo $url_path; ?>/site/mercury/daily_report.pdf?<?php echo uniqid(md5(rand()), true); ?>" width="100%" height="500px"></iframe>
</p>