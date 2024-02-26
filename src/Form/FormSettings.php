<?php

namespace Drupal\notification_update_nodes\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configuration form for custom notifications module.
 */
class FormSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'form_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['form_settings.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('form_settings.settings');

    $form['notifications_email'] = [
      '#type' => 'email',
      '#title' => $this->t('E-mail de notification'),
      '#description' => $this->t('Saisissez l\'adresse e-mail à laquelle les notifications de mise à jour de contenu doivent être envoyées.'),
      '#default_value' => $config->get('notifications_email'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('form_settings.settings')
      ->set('notifications_email', $form_state->getValue('notifications_email'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
