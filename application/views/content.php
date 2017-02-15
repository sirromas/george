<?php
//require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/config.php';
echo "<div id='page' class='container-fluid'>";
echo "<div id='page-content' class='row-fluid'>";
echo $page;

/* * *************************************************************************
 * Both DIVs id=page and id='page-content' are closed in the footer
 * so please be aware and do not clode them in context section
 * ************************************************************************* */

if (strpos($_SERVER['REQUEST_URI'], 'contact') !== FALSE) {
    ?>

    <script type="text/javascript">

        $(document).ready(function () {

            var url = "http://<?php echo $_SERVER['SERVER_NAME']; ?>/index.php/index/get_campus_data";
            $.post(url, {id: 1}).done(function (data) {
                var $obj_data = jQuery.parseJSON(data);
                var map = new google.maps.Map(document.getElementById('map'), {
                    scrollwheel: false,
                    zoom: 8
                }); // end var map            
                var latLngs = [];
                var bounds = new google.maps.LatLngBounds();
                var infowindow = new google.maps.InfoWindow();
                var myLatLng = new google.maps.LatLng($obj_data.lat, $obj_data.lon);
                latLngs[1] = myLatLng;
                var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,
                    label: $obj_data.name,
                    title: $obj_data.name,
                    zIndex: 1
                }); // end marker                
                bounds.extend(marker.position);
                google.maps.event.addListener(marker, 'click', (function (marker) {
                    return function () {
                        infowindow.setContent($obj_data.desc);
                        infowindow.open(map, marker);
                    }
                })(marker));
                map.fitBounds(bounds);
                infowindow.setContent($obj_data.desc);
                infowindow.open(map, marker);

            }); // end of post
        }); // end of document ready

    </script>    

<?php } ?>
