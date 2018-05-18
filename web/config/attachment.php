<?php
/**
 * 附件存储及相关配置
 */

$config = array(
        'max_attach_num' => '3',
        'mimetype' => array('image/gif', 'image/jpeg', 'image/png', 'image/bmp','text/plain'),
        // 单位：kb（1kb = 1024bytes）
        'min_size' => '0',
        // 单位：kb（1kb = 1024bytes）
        'max_size' => '300'); 

$config['adapters']['disk'] = array(
        'adapter' => 'Disk', 
        'root' => ZEED_PATH_UPLOAD, 
        'url_prefix' => '/upload', 
        'url_thumb_prefix' => '/upload/thumb', 
        'url_mng_prefix' => '/upload',  
        'url_thumb_mng_prefix' => '/upload/thumb',
        'url_prefix_b' => '/uploads');

$config['adapters']['mongo'] = array(
        'adapter' => 'Mongo', 
        'root' => '', 
        'url_prefix' => '/upload', 
        'url_thumb_prefix' => '/upload/thumb', 
        'url_mng_prefix' => '/upload',  
        'url_thumb_mng_prefix' => '/upload/thumb', 
        'url_prefix_b' => '/uploads', 
        'mongodb' => array(
                'server' => 'mongodb://localhost:27017', 
                'server_options' => array(), 
                'db' => 'ice_inews8_attach', 
                // Replica Sets
                'replicaSet' => false,
                // Persistent connections need an identifier string
                'persist' => 'i8attach',  
                'showdebug' => 0));

$config['adapters']['default'] = $config['adapters']['disk'];

return $config;
