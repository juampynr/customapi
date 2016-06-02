<?php

namespace Drupal\customapi\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CustomApiController.
 *
 * @package Drupal\customapi\Controller
 */
class CustomApiController extends ControllerBase {

  /**
   * The serialization service.
   *
   * @var Symfony\Component\Serializer\Serializer
   */
  protected $serializer;

  /**
   * {@inheritdoc}
   */
  public function __construct(Serializer $serializer) {
    $this->serializer = $serializer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('serializer')
    );
  }

  /**
   * Get a node using the default serializer.
   *
   * @param Drupal\node\NodeInterface $node
   *   A node object.
   *
   * @return string
   *   An XML response.
   */
  public function getNodeWithDefaultSerializer(NodeInterface $node) {
    $response_data = $this->serializer->serialize($node, 'xml');

    return $this->buildResponse($response_data);
  }

  /**
   * Returns a node with its author.
   *
   * @param Drupal\node\NodeInterface $node
   *   A node object.
   *
   * @return string
   *   An XML response.
   */
  public function getNodeWithNestedData(NodeInterface $node) {
    $normalized_node = $this->serializer->normalize($node);

    // At this point, we have the normalized node in an array structure.
    // We can now modify this structure as we need. For example:
    // 1. We can remove an item:
    unset($normalized_node['vid']);

    // 2. We can add an extra scalar item:
    $normalized_node['custom_data'] = 'Foo';

    // 3. We can add a normalized object:
    $normalized_node['author'] = $this->serializer->normalize($node->getOwner());

    // 4. We can add a nested list of objects too:
    unset($normalized_node['field_tags']);
    $normalized_node['tags'] = [];
    foreach ($node->field_tags as $term) {
      $normalized_node['tags'][] = $this->serializer->normalize($term);
    }

    // 5. If needed, we can use our own normalizer to build a custom structure.
    $normalized_node['author_custom'] = $this->serializer->normalize($node->getOwner(), 'customapi_user');

    $response_data = $this->serializer->encode($normalized_node, 'xml');

    return $this->buildResponse($response_data);
  }

  /**
   * Builds the XML response.
   *
   * Return an XML response based on how XML Sitemap module does it.
   * For caching options, see
   * https://www.drupal.org/developing/api/8/response
   */
  protected function buildResponse($response_data) {
    $headers = [
      'Content-type' => 'text/xml; charset=utf-8',
    ];

    if (ob_get_level()) {
      ob_end_clean();
    }

    $response = new Response();
    foreach ($headers as $name => $value) {
      $response->headers->set($name, $value);
    }

    $response->setContent($response_data);
    return $response;
  }

}
