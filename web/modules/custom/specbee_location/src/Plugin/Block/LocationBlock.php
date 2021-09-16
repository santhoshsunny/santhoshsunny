<?php

namespace Drupal\specbee_location\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\specbee_location\Services\CustomService;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;


/**
 * Provides 'location block'.
 *
 * @Block(
 *   id = "location_block",
 *   admin_label = @Translation("Location Block"),
 *   category = @Translation("Custom Specbee blocks")
 * )
 */
class LocationBlock extends Blockbase implements ContainerFactoryPluginInterface {
  /**
   * Drupal\specbee_location\Services\CustomService definition.
   *
   * @var \Drupal\specbee_location\Services\CustomService
   */
  protected $location;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('specbee_location.custom_services'),
      $container->get('config.factory')
    );
  }

  /**
   * Constructor.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CustomService $location, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->location = $location;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $country = $this->configFactory->get('specbee_location.adminsettings')->get('country');
    $city = $this->configFactory->get('specbee_location.adminsettings')->get('city');
    if (!empty($country) && !empty($city)) {
      $location = [
        'country' => ucwords(strtolower($country)),
        'city' => ucwords(strtolower($city)),
      ];
    }
    $renderable = [
      '#theme' => 'location_block',
      '#location' => $location,
      '#attached' => [
        'library' => [
          'specbee_location/specbee_location_timezone',
        ],
      ],
    ];
    return $renderable;
  }

}
