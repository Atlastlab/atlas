<?php

namespace Drupal\fontawesome_field\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'fontawesome_field_icon' formatter.
 *
 * @FieldFormatter(
 *   id = "fontawesome_field_icon",
 *   label = @Translation("Icon"),
 *   field_types = {
 *     "fontawesome_field",
 *   },
 * )
 */
class FontawesomeFieldIconFormatter extends FormatterBase {

    /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = array(
      t('Displays an icon.'),
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
        '#markup' => static::render_icon($item),
      );
    }

    return $elements;
  }

  protected static function render_icon($item) {
    $classes = array('fa');
    $classes[] = $item->class;

    return '<i class="' . implode(" ", $classes) . '"></i>';
  }

}
