<?php

namespace Drupal\editor_button_convert\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

/**
 * Event subscriber to modify link attributes.
 */
class ButtonLinkAttributeConverter implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events['kernel.response'][] = ['onKernelResponse'];
    return $events;
  }

  /**
   * Converts data attributes to classes.
   *
   * @param \Symfony\Component\HttpKernel\Event\ResponseEvent $event
   *   The event to process.
   */
  public function onKernelResponse(ResponseEvent $event) {
    $response = $event->getResponse();
    $content = $response->getContent();

    // Regular expression to match <a> tags and their attributes.
    $content = preg_replace_callback(
      '/<a([^>]+)>(.*?)<\/a>/i',
      function ($matches) {
        $attributes = $matches[1];
        $link_text = $matches[2];

        // Initialize an array to collect class names.
        $classes = [];
        $data_button_link_exists = false;

        // Check for existing class attribute.
        if (preg_match('/class="([^"]+)"/', $attributes, $class_matches)) {
          $existing_classes = explode(' ', $class_matches[1]);
        } else {
          $existing_classes = [];
        }

        // Check for data-drupal-button-link and add 'button' class if not present.
        if (preg_match('/data-drupal-button-link="button"/', $attributes)) {
          $data_button_link_exists = true;
          if (!in_array('button', $existing_classes)) {
            $classes[] = 'button';
          }
          // Remove the data attribute.
          $attributes = preg_replace('/\s?data-drupal-button-link="button"/', '', $attributes);
        }

        // Only process data-drupal-button-link-style if data-drupal-button-link="button" was found.
        if ($data_button_link_exists) {
          if (preg_match('/data-drupal-button-link-style="([^"]+)"/', $attributes, $style_matches)) {
            $style_class = $style_matches[1];
            if (!in_array($style_class, $existing_classes)) {
              $classes[] = $style_class;
            }
            // Remove the data attribute.
            $attributes = preg_replace('/\s?data-drupal-button-link-style="[^"]+"/', '', $attributes);
          }
        }

        // Combine new classes with existing classes.
        $all_classes = array_merge($existing_classes, $classes);
        $all_classes = array_unique($all_classes);

        // Combine classes into a class attribute.
        if (!empty($all_classes)) {
          $class_attribute = 'class="' . implode(' ', $all_classes) . '"';
          if (preg_match('/class="[^"]+"/', $attributes)) {
            // Replace existing class attribute.
            $attributes = preg_replace('/class="[^"]+"/', $class_attribute, $attributes);
          } else {
            // Add new class attribute.
            $attributes .= ' ' . $class_attribute;
          }
        }

        // Return the modified <a> tag.
        return '<a' . $attributes . '>' . $link_text . '</a>';
      },
      $content
    );

    $response->setContent($content);
  }
}
