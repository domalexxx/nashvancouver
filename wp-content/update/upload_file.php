<?php
ob_start();
include_once('../../../wp-config.php');
include_once('../../../wp-load.php');
$array = array();

$upload_dir = wp_upload_dir(); 
$uploadfile = $upload_dir['path'] . '/' . basename($_FILES['uploadfile']['name']);


// These files need to be included as dependencies when on the front end.
require_once( ABSPATH . 'wp-admin/includes/image.php' );
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/media.php' );

// Let WordPress handle the upload.
// Remember, 'my_image_upload' is the name of our file input in our form above.
$attachment_id = media_handle_upload( 'uploadfile', 0 );

if ( is_wp_error( $attachment_id ) ) {
    $array['status'] = "error";
	$array['message'] = "Possible file upload attack!\n";
} else {
	$array['status'] = "success";
	$array['attachment_id'] = $attachment_id;
	$array['message'] = wp_get_attachment_url( $attachment_id );
}
echo json_encode($array);
die;

/*echo '<pre>';
if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $uploadfile)) {
    echo "File is valid, and was successfully uploaded.\n";
} else {
    echo "Possible file upload attack!\n";
}
print "</pre>";*/
?>