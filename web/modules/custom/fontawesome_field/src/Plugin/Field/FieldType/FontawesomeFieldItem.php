<?php

namespace Drupal\fontawesome_field\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'fontawesome_field' field type.
 *
 * @FieldType(
 *   id = "fontawesome_field",
 *   label = @Translation("Fontawesome Icon"),
 *   description = @Translation("This field represents a Fontawesome icon."),
 *   category = @Translation("General"),
 *   default_widget = "fontawesome_field_icon",
 *   default_formatter = "fontawesome_field_icon"
 * )
 */
class FontAwesomeFieldItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['class'] = DataDefinition::create('string')
      ->setLabel(t('Class'))
      ->setRequired(FALSE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return array(
      'columns' => array(
        'class' => array(
          'type' => 'varchar',
          'length' => 30,
          'not null' => FALSE,
        ),
      ),
      'indexes' => array(
        'class' => array('class'),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function applyDefaultValue($notify = TRUE) {
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    $value = $this->get('class')->getValue();
    return $value === NULL || $value === '';
  }

}
