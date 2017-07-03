/**
 * @file
 * Attaches the leaflet draw widget.
 */

(function ($, Drupal, drupalSettings) {

  "use strict";
  var timeout;

  Drupal.behaviors.bboxViewsExposedFilter = {
    attach: function (context, drupalSettings) {

        if (context == document) {
            if (!drupalSettings.bbox.instances) {
                drupalSettings.bbox.instances = {}
            }

            $.each(drupalSettings.bbox.widgets, function (delta, id) {
                if ($('#' + id).length && !$('#' + id).hasClass('leaflet-container')) {
                    $('#' + id).css('height', '200px');
                    $('#' + id).parent().find('.form-type-textfield').hide();

                    var viewsField = $('#' + id).parent().find('.bbox-value');
                    var map = L.map(id, {
                        attributionControl: false
                    }).setView([0, 0], 0);

                    L.tileLayer('http://{s}.tilemill.studiofonkel.nl/style/{z}/{x}/{y}.png?id=tmstyle:///home/administrator/styles/geografie.tm2').addTo(map);

                    var timeout;

                    map.on('drag zoom move', function(e) {
                        clearTimeout(timeout)
                    });

                    map.on('zoomend moveend dragend', function(e) {
                        clearTimeout(timeout);
                        var bounds = map.getBounds();
                        var newVal = bounds.toBBoxString().replace(/,/g, '|');

                        timeout = setTimeout(function () {
                            viewsField.val(newVal);
                            viewsField.change()
                        }, 400);
                    });

                    if (viewsField.val()) {
                        var values = viewsField.val().split('|');

                        if (values) {
                            var bounds = [[values[1], values[0]], [values[3], values[2]]];
                            map.fitBounds(bounds)
                        }
                    }

                    drupalSettings.bbox.instances[id] = map
                }
            })
        }

    }
  };

})(jQuery, Drupal, drupalSettings);
