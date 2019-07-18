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
 *   },
 *   quickedit = {
 *     "editor" = "disabled"
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
	
	// Render each element as markup in case of multi-values.

	foreach ($items as $delta => $item) {
		  
	  // Desired fields: field_network_name, field_network_specific_site_code, field_network_verified;
	  $network_name_tid = render($item->entity->field_network_name->target_id);
	  $full_taxonomy_term_object = taxonomy_term_load($network_name_tid);
	  
	  if ($full_taxonomy_term_object) {
		$network_label = $full_taxonomy_term_object->getName();	  
	  }
	  if ($item->entity->field_network_specific_site_code->value) {
		$network_site_code = " (" . render($item->entity->field_network_specific_site_code->value) . ")";
	  }
	  else {
		$network_site_code = "";
	  }

	  $network_site_verified = render($item->entity->field_network_verified->value);
	  if ($network_site_verified) {
		  $network_site_verified = '<sup>✓</sup>';
	  }
		  
	  $element[$delta] = [
		'#markup' => $network_label . $network_site_verified . $network_site_code
	  ];
	} 
	// sort elements of result alphabetically
	sort($element);
	return $element;
  }
	
}
