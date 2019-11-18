<div class="content_desc">
<h1>{title}</h1>

<p>{desc}</p>

<div id="mod_map">
    {map}
</div>

<script>
    $(document).ready(function(){
        if(document.getElementById("YMapsID")){
            var map = new YMaps.Map(document.getElementById("YMapsID"));
            
            var q = new String('{point}');
            var xyArray = q.split(',');
            var x = xyArray[0];
            var y = xyArray[1];

            map.setCenter(new YMaps.GeoPoint(x,y), 15);
            map.addControl(new YMaps.Zoom());
            map.addOverlay(new YMaps.Placemark(new YMaps.GeoPoint(x,y), {hasBalloon: false}));
        }
    });
</script>
</div>
