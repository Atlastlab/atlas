<?php

namespace Drupal\geojson_mapbox_gl\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Component\Utility\Html;

/**
 * Plugin implementation of the 'geojson_mapbox_gl' formatter.
 *
 * @FieldFormatter(
 *   id = "geojson_mapbox_gl",
 *   label = @Translation("GeoJSON in a Mapbox GL Map"),
 *   field_types = {
 *     "string_long"
 *   }
 * )
 */
class GeoJSONMapboxGL extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [
      '#attached' => [
        'library' => [
          'geojson_mapbox_gl/formatter',
        ],
      ],
    ];

    foreach ($items as $delta => $item) {
      $element[$delta] = [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#attributes' => [
          'id' => Html::getUniqueId('geojson_mapbox_gl'),
          'data-geojson' => base64_encode($item->value),
          'style' => 'height: 400px; width: 700px;',
        ],
      ];
    }

    return $element;
  }

}
