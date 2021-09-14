<?php

namespace Drupal\specbee_location\Services;

use Drupal\Core\Config\ConfigFactory;

/**
 * Class CustomService.
 */
class CustomService {

  /**
   * Configuration Factory.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * Constructor.
   */
  public function __construct(ConfigFactory $configFactory) {
    $this->configFactory = $configFactory;
  }


  /**
   * Gets my setting.
   */
  public function get_time() {
    $config = $this->configFactory->get('specbee_location.adminsettings');
    $date = new \DateTime("now", new \DateTimeZone($config->get('timezone_dropdown')));
    return  $date->format("j M Y- g:i A");
  }
}
