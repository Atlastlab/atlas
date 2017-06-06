<?php

namespace Drupal\atlas_api\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'Property' formatter.
 *
 * @FieldFormatter(
 *   id = "property",
 *   label = @Translation("Property"),
 *   field_types = {
 *     "link",
 *     "string_long",
 *     "entity_reference",
 *     "bricks"
 *   }
 * )
 */
class Property extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      if ($json = json_decode($item->value, TRUE)) {
        $element[$delta]['#json'] = $json;
      }
      elseif (isset($item->entity)) {
        $element[$delta]['#json'] = (int) $item->entity->id();
      }
      else {
        $element[$delta]['#markup'] = $item->value;
      }
    }

    return $element;
  }

}
