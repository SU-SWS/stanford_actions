<?php

namespace Drupal\stanford_actions\Events;

use Drupal\node\NodeInterface;
use Drupal\Component\EventDispatcher\Event;

class NodeCloneEvent extends Event {

  public function __construct(protected NodeInterface $node, protected NodeInterface $originalNode) {}

  public function getNode(): NodeInterface {
    return $this->node;
  }

  public function getOriginalNode(): NodeInterface {
    return $this->originalNode;
  }

}
