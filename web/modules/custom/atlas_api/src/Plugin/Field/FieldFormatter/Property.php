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
 *     "bricks",
 *     "bbox"
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
      else {
        $element[$delta]['#json'] = $item->getValue();

        if (isset($element[$delta]['#json']['target_id'])) {
          $element[$delta]['#json']['target_id'] = (int) $element[$delta]['#json']['target_id'];
        }

        if (isset($element[$delta]['#json']['depth'])) {
          $element[$delta]['#json']['depth'] = (int) $element[$delta]['#json']['depth'];
          $element[$delta]['#json']['type'] = $item->entity->bundle();
        }

        if (isset($element[$delta]['#json']['options']) && $element[$delta]['#json']['options'] == [
            'view_mode' => '',
            'css_class' => ''
          ]
        ) {
          unset($element[$delta]['#json']['options']);
        }

        // Cleaning up entity references.
        if (isset($element[$delta]['#json']['target_id']) && count($element[$delta]['#json']) == 1) {
          $element[$delta]['#json'] = (int) $element[$delta]['#json']['target_id'];
        }
      }
    }

    return $element;
  }

}
