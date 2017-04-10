<?php

namespace Drupal\geojson_mapbox_gl\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'geojson_mapbox_gl' formatter.
 *
 * @FieldFormatter(
 *   id = "geojson_mapbox_gl",
 *   label = @Translation("GeoJSON in a Mapbox GL Map"),
 *   field_types = {
 *     "text_long"
 *   }
 * )
 */
class GeoJSONMapboxGL  extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
    $entity = $items->getEntity();

    foreach ($items as $delta => $item) {
    }

    return $element;
  }

}
