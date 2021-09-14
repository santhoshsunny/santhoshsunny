<?php

namespace Drupal\specbee_location\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
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
    $timezone = $this->configFactory->get('specbee_location.adminsettings')->get('timezone_dropdown');
    if (!empty($country) && !empty($city) && !empty($timezone)) {
      $location = [
        'country' => ucwords(strtolower($country)),
        'city' => ucwords(strtolower($city)),
        'timezone' => $this->location->get_time(),
      ];
    }

    $renderable = [
      '#theme' => 'location_block',
      '#location' => $location,
      '#cache' => [
        'max-age' => 0,
      ],
    ];
    return $renderable;
  }

  /**
   * {@inheritdoc}
   */
  public function blockValidate($form, FormStateInterface $form_state) {}

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
