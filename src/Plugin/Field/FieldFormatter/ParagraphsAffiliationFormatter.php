<?php

namespace Drupal\paragraphs_affiliation_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'ParagraphsAffiliationFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "paragraphs_affiliation_formatter",
 *   label = @Translation("Paragraphs Affiliation Formatter"),
 *   field_types = {
 *     "entity_reference_revisions"
 *   }
 * )
 */
 
class ParagraphsAffiliationFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Formats the affiliation field of Drupal.');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      // Render each element as markup.
      $element[$delta] = [
		'#markup' => $item->value
	  ];
    }

    return $element;
  }

}