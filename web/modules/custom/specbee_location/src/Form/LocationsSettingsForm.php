<?php

namespace Drupal\specbee_location\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Cache\Cache;

/**
 * List tests arranged in groups that can be selected and run.
 *
 * @internal
 */
class LocationsSettingsForm extends ConfigFormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * The returned ID should be a unique string that can be a valid PHP function
   * name, since it's used in hook implementation names such as
   * hook_form_FORM_ID_alter().
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'locations_form';
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('specbee_location.adminsettings');
    $timezone_options = static::getDropdownOptions();


    $form['country'] = [
      '#type' => 'textfield',
      '#name' => 'country',
      '#title' => $this->t('Country'),
      '#description' => $this->t('Please type valid Country Name.'),
      '#default_value' => $config->get('country'),
    ];
    $form['city'] = [
      '#type' => 'textfield',
      '#name' => 'city',
      '#title' => $this->t('City'),
      '#description' => $this->t('Please type valid City Name.'),
      '#default_value' => $config->get('city'),
    ];

    $form['timezone_dropdown'] = [
      '#type' => 'select',
      '#title' => $this->t('Timezone'),
      '#options' => $timezone_options,
      '#required' => TRUE,
      '#default_value' => $config->get('timezone_dropdown'),
    ];

    // Group submit handlers in an actions element with a key of "actions" so
    // that it gets styled correctly, and so that other modules may add actions
    // to the form. This is not required, but is convention.
    $form['actions'] = [
      '#type' => 'actions',
    ];

    // Add a submit button that handles the submission of the form.
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Validate the title and the checkbox of the form
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    parent::validateForm($form, $form_state);

    $country = $form_state->getValue('country');
    $city = $form_state->getValue('city');
    $timezone = $form_state->getValue('timezone_dropdown');

    if (empty($country)) {
      // Set an error for the form element with a key of "country".
      $form_state->setErrorByName('country', $this->t('Country should be required.'));
    }

    if (empty($city)) {
      // Set an error for the form element with a key of "city".
      $form_state->setErrorByName('city', $this->t('City should be required.'));
    }
    if ($timezone == "select") {
      // Set an error for the form element with a key of "Timezone".
      $form_state->setErrorByName('timezone', $this->t('Please select a timezone.'));
    }
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    //$tags=[];
    parent::submitForm($form, $form_state);
    $this->config('specbee_location.adminsettings')
      ->set('country', $form_state->getValue('country'))
      ->set('city', $form_state->getValue('city'))
      ->set('timezone_dropdown', $form_state->getValue('timezone_dropdown'))
      ->save(); //saving the configuration value in database
      // $tags[] = 'my_timezone';
      // if (!empty($tags)) {
      //   Cache::invalidateTags($tags);
      // }
    // Call the Static Service Container wrapper
    // We should inject the messenger service, but its beyond the scope of this example.
    $messenger = \Drupal::messenger();
    $messenger->addMessage("Form Submitted Succesfully");
  }


  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'specbee_location.adminsettings',
    ];
  }


  public static function getDropdownOptions() {
    return [
      'select' => 'Select',
      'America/Chicago' => 'America/Chicago',
      'America/New_York' => 'America/New_York',
      'Asia/Tokyo' => 'Asia/Tokyo',
      'Asia/Dubai' => 'Asia/Dubai',
      'Asia/Kolkata' => 'Asia/Kolkata',
      'Europe/Amsterdam' => 'Europe/Amsterdam',
      'Europe/Oslo' => 'Europe/Oslo',
      'Europe/London' => 'Europe/London',
    ];
  }
}
