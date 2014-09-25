<?php
/**
 * Default properties for the akismetCommentCheck snippet/hook
 *
 * @package akismet
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'comment_type',
        'desc' => 'May be blank, comment, trackback, pingback, or a made up value like "registration". ',
        'type' => 'textfield',
        'options' => '',
        'value' => 'comment',
    ),
    array(
        'name' => 'author_name_field',
        'desc' => 'Sets form field name for author name.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'name',
    ),
    array(
        'name' => 'author_email_field',
        'desc' => 'Sets form field name for author email.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'email',
    ),
    array(
        'name' => 'author_url_field',
        'desc' => 'Sets form field name for author url.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'url',
    ),
    array(
        'name' => 'comment_content_field',
        'desc' => 'Sets form field name for comment or message.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'message',
    ),
    array(
        'name' => 'spam_error_msg',
        'desc' => 'Sets error message for submissions flagged as spam.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'use_hook',
        'desc' => 'Sets whether or not to use $hook values.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
    ),
    array(
        'name' => 'fields_json',
        'desc' => 'Valid JSON string representing field values. Useful when calling this via $modx->runSnippet()',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
);

return $properties;