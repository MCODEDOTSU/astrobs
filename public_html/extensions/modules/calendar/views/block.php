<script type="text/javascript">
/*
	var req;
	var data1, data2, send_post;
	 
	function loadXMLDoc(url) {
	data1 =  document.calendar.month.value;
		 data2 = document.calendar.year.value;
		 send_post="month=" + data1 + "&year=" + data2;
		if (window.XMLHttpRequest) {

		
		req = new XMLHttpRequest();
		req.onreadystatechange = processReqChange;
		req.open("POST", url, true);
		req.send(send_post);
		} else if (window.ActiveXObject) {
		req = new ActiveXObject("Microsoft.XMLHTTP");
		if (req) {
		req.onreadystatechange = processReqChange;

		req.open("POST", url, true);
		req.send(send_post);
		}
		}
	}
	function processReqChange() {   
	if (req.readyState == 4) {
	// only if "OK"
	//    if (req.status == 200) {
		    //document.getElementsByName('txt').value=req.status;
		  document.getElementById('kalend');
	//    } else {
		//    alert("Не удалось получить данные:\n" + req.statusText);
	//    }
	}  
	}

		                                                                                                    
	function showUser(str)
	{
	//  data1 =  document.getElementById("month").value; 
		
	loadXMLDoc(window.location.href);
	}
*/
</script>


<div id="calendar" class="block">
	<div class="block_title">Календарь новостей</div>
	<div class="block_content">
		{form_openM}
			{month}
			{year}
			{submit}
		{form_closeM}
		{form_openC}
			{calendar}
			{day}
			{y}{m}    
		{form_closeC}
	</div>
</div>
