<?php
/**
 * @file
 * Contains \Drupal\atlas_api\Controller\Api.
 */
namespace Drupal\atlas_api\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\ds\Ds;
use Drupal\Component\Utility\Html;
Use Drupal\Core\Render\Element;

class Api extends ControllerBase {

  public function response($timestamp) {
    $entity_ids_keyed_by_type = $this->getEntityIdsKeyedByType($timestamp);
    $render = $this->buildEntityFieldRenders($entity_ids_keyed_by_type);
    return new JsonResponse($render);
  }

  public function getEntityIdsKeyedByType($timestamp) {
    $query = \Drupal::entityQuery('node')
      ->condition('status', 1)
    ->condition('changed', $timestamp, '>');

    $node_ids = $query->execute();

    return ['node' => $node_ids];
  }

  public function buildEntityFieldRenders($entity_ids_keyed_by_type) {
    $render = [];
    foreach ($entity_ids_keyed_by_type as $entity_type => $entity_ids) {
      $entity_storage = \Drupal::entityManager()->getStorage($entity_type);
      $entities = $entity_storage->loadMultiple($entity_ids);
      $ds_fields = Ds::getFields($entity_type);

      foreach ($entities as $entity) {
        $entity_display = entity_get_display($entity_type, $entity->bundle(), 'json');
        $configuration = $entity_display->getThirdPartySettings('ds');

        foreach ($configuration['regions']['ds_content'] as $field_name) {
          if (isset($entity->{$field_name})) {
            $field_render = $entity->{$field_name}->view('json');
            $field_render['#label_display'] = 'hidden';
            if (isset($field_render[0]['#json'])) {

              foreach (Element::children($field_render) as $field_render_delta) {
                $field_render_item = $field_render[$field_render_delta];
                $render[$entity_type][(int) $entity->id()][Html::getClass($field_render['#title'])][$field_render_delta] = $field_render_item['#json'];
              }
            }
            else {
              $render[$entity_type][(int) $entity->id()][Html::getClass($field_render['#title'])] = trim(render($field_render));
            }
          }
          else {
            $field = $ds_fields[$field_name];
            $field_instance = Ds::getFieldInstance($field_name, $field, $entity, 'json', $entity_display, []);
            $field_value = $field_instance->build();
            $field_title = $field_instance->getTitle();
            $render[$entity_type][(int) $entity->id()][Html::getClass($field_title->render())] = trim(render($field_value));
          }
        }
      }

    }

    return $render;
  }
}