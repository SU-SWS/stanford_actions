<?php

namespace Drupal\stanford_actions\Attribute;

use Drupal\Component\Plugin\Attribute\AttributeBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Defines a field clone plugin attribute.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class FieldClone extends AttributeBase {

  /**
   * Constructs a new FieldClone instance.
   *
   * @param string $id
   *   The plugin ID.
   * @param \Drupal\Core\StringTranslation\TranslatableMarkup|null $label
   *   (optional) The human-readable name of the plugin.
   * @param \Drupal\Core\StringTranslation\TranslatableMarkup|null $description
   *   (optional) A brief description of the plugin.
   * @param string[] $fieldTypes
   *   (optional) Field types the plugin applies to.
   */
  public function __construct(
    public readonly string $id,
    public readonly ?TranslatableMarkup $label = NULL,
    public readonly ?TranslatableMarkup $description = NULL,
    public readonly array $fieldTypes = [],
  ) {}

}
