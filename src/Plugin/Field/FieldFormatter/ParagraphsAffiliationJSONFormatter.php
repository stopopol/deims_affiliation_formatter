<?php

namespace Drupal\paragraphs_affiliation_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'ParagraphsAffiliationJSONFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "paragraphs_affiliation_json_formatter",
 *   label = @Translation("Paragraphs Affiliation JSON Formatter"),
 *   field_types = {
 *     "entity_reference_revisions"
 *   },
 *   quickedit = {
 *     "editor" = "disabled"
 *   }
 * )
 */
 
class ParagraphsAffiliationJSONFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
   
 
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Formats the affiliation field of Drupal as a JSON.');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
	
	// Render each element as markup in case of multi-values.
	$pre_json_array = [];
	foreach ($items as $delta => $item) {
	  $temp_array = [];
	  // Desired fields: field_network_name, field_network_specific_site_code, field_network_verified;
	  if ($item->entity->field_network_name->target_id) {
		$network_name_tid = $item->entity->field_network_name->target_id;
	    $full_taxonomy_term_object = taxonomy_term_load($network_name_tid);
		$temp_array['name'] = $full_taxonomy_term_object->getName();
	  }
	  else {
		break;
	  }  

	  if ($item->entity->field_network_specific_site_code->value) {
		$temp_array['code'] = $item->entity->field_network_specific_site_code->value;
	  }
	  else {
		$temp_array['code'] = "";
	  }
	  
	  if ($item->entity->field_network_verified->value) {
		$temp_array['verified'] = true;
	  }
	  else {
		$temp_array['verified'] = false;
	  }
	
	  array_push($pre_json_array, $temp_array);
	  
	}

	$json_object = json_encode($pre_json_array);
	$json_element = substr($json_object, 1, -1);
	// print json_encoded array into a single-value element output
	$element[$delta] = [
		'#markup' => $json_element
	];
	
	return $element;
  }
	
}