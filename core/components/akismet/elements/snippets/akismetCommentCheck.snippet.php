<?php
/**
 * akismetCommentCheck
 *
 * Copyright 2014 by YJ Tso <yj@modx.com>
 * Attribution: 
 * http://akismet.com/development/api/
 * 
 *
 * This snippet is part of akismet, an integration of the Akismet API to MODX.
 * A valid Akismet API key is required.
 *
 * akismet is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * akismet is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * akismet; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package akismet
 * 
 * 
*/

// Settings
$api_key = $modx->getOption('akismet.api_key');
$key_verified = $modx->getOption('akismet.key_verified');
// Merge in Formit config. If it exists, it will overwrite $scriptProperties
if ($hook && is_array($hook->formit->config)) array_merge($scriptProperties, $hook->formit->config);
// Options
$comment_type = $modx->getOption('comment_type', $scriptProperties, 'comment');
$author_name_field = $modx->getOption('author_name_field', $scriptProperties, 'name');
$author_email_field = $modx->getOption('author_email_field', $scriptProperties, 'email');
$author_url_field = $modx->getOption('author_url_field', $scriptProperties, 'url');
$comment_content_field = $modx->getOption('comment_content_field', $scriptProperties, 'message');
$spam_error_msg = $modx->getOption('spam_error_msg', $scriptProperties, 'Hmm...looks like we are missing some ham.');
$use_hook = $modx->getOption('use_hook', $scriptProperties, true);
$debug = false;

// For providing fields in json - gets overwritten if $hook is available and $use_hook is enabled 
if ($debug) $scriptProperties['fields_json'] = '{"name":"viagra-test-123","email":"test@test.com"}';
$fields_array = $modx->fromJSON($scriptProperties['fields_json']);
if (!$fields_array || !is_array($fields_array)) $fields_array = array();
if ($debug) var_dump($fields_array);

/* Grab the Akismet class */
$path = $modx->getOption('akismet.core_path', null, $modx->getOption('core_path') . 'components/akismet/');
$path .= 'model/akismet/';
if (is_readable($path . 'akismet.class.php')) {
    $akismet = $modx->getService('akismet','Akismet', $path);
}
if (!($akismet instanceof Akismet)) {
    return false;
    $modx->log(modX::LOG_LEVEL_ERROR, '[askismetCommentCheck] Failed to load Akismet class.');
}

/* Call to verify key function */
if (!$key_verified) {
    $success = $akismet->verify_key($api_key, $modx->getOption('site_url'));
    if ($success) { 
        $setting = $modx->getObject('modSystemSetting', 'akismet.key_verified');
        $setting->set('value', 1);
        $setting->save();
    } else {
        return false;
        $modx->log(modX::LOG_LEVEL_ERROR, '[askismetCommentCheck] Failed to verify Akismet API key.');
    }
}

/* Call to comment check */
$data = array('blog' => $modx->getOption('site_url'),
              'user_ip' => $_SERVER['REMOTE_ADDR'],
              'user_agent' => $_SERVER['HTTP_USER_AGENT'],
              'referrer' => $_SERVER['HTTP_REFERER'],
              'permalink' => $modx->getOption('site_url') . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING'],
              'comment_type' => $comment_type,
              'comment_author' => $fields_array[$author_name_field],
              'comment_author_email' => $fields_array[$author_email_field],
              'comment_author_url' => $fields_array[$author_url_field],
              'comment_content' => $fields_array[$comment_content_field],
        );
if ($hook && $use_hook) {
    $data['comment_author'] = $hook->getValue($author_name_field);
    $data['comment_author_email'] = $hook->getValue($author_email_field);
    $data['comment_author_url'] = $hook->getValue($author_url_field);
    $data['comment_content'] = $hook->getValue($comment_content_field);       
}

/* Test values per Akismet docs */
if (isset($debug) && !empty($debug)) {
    $data['is_test'] = 1;
    if ($debug === 'spam') $data['comment_author'] = 'viagra-test-123';
    if ($debug === 'not spam') $data['user_role'] = 'administrator';
}

/* Akismet passes back true (it's spam) or false (it's ham) */
$spam = $akismet->comment_check($api_key, $data);
if ($spam) {
    if ($hook) $hook->addError('comment_spam', $spam_error_msg);
    return false; // Fail the hook due to spam
} else {
    return true; // It's not spam and the hook succeeds
}
