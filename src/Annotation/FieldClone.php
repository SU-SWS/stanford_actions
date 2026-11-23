<?php

namespace Drupal\stanford_actions\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Class FieldClone to define the field clone plugins.
 *
 * @package Drupal\stanford_actions\Annoation
 *
 * @Annotation
 */
class FieldClone extends Plugin {

  /**
   * Field types the plugin can alter when cloning.
   *
   * @var string[]
   */
  protected $fieldTypes = [];

}
