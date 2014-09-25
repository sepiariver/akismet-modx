<?php
/**
 * akismet
 *
 * Copyright 2014 by YJ Tso <yj@modx.com>
 * Attribution: 
 * http://akismet.com/development/api/
 * Mark Hamstra hello@modmore.com
 *
 * This file is part of akismet, an integration of the Akismet API to MODX.
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

class Akismet {
    /**
     * @var modX|null $modx
     */
    public $modx = null;
    /**
     * @var array
     */
    public $config = array();
    /**
     * @var bool
     */
    public $debug = false;


    /**
     * @param \modX $modx
     * @param array $config
     */
    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;

        $corePath = $this->modx->getOption('akismet.core_path',$config,$this->modx->getOption('core_path').'components/akismet/');
        $this->config = array_merge(array(
            'basePath' => $corePath,
            'corePath' => $corePath,
            'modelPath' => $corePath.'model/',
            'userAgentPlugin' => $modx->getOption('akismet.modx_useragent'),
            'modxVersion' => $modx->getOption('settings_version'),
            'userAgent' => 'MODX/' . $this->config['modxVersion']. ' | ' . $this->config['userAgentPlugin'],
        ),$config);

        $this->debug = (bool)$this->modx->getOption('akismet.debug',null,false);
    }

    /**
     * Authenticates your Akismet API key
     * @return boolean
     */

    public function verify_key( $key, $blog ) {
        $blog = urlencode($blog);
        $request = 'key='. $key .'&blog='. $blog;
        $host = $http_host = 'rest.akismet.com';
        $path = '/1.1/verify-key';
        $port = 80;
        $akismet_ua = $this->config['userAgent'];
        $content_length = strlen( $request );
        $http_request  = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $http_request .= "Content-Length: {$content_length}\r\n";
        $http_request .= "User-Agent: {$akismet_ua}\r\n";
        $http_request .= "\r\n";
        $http_request .= $request;
        $response = '';
        if( false != ( $fs = @fsockopen( $http_host, $port, $errno, $errstr, 10 ) ) ) {
             
            fwrite( $fs, $http_request );
     
            while ( !feof( $fs ) )
                $response .= fgets( $fs, 1160 ); // One TCP-IP packet
            fclose( $fs );
             
            $response = explode( "\r\n\r\n", $response, 2 );
        }
         
        if ( 'valid' == $response[1] )
            return true;
        else
            return false;
    }

    /**
     * Checks comment
     * @return boolean
     */

    public function comment_check( $key, $data ) {
        $request = 'blog='. urlencode($data['blog']) .
                   '&user_ip='. urlencode($data['user_ip']) .
                   '&user_agent='. urlencode($data['user_agent']) .
                   '&referrer='. urlencode($data['referrer']) .
                   '&permalink='. urlencode($data['permalink']) .
                   '&comment_type='. urlencode($data['comment_type']) .
                   '&comment_author='. urlencode($data['comment_author']) .
                   '&comment_author_email='. urlencode($data['comment_author_email']) .
                   '&comment_author_url='. urlencode($data['comment_author_url']) .
                   '&comment_content='. urlencode($data['comment_content']);
        $host = $http_host = $key.'.rest.akismet.com';
        $path = '/1.1/comment-check';
        $port = 80;
        $akismet_ua = $this->config['userAgent'];
        $content_length = strlen( $request );
        $http_request  = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $http_request .= "Content-Length: {$content_length}\r\n";
        $http_request .= "User-Agent: {$akismet_ua}\r\n";
        $http_request .= "\r\n";
        $http_request .= $request;
        $response = '';
        if( false != ( $fs = @fsockopen( $http_host, $port, $errno, $errstr, 10 ) ) ) {
             
            fwrite( $fs, $http_request );
     
            while ( !feof( $fs ) )
                $response .= fgets( $fs, 1160 ); // One TCP-IP packet
            fclose( $fs );
             
            $response = explode( "\r\n\r\n", $response, 2 );
        }
         
        if ( 'true' == $response[1] )
            return true;
        else
            return false;
    }
    
    /**
     * Submit spam (not yet supported < Version 1)
     * @return boolean
     */
     
    public function submit_spam( $key, $data ) {
        $request = 'blog='. urlencode($data['blog']) .
                   '&user_ip='. urlencode($data['user_ip']) .
                   '&user_agent='. urlencode($data['user_agent']) .
                   '&referrer='. urlencode($data['referrer']) .
                   '&permalink='. urlencode($data['permalink']) .
                   '&comment_type='. urlencode($data['comment_type']) .
                   '&comment_author='. urlencode($data['comment_author']) .
                   '&comment_author_email='. urlencode($data['comment_author_email']) .
                   '&comment_author_url='. urlencode($data['comment_author_url']) .
                   '&comment_content='. urlencode($data['comment_content']);
        $host = $http_host = $key.'.rest.akismet.com';
        $path = '/1.1/submit-spam';
        $port = 80;
        $akismet_ua = $this->config['userAgent'];
        $content_length = strlen( $request );
        $http_request  = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $http_request .= "Content-Length: {$content_length}\r\n";
        $http_request .= "User-Agent: {$akismet_ua}\r\n";
        $http_request .= "\r\n";
        $http_request .= $request;
        $response = '';
        if( false != ( $fs = @fsockopen( $http_host, $port, $errno, $errstr, 10 ) ) ) {
             
            fwrite( $fs, $http_request );
     
            while ( !feof( $fs ) )
                $response .= fgets( $fs, 1160 ); // One TCP-IP packet
            fclose( $fs );
             
            $response = explode( "\r\n\r\n", $response, 2 );
        }
         
        if ( 'Thanks for making the web a better place.' == $response[1] )
            return true;
        else
            return false;
    }   
    
    
    /**
     * Submit Ham (not yet supported < Version 1)
     * @return boolean
     */
    
    public function submit_ham( $key, $data ) {
        $request = 'blog='. urlencode($data['blog']) .
                   '&user_ip='. urlencode($data['user_ip']) .
                   '&user_agent='. urlencode($data['user_agent']) .
                   '&referrer='. urlencode($data['referrer']) .
                   '&permalink='. urlencode($data['permalink']) .
                   '&comment_type='. urlencode($data['comment_type']) .
                   '&comment_author='. urlencode($data['comment_author']) .
                   '&comment_author_email='. urlencode($data['comment_author_email']) .
                   '&comment_author_url='. urlencode($data['comment_author_url']) .
                   '&comment_content='. urlencode($data['comment_content']);
        $host = $http_host = $key.'.rest.akismet.com';
        $path = '/1.1/submit-ham';
        $port = 80;
        $akismet_ua = $this->config['userAgent'];
        $content_length = strlen( $request );
        $http_request  = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $http_request .= "Content-Length: {$content_length}\r\n";
        $http_request .= "User-Agent: {$akismet_ua}\r\n";
        $http_request .= "\r\n";
        $http_request .= $request;
        $response = '';
        if( false != ( $fs = @fsockopen( $http_host, $port, $errno, $errstr, 10 ) ) ) {
             
            fwrite( $fs, $http_request );
     
            while ( !feof( $fs ) )
                $response .= fgets( $fs, 1160 ); // One TCP-IP packet
            fclose( $fs );
             
            $response = explode( "\r\n\r\n", $response, 2 );
        }
         
        if ( 'Thanks for making the web a better place.' == $response[1] )
            return true;
        else
            return false;
    }
    
}
