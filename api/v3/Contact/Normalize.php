<?php

/**
 * Contact.Normalize API: Normalizes one or more contacts
 *
 * @param  array $params  an associative array of contacts.
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_contact_normalize($params) {
  $normalize = CRM_Utils_Normalize::singleton();
  $contacts = CRM_Utils_Array::value('values', $params);
  if (is_array($contacts)) {
    foreach ($contacts as &$contact) {
      $normalize->normalize_contact($contact);
    }
    return civicrm_api3_create_success($contacts);
  }
  if (!empty($params['contact_id'])) {
    $contact = civicrm_api3('Contact', 'getsingle', [
      'id' => $params['contact_id'],
    ]);
    $normalize->normalize_contact($contact);
    civicrm_api3('Contact', 'create', $contact);
    return civicrm_api3_create_success($contact);
  }

  throw new Exception("Missing params contacts or contact_id");
}
