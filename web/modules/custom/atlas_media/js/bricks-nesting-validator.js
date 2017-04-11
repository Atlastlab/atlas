(function ($, Drupal) {
    Drupal.behaviors.bricksNestingValidator = {
        attach: function (context, settings) {

            if (Drupal.tableDrag) {
                $('.tabledrag-toggle-weight-wrapper').hide();

                var _isValidSwap = Drupal.tableDrag.prototype.row.prototype.isValidSwap;

                Drupal.tableDrag.prototype.row.prototype.isValidSwap = function(tr) {
                    if ($(tr).index() == 0) {
                        this.interval.max = 0;
                        return true;
                    }
                    return  _isValidSwap.apply(this, arguments);
                };

                var _validIndentInterval = Drupal.tableDrag.prototype.row.prototype.validIndentInterval;

                var hierachy = {
                  'Text': ['Swiper'],
                  'Image': ['Swiper']
                };

                Drupal.tableDrag.prototype.row.prototype.validIndentInterval = function (prevRow, nextRow) {
                    var values = _validIndentInterval.apply(this, arguments);
                    var currentBundle = $(this.element).find('.inline-entity-form-media-bundle').text().trim();
                    var prevBundle = $(prevRow).find('.inline-entity-form-media-bundle').text().trim();
                    var parentBundle = $(this.group[0]).prev('tr').find('.inline-entity-form-media-bundle').text().trim();

                    if ($.inArray(prevBundle, hierachy[currentBundle]) !== -1 || $.inArray(parentBundle, hierachy[currentBundle]) !== -1) {
                        values.max = 1;
                    }
                    else {
                        values.max = 0;
                    }

                    return values;
                };


            }

        }
    };
})(jQuery, Drupal);
