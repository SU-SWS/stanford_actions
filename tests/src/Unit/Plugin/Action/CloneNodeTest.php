<?php

namespace Drupal\Tests\stanford_actions\Unit\Plugin\Action;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\field\FieldConfigInterface;
use Drupal\stanford_actions\Plugin\Action\CloneNode;
use Drupal\stanford_actions\Plugin\FieldCloneManagerInterface;
use Drupal\Tests\UnitTestCase;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Test which reference fields the clone action duplicates.
 */
#[Group('stanford_actions')]
class CloneNodeTest extends UnitTestCase {

  /**
   * Field definitions returned for the bundle being cloned.
   *
   * @var \Drupal\Core\Field\FieldDefinitionInterface[]
   */
  protected array $fields = [];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->fields = [
      'field_eck' => $this->getField(FieldConfigInterface::class, 'entity_reference', 'eck_thing'),
      'field_paragraphs' => $this->getField(FieldConfigInterface::class, 'entity_reference_revisions', 'paragraph'),
      'field_node' => $this->getField(FieldConfigInterface::class, 'entity_reference', 'node'),
      'field_eck_string' => $this->getField(FieldConfigInterface::class, 'string', 'eck_thing'),
      'base_eck' => $this->getField(FieldDefinitionInterface::class, 'entity_reference', 'eck_thing'),
    ];
  }

  /**
   * ECK entity types are cloned when the ECK module is available.
   */
  public function testReferenceFieldsWithEck(): void {
    $eck_storage = $this->createMock(EntityStorageInterface::class);
    $eck_storage->method('loadMultiple')->willReturn(['eck_thing' => NULL]);

    $entity_type_manager = $this->createMock(EntityTypeManagerInterface::class);
    $entity_type_manager->method('hasDefinition')
      ->with('eck_entity_type')
      ->willReturn(TRUE);
    $entity_type_manager->expects($this->once())
      ->method('getStorage')
      ->with('eck_entity_type')
      ->willReturn($eck_storage);

    $fields = $this->getReferenceFields($entity_type_manager);
    $this->assertEquals(['field_eck', 'field_paragraphs'], array_keys($fields));
  }

  /**
   * Only the configured entity types are cloned without the ECK module.
   */
  public function testReferenceFieldsWithoutEck(): void {
    $entity_type_manager = $this->createMock(EntityTypeManagerInterface::class);
    $entity_type_manager->method('hasDefinition')->willReturn(FALSE);
    $entity_type_manager->expects($this->never())->method('getStorage');

    $fields = $this->getReferenceFields($entity_type_manager);
    $this->assertEquals(['field_paragraphs'], array_keys($fields));
  }

  /**
   * Build the action and get the fields it would clone references for.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Mocked entity type manager.
   *
   * @return \Drupal\Core\Field\FieldDefinitionInterface[]
   *   Fields whose referenced entities would be cloned.
   */
  protected function getReferenceFields(EntityTypeManagerInterface $entity_type_manager): array {
    $field_manager = $this->createMock(EntityFieldManagerInterface::class);
    $field_manager->method('getFieldDefinitions')
      ->with('node', 'page')
      ->willReturn($this->fields);

    $settings = $this->createMock(ImmutableConfig::class);
    $settings->method('get')
      ->with('actions.node_clone_action.clone_entities')
      ->willReturn(['paragraph']);
    $config_factory = $this->createMock(ConfigFactoryInterface::class);
    $config_factory->method('get')
      ->with('stanford_actions.settings')
      ->willReturn($settings);

    $action = new CloneNode(
      [],
      'node_clone_action',
      [],
      $field_manager,
      $entity_type_manager,
      $this->createMock(FieldCloneManagerInterface::class),
      $config_factory,
      $this->createMock(AccountProxyInterface::class),
      $this->createMock(EventDispatcherInterface::class)
    );

    $method = new \ReflectionMethod($action, 'getReferenceFields');
    return $method->invoke($action, 'node', 'page');
  }

  /**
   * Create a mocked field definition.
   *
   * @param string $class
   *   Field definition interface to mock.
   * @param string $type
   *   Field type.
   * @param string $target_type
   *   Target entity type setting on the field storage.
   *
   * @return \Drupal\Core\Field\FieldDefinitionInterface
   *   Mocked field.
   */
  protected function getField(string $class, string $type, string $target_type): FieldDefinitionInterface {
    $storage = $this->createMock(FieldStorageDefinitionInterface::class);
    $storage->method('getSetting')
      ->with('target_type')
      ->willReturn($target_type);

    $field = $this->createMock($class);
    $field->method('getType')->willReturn($type);
    $field->method('getFieldStorageDefinition')->willReturn($storage);
    return $field;
  }

}
