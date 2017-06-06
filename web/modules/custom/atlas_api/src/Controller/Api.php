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

    $response = new JsonResponse($render);
    $response->headers->set('Access-Control-Allow-Origin', '*');
    return $response;
  }

  public function getEntityIdsKeyedByType($timestamp) {
    $query = \Drupal::entityQuery('node')
      ->condition('status', 1)
    ->condition('changed', $timestamp, '>');
    $node_ids = $query->execute();

    $query = \Drupal::entityQuery('media')
      ->condition('status', 1)
      ->condition('changed', $timestamp, '>');
    $media_ids = $query->execute();

    return [
      'node' => $node_ids,
      'media' => $media_ids
    ];
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

        if (isset($configuration['regions'])) {
          foreach ($configuration['regions']['ds_content'] as $field_name) {
            if (isset($entity->{$field_name})) {
              $field_render = $entity->{$field_name}->view('json');
              $field_render['#label_display'] = 'hidden';
              $field_title = is_string($field_render['#title']) ? $field_render['#title'] : $field_render['#title']->render();

              $entity_display_content = $entity_display->get('content');

              if (isset($entity_display_content[$field_name]['third_party_settings']['ds']['ft']['settings']['lb']) && $entity_display_content[$field_name]['third_party_settings']['ds']['ft']['settings']['lb']) {
                $field_title = $entity_display_content[$field_name]['third_party_settings']['ds']['ft']['settings']['lb'];
              }

              if (isset($field_render[0]['#json'])) {
                foreach (Element::children($field_render) as $field_render_delta) {
                  $field_render_item = $field_render[$field_render_delta];
                  $render[$entity->bundle()][(int) $entity->id()][Html::getClass($field_title)][$field_render_delta] = $field_render_item['#json'];
                }
              }
              else {
                $render[$entity->bundle()][(int) $entity->id()][Html::getClass($field_title)] = trim(render($field_render));
              }
            }
            else {
              $field = $ds_fields[$field_name];
              $field_instance = Ds::getFieldInstance($field_name, $field, $entity, 'json', $entity_display);
              $field_value = $field_instance->build();
              $field_title = is_string($field_instance->getTitle()) ? $field_instance->getTitle() : $field_instance->getTitle()
                ->render();

              if (isset($configuration['fields'][$field_name]['ft']['settings']['lb']) && $configuration['fields'][$field_name]['ft']['settings']['lb']) {
                $field_title = $configuration['fields'][$field_name]['ft']['settings']['lb'];
              }

              $render[$entity->bundle()][(int) $entity->id()][Html::getClass($field_title)] = trim(render($field_value));
            }
          }
        }
      }
    }

    return $render;
  }
}