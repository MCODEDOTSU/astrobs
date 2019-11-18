<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?=$this->templates->output_pagetitle()?></title>
		<link rel="shortcut icon" href="http://logos.net.ru/favicon.ico"></link>
		<?=$this->templates->output_metatags();?>
		<?=$this->templates->output_variables();?>
		<?=$this->templates->output_assets();?>
		<th:include></th:include>		
		<script type="text/javascript">
			tinyMCE.init({convert_urls : false});
		</script>
	</head>
	<body>

		<th:header></th:header>

		<th:left></th:left>

		<th:right></th:right>

		<th:footer></th:footer>

		<div id="cms_content">
			<th:content></th:content>
		</div>
		
	</body>
</html>
