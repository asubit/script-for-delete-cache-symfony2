<?php

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
                        // On supprime le contenu du dossier
                        DeleteDirectory($dir.'/'.$file);
                        // On supprime le dossier
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

// Test token

$okToken 	= !(!isset($_GET['token']) || $_GET['token'] != '2A3ACB494FC1C8F7D8D78623B452E');




if($okIp && $okToken) {

    /*
    if(rmdir('../vendor') {
        print 'vendor';
    } else {
        print 'erreur vendor';
    }
    */
    
    // Gestion du cache APC, avec petit test si l'extension est bien chargée
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
    
    // --------
    
    // Gestion du cache SF
    $dir = '../app/cache';

    echo '<br/>DEBUT PURGE CACHE';
    
    if(is_dir($dir)) {
        if($dh = opendir($dir)) {
            while(($file = readdir($dh)) !== false) {
                if(is_dir($dir.'/'.$file) && $file != '.' && $file != '..' && $file != '.svn') {

                    echo '<br/>'.$dir.'/'.$file;
                    // On supprime le contenu du dossier
                    DeleteDirectory($dir.'/'.$file);
                    // On supprime le dossier
                    rmdir($dir.'/'.$file);

                }
            }
            closedir($dh);
        }
    } else  {
        die('Le repertoire specifie n\'existe pas');
    }

    echo '<br/>FIN PURGE CACHE';

} else {

    header('Status: 404 Not Found');
    exit();

}
