<title><?=_module_icon('map')?>Создание / Редактирование карт</title>


<div rel="content">
{form_open}
{file}
{point}
<ul>
    <li>
        <label for="title">Наименование</label>
        {title}
    </li>

    <li>
        <label for="desc">Описание</label>
        {desc}
    </li>

    <li>
        {mapsYandex}
    </li>

    <li class="cms_dialog_btn">
        {submit}
    </li>
</ul>
{form_close}

<script type="text/javascript">
    var placemark;
    
    YMaps.jQuery(function () {
        var map = new YMaps.Map(document.getElementById("YMapsID"));

        if($('#map_point').val() != "0,0")
        {
            var p = new String($('#map_point').val());

            var p = p.split(',');
            map.setCenter(new YMaps.GeoPoint(p[0],p[1]), 15);
            
            placemark = setMark($('#map_point').val(), map, placemark);
        }
        else
        {
            map.setCenter(new YMaps.GeoPoint(48.03034,46.349636), 15);
        }
        
        map.addControl(new YMaps.Zoom());
        
        YMaps.Events.observe(map, map.Events.Click, function (map, mEvent) {
          placemark = setMark(mEvent.getGeoPoint(), map, placemark);
        });
    })

    

    function setMark(xyString, map, placemark)
    {
        $('#map_point').val(xyString);

        map.removeOverlay(placemark);
        if(xyString == '') return false;

        var q = new String(xyString);

        var xyArray = q.split(',');

        var x = xyArray[0];
        var y = xyArray[1];

        var point = new YMaps.GeoPoint(x,y);
        placemark = new YMaps.Placemark(point, {hasBalloon: false});

        map.addOverlay(placemark);

        return placemark;
    }
</script>
</div>