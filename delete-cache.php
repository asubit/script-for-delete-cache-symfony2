<?php
/*
The MIT License (MIT)
Copyright (c) 2015 Antoine Subit
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/
ini_set('display_errors', 1);
error_reporting(E_ALL); 

// Function used to delete cache dirs
function DeleteDirectory($dir) {
    if (is_dir($dir) && $dir!=".svn") {
        if($dh = opendir($dir)) {
            while(($file = readdir($dh)) !== false) {
                if($file != '.' && $file != '..' && $file != '.svn') {
                    if(is_dir($dir .'/'. $file)) {
                        echo '<br/>DIR - '.$dir.'/'.$file;
                        // We delete the directory content
                        DeleteDirectory($dir.'/'.$file);
                        // We delete the directory
                        rmdir($dir.'/'.$file);
                    } else {
                        echo '<br/>FIL - '.$dir.'/'.$file;
                        if(is_file($dir .'/'. $file)) unlink($dir .'/'. $file);
                    }
                }
                
            }
            closedir($dh);
        }
    }
}

// Test IP
$ips = array(
    '127.0.0.1',
    // Add here your IP address
);
$okIp 		= in_array(@$_SERVER['REMOTE_ADDR'], $ips);

// Change token here
$okToken 	= !(!isset($_GET['token']) || $_GET['token'] != '2A3ACB494FC1C8F7D8D78623B452E');
// Test token
if($okIp && $okToken) {

    // Manage APC cache, with little test if the extension is busy
    if(in_array('apc', get_loaded_extensions())) {
    
        /*echo '<br/>APC USER CACHE';
        echo '<pre>';
        var_dump(apc_cache_info('user'));
        echo '</pre>';*/
        echo 'PURGE APC USER CACHE : '.(apc_clear_cache('user') ? 'SUCCESS' : 'FAILURE').'<br />';
        echo '<br />-----<br />';

       /* echo '<br/>APC SYSTEM CACHE';
        echo '<pre>';
        var_dump(apc_cache_info());
        echo '</pre>';*/
        echo 'PURGE APC SYSTEM CACHE : '.(apc_clear_cache() ? 'SUCCESS' : 'FAILURE').'<br />';
        echo '<br />----- ----- -----<br />';
        
    } else {
        
        echo '<br/>APC EXTENSION NOT LOADED';
        
    }
    
    // Manage Symfony cache
    $dir = '../app/cache';

    echo '<br/>BEGIN PURGE CACHE';
    
    if(is_dir($dir)) {
        if($dh = opendir($dir)) {
            while(($file = readdir($dh)) !== false) {
                if(is_dir($dir.'/'.$file) && $file != '.' && $file != '..' && $file != '.svn') {

                    echo '<br/>'.$dir.'/'.$file;
                    // We delete the directory content
                    DeleteDirectory($dir.'/'.$file);
                    // We delete the directory
                    rmdir($dir.'/'.$file);

                }
            }
            closedir($dh);
        }
    } else  {
        die('Specified directory does not exist');
    }

    echo '<br/>END PURGE CACHE';

} else {

    header('Status: 404 Not Found');
    exit();

}
