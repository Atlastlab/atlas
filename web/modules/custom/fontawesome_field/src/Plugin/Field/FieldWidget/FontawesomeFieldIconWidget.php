<?php

namespace Drupal\fontawesome_field\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Plugin implementation of the 'fontawesome_field_icon' widget.
 *
 * @FieldWidget(
 *   id = "fontawesome_field_icon",
 *   label = @Translation("Fontawesome Icon"),
 *   field_types = {
 *     "fontawesome_field"
 *   },
 * )
 */
class FontawesomeFieldIconWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    
    $field_id = 'fontawesome-field-' . uniqid();
    $preview = '<div class="container"><div class="fontawesome-icon-preview"></div></div>';

    $element['class'] = array(
      '#prefix' => '<div id="'. $field_id .'" class="font-awesome-field-admin-wrapper">' . $preview,
      '#suffix' => '</div>',

      '#title' => t('Icon'),
      '#default_value' => isset($items[$delta]->class)? $items[$delta]->class : '',
      '#type' => 'select',
      '#options' => fontawesome_field_get_option_list('icons'),

      '#attributes' => array('class' => array('fontawesome-icon','faf-watch')),
    );

    $element['#attached']['library'][] = 'fontawesome_field/fontawesome';
    $element['#attached']['library'][] = 'fontawesome_field/admin';
    $element['#attached']['library'][] = 'fontawesome_field/select2';

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function errorElement(array $element, ConstraintViolationInterface $violation, array $form, FormStateInterface $form_state) {
    
    return $element;
  }

}
