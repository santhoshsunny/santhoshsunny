<?php

namespace Drupal\specbee_location\Controller;

use Drupal\Core\Controller\ControllerBase;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\specbee_location\Services\CustomService;

/**
 * An example controller.
 */
class TimezoneController extends ControllerBase {

  private $location;

  public static function create(ContainerInterface $container) {

    $location = $container->get('specbee_location.custom_services');
    return new static($location);
  }

  /**
   * Constructor.
   */
  public function __construct(CustomService $location) {
    $this->location = $location;
  }

  /**
   * {@inheritdoc}
   */
  public function get_timezone() {

    $build = array(
      '#type' => 'markup',
      '#markup' => $this->location->get_time(),
    );
    // This is the important part, because will render only the TWIG template.
    return new Response(render($build));
  }
}
