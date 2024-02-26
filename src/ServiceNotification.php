<?php

namespace Drupal\notification_update_nodes;

use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Component\Utility\Html;
use Drupal\Core\Config\ConfigFactoryInterface;

class ServiceNotification {
    protected $mailManager;
    protected $renderer;
    protected $configFactory;
  
    public function __construct(MailManagerInterface $mail_manager, RendererInterface $renderer, ConfigFactoryInterface $configFactory) {
      $this->mailManager = $mail_manager;
      $this->renderer = $renderer;
      $this->configFactory = $configFactory;
    }
  
    public static function create(ContainerInterface $container) {
      return new static(
        $container->get('plugin.manager.mail'),
        $container->get('renderer'),
      );
    }
  
    public function envoyerEmail($ContentType,$nodeid,$title) {
      $module = 'notification_update_nodes';
      $key = 'content_update_notification';
      $langcode = \Drupal::currentUser()->getPreferredLangcode();
      $send = true;
      $config = $this->configFactory->get('form_settings.settings');
      $email = $config->get('notifications_email');
      $params['subject'] = "Update Nodes";
      $bundle = $ContentType;
      $build = [
        '#theme' => 'email_update',
        '#bundle' => $bundle,
        '#id' => $nodeid,
        '#title' => $title
      ];  
      $params['body'] = Html::escape($this->renderer->renderRoot($build));
      $params['headers'] = ['Content-Type' => 'text/html; charset=UTF-8'];
      $this->mailManager->mail($module, $key, $email, $langcode, $params, null, $send);
    }        
}