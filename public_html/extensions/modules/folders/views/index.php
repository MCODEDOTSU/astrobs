<script type="text/javascript">
	var obj = {
		hideDiv: function (id) {
			var sel = '#ho' + id;
			if ($(sel).css ('display') == 'block') {
				$(sel).css ('display', 'none');
			} else {
				$(sel).css ('display', 'block');
			}
			return false;
		}
	}
</script>

<div class="content_desc">
	{breadCrumbs}

    <h1>{title}</h1>
    
    {biletOrd}
    
    <ul id="tree_root">
    {links}
	<li class="tree_root">{link}</li>
    {/links}
    </ul>
</div>

