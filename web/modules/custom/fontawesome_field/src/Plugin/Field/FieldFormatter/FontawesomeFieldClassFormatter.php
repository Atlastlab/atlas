<?php

namespace Drupal\fontawesome_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'fontawesome_field_class' formatter.
 *
 * @FieldFormatter(
 *   id = "fontawesome_field_class",
 *   label = @Translation("CSS class"),
 *   field_types = {
 *     "fontawesome_field",
 *   },
 * )
 */
class FontawesomeFieldClassFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = array(
      t('Displays the icon class.'),
    );

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = array();

    foreach ($items as $delta => $item) {
      $elements[$delta] = array(
        '#type' => 'markup',
        '#markup' => $item->class,
      );
    }

    return $elements;
  }

}
