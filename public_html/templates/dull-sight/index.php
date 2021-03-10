<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
	<head>
		<?=$this->templates->output_metatags();?>
		<title><?=$this->templates->output_pagetitle();?></title>
		<?=$this->templates->output_variables();?>
		<?=$this->templates->output_special_assets();?>
		<th:include></th:include>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				$('.mod_photo a').lightBox();
			});
		</script>
	</head>
	<body <?=$this->templates->body_classes();?>>

		<div id="wrapper">
			<div id="font_panel">
				<th:fontpanel></th:fontpanel>
			</div>
		<!-- header begin -->

			<div id="header">
				<th:versite></th:versite>
				<a href="http://www.facebook.com/astrakhanobs" class="ico ico_1"><img src="/templates/dull-sight/image/facebook.png" /></a>
				<a href="http://twitter.com/bcslep" class="ico ico_2"><img src="/templates/dull-sight/image/twitter.png" /></a>
				<a href="http://vk.com/id176686925" class="ico ico_3"><img src="/templates/dull-sight/image/vk.png" /></a>
				<a href="http://bcslep.livejournal.com/" class="ico ico_4"><img src="/templates/dull-sight/image/lj.png" /></a>
			</div>

		<!-- header end -->

		<!-- toolBar begine -->

			<div id="toolBar">
				<div id="toolBar-left"></div>
				<div id="toolBar-right"></div>
				<th:header></th:header>
			</div>

		<!-- toolBar end -->

		<!-- content begine -->

			<div id="content">
				<div id="left"><th:left></th:left></div>
				<div id="right"><th:content></th:content></div>
			</div>

		<!-- content end -->

		<!-- footer begin -->

			<div id="footer">
				<div id="footer_tip_left">
					<div id="hit_counter">
					<!--LiveInternet counter--><script type="text/javascript"><!--
document.write("<a href='http://www.liveinternet.ru/click' "+
"target=_blank><img src='//counter.yadro.ru/hit?t11.1;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random()+
"' alt='' title='LiveInternet: показано число просмотров за 24"+
" часа, посетителей за 24 часа и за сегодня' "+
"border='0' width='88' height='31'><\/a>")
//--></script><!--/LiveInternet-->
                        <script async src="https://culturaltracking.ru/static/js/spxl.js?pixelId=11430" data-pixel-id="11430"></script>
					</div>
				</div>
				<div id="footer_tip_right"></div>
				<div id="footer_shadow_left"></div>
				<div id="footer_shadow_right"></div>
				<div id="copyright">
					<th:footer></th:footer>
				</div>
				<a href="http://logos.net.ru" id="developer">Разработка сайта<br /> Поддержка и продвижение<br /> Дизайн студия Логос</a>
			</div>

		<!-- footer end -->
			
		</div>

	</body>
</html>
