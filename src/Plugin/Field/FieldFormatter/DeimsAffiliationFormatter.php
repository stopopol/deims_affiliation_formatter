<?php

namespace Drupal\deims_affiliation_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'DeimsAffiliationFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "deims_affiliation_formatter",
 *   label = @Translation("DEIMS Affiliation Formatter"),
 *   field_types = {
 *     "entity_reference_revisions"
 *   },
 *   quickedit = {
 *     "editor" = "disabled"
 *   }
 * )
 */
 
class DeimsAffiliationFormatter extends FormatterBase {

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
	global $base_url;

	// Render each element as markup in case of multi-values.
	foreach ($items as $delta => $item) {

	  // Desired fields: field_network_name, field_network_specific_site_code, field_network_verified;
	  if ($item->entity) {
		 if ($item->entity->field_network->entity) {
			 $network_label = $item->entity->field_network->entity->field_name->value;
			 $network_uuid = $item->entity->field_network->entity->get('uuid')->value;
			 $network_url = '<a href="' . $base_url . '/network/' . $network_uuid . '">' . $network_label . '</a>';
		 }
		 else {
		 	break;
		 }
	  }
	  else {
		break;
	  }  
	  // add network related site code if existant
	  if ($item->entity->field_network_specific_site_code->value) {
		$network_site_code = "<sub>  (" . $item->entity->field_network_specific_site_code->value . ")</sub>";
	  }
	  else {
		$network_site_code = "";
	  }
	  // add verification status, if it is a verified site
	  if ($item->entity->field_network_verified->value) {
		  $network_site_verified = $item->entity->field_network_verified->value;
		  $network_site_verified = '<sup class="green-colour">✔</sup>';
		  $network_element = '<div class="verification-tooltip">' . $network_url . $network_site_verified . $network_site_code . '<span class="verification-tooltiptext verfication-colour-green verfication-border-colour-green">This site is a verified "' . $network_label. '" member.</span></div>';
	  
	  }
	  else {
		  $network_site_verified = '<sup class="red-colour">✖</sup>';
		  $network_element = '<div class="verification-tooltip">' . $network_url . $network_site_verified . $network_site_code . '<span class="verification-tooltiptext verfication-colour-red verfication-border-colour-red">The affiliation of this site with "' . $network_label. '" is not verified.</span></div>';
	  
	  }
	  
	  $element[$delta] = [
		'#markup' => $network_element,
		'#attached' => [
			'library' => [
			  'deims_affiliation_formatter/deims-affiliation-formatter-style',
			],
		]
	  ];
	} 
	// sort elements of result alphabetically
	sort($element);
	return $element;
  }
	
}
