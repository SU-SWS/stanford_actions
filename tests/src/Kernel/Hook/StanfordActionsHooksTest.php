<?php

namespace Drupal\Tests\stanford_actions\Kernel\Hook;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\KernelTests\KernelTestBase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

/**
 * Test the module hook implementations.
 */
#[Group('stanford_actions')]
#[RunTestsInSeparateProcesses]
class StanfordActionsHooksTest extends KernelTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = [
    'system',
    'user',
    'node',
    'views',
    'views_bulk_operations',
    'stanford_actions',
  ];

  /**
   * Test hook_help().
   */
  public function testHelp() {
    $module_handler = $this->container->get('module_handler');
    $this->assertTrue($module_handler->hasImplementations('help', 'stanford_actions'));

    $route_match = $this->createMock(RouteMatchInterface::class);
    $help = (string) $module_handler->invoke('stanford_actions', 'help', [
      'help.page.stanford_actions',
      $route_match,
    ]);
    $this->assertStringContainsString('<h3>About</h3>', $help);
    $this->assertStringContainsString('Provides action plugins to work with VBO module.', $help);

    $this->assertNull($module_handler->invoke('stanford_actions', 'help', [
      'system.admin',
      $route_match,
    ]));
  }

}
