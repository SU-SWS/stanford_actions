<?php

namespace Drupal\stanford_actions\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Hook implementations for stanford_actions.
 */
class StanfordActionsHooks {

  use StringTranslationTrait;

  /**
   * Implements hook_help().
   */
  #[Hook('help')]
  public function help(string $route_name, RouteMatchInterface $route_match): ?string {
    if ($route_name == 'help.page.stanford_actions') {
      $output = '<h3>' . $this->t('About') . '</h3>';
      $output .= '<p>' . $this->t('Provides action plugins to work with VBO module.') . '</p>';
      return $output;
    }
    return NULL;
  }

}
