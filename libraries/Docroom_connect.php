<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Docroom_connect PHP library
 *
 **/
class Docroom_connect
{

    /**
     * Privates
     */
    private $ci;
    private $key1 = '';
    private $key2 = '';
    private $host = 'https://www.thedocroom.com';

    /**
     * Constructor
     *
     * @access public
     * @param array
     */
    public function __construct()
    {
        
    }

    /**
     * Connect User
     *
     * @access public
     */
    public function authorize($key1='',$key2='')
    {
      
   
        if (!$key1 || !$key2) {
            return false;
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$this->host."/api/v2/authorize");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "key1=".$key1."&key2=".$key2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);   

        if (curl_error($ch)) {
            
            return false;
        }
      return  $response = json_decode($response);
   
    }
    function upload_file($user='',$files='',$file_name='')
    {

        $ch = curl_init();
        $cFile = curl_file_create($files,'', basename($file_name));
        $post = ["access_token"=> $user['access_token'],
        "account_id"=>$user['account_id'],'folder_id'=>$user['folder_id'],'upload_file'=>$cFile];          
        curl_setopt($ch, CURLOPT_URL,$this->host."/api/v2/file/upload");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);   

        if (curl_error($ch)) {
            
            return false;
        }
      return  $response = json_decode($response);
    }
    function create_folder($user='',$folder_name=''){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$this->host."/api/v2/folder/create");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "access_token=".$user['access_token']."&account_id=".$user['account_id']."&parent_id=".$user['parent_id']."&folder_name=".$folder_name);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);   

        if (curl_error($ch)) {
            
            return false;
        }
      return  $response = json_decode($response);
    }

    function listing($user=''){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$this->host."/api/v2/folder/listing");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "access_token=".$user['access_token']."&account_id=".$user['account_id']."&parent_folder_id=".$user['parent_folder_id']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);   

        if (curl_error($ch)) {
            
            return false;
        }
        return  $response = json_decode($response);
    }
    
    function download_file($user='',$file_id=0){
       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$this->host."/api/v2/file/download");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "access_token=".$user['access_token']."&account_id=".$user['account_id']."&file_id=".$file_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);   

        if (curl_error($ch)) {
            
            return false;
        }
      return  $response = json_decode($response);
    }

    function delete_file($user='',$file_id=0){
       
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$this->host."/api/v2/file/delete");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "access_token=".$user['access_token']."&account_id=".$user['account_id']."&file_id=".$file_id);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);   

        if (curl_error($ch)) {
            
            return false;
        }
      return  $response = json_decode($response);
    }

}