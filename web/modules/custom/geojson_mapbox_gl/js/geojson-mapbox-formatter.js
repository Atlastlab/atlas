(function ($, Drupal) {
    Drupal.behaviors.geojsonMapboxFormatter = {
        attach: function (context, settings) {
            $(context).find('[data-geojson]').once('geojsonMapboxFormatter').each(function () {
                var that = this;
                var geoJson = JSON.parse(atob($(that).attr('data-geojson')));

                var map = new mapboxgl.Map({
                    container: $(that).attr('id'),
                    style: {
                        "version": 8,
                        "sources": {},
                        "layers": []
                    }
                });

                map.on('load', function () {
                    map.addLayer({
                        "id": $(that).attr('id'),
                        "type": "line",
                        "source": {
                            "type": "geojson",
                            "data": geoJson
                        },
                        "paint": {
                            "line-color": "#888",
                            "line-width": 1
                        }
                    });

                    var bboxArray = bbox(geoJson);
                    var bounds = new mapboxgl.LngLatBounds([bboxArray[0], bboxArray[1]], [bboxArray[2], bboxArray[3]]);

                    map.fitBounds(bounds, {
                        animate: false,
                        padding: 20
                    });
                });

            });
        }
    };
})(jQuery, Drupal);
