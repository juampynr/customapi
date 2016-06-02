<?php

namespace Drupal\customapi\Normalizer;

use Drupal\serialization\Normalizer\ContentEntityNormalizer;

/**
 * Normalizes/denormalizes user entities with a custom structure.
 */
class UserCustomNormalizer extends ContentEntityNormalizer {

  /**
   * The formats that the Normalizer can handle.
   *
   * @var array
   */
  protected $formats = array('customapi_user');

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = ['Drupal\Core\Entity\ContentEntityInterface'];

  /**
   * {@inheritdoc}
   */
  public function supportsNormalization($data, $format = NULL) {
    return in_array($format, $this->formats) && parent::supportsNormalization($data, $format);
  }

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = array()) {
    $context += array(
      'account' => NULL,
    );

    $attributes = [];
    $attributes['name'] = $object->getDisplayName();

    return $attributes;
  }

}
