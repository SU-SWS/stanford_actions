<?php

namespace Drupal\stanford_actions\Plugin\Action\FieldClone;

/**
 * Class Date to increment date fields.
 *
 * @FieldClone(
 *   id = "smart_date",
 *   label = @Translation("Smart Date"),
 *   description = @Translation("Incrementally increase the Smart date on the field for every cloned item."),
 *   fieldTypes = {
 *     "smartdate"
 *   }
 * )
 */
class SmartDate extends Date {

  /**
   * Increase the given date value by the configured amount.
   *
   * @param string $value
   *   Original date value.
   *
   * @return string
   *   The new increased value.
   *
   * @throws \Exception
   */
  protected function incrementDateValue($value) {

    $increment = $this->configuration['multiple'] * $this->configuration['increment'];

    $new_value = \DateTime::createFromFormat('U', $value);
    $timezone = new \DateTimeZone('America/Los_Angeles');
    $new_value->setTimezone($timezone);
    $daylight_savings = date('I', $new_value->getTimestamp());

    // Add the interval that is in the form of "2 days" or "6 hours".
    $interval = \DateInterval::createFromDateString($increment . ' ' . $this->configuration['unit']);
    $new_value->add($interval);

//    // Adjust the time of the string if the new value skips over the daylight
//    // savings time.
//    if (date('I', $new_value->getTimestamp()) != $daylight_savings) {
//      // Accommodate both going into and out of daylight savings time.
//      $interval = $daylight_savings ? '1 hour' : '-1 hour';
//      $interval = \DateInterval::createFromDateString($interval);
//      $new_value->add($interval);
//    }

    return $new_value->format('U');
  }

}
