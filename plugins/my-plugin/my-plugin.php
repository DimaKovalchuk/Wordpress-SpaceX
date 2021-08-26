<?php 

/**
Plugin Name: My plugin
*/
add_action( 'init', 'get_spacex' );   
function get_spacex() {
    // Выдача из транзитного кэша
    $cached = get_transient( 'space' );
    if ( $cached !== false )
        return $cached;
	
	
	$url = 'https://api.spacexdata.com/v4/launches/';
	$response = wp_remote_get( $url);
	$response_code = wp_remote_retrieve_response_code( $response );
	if( $response_code == 200 ) {
		$json = wp_remote_retrieve_body( $response );
		$data = json_decode( $json, true );
		var_dump( $data );
	}
	
    // Запись в транзитный кэш на 1 час
    set_transient( 'space', $data, 1 * HOUR_IN_SECONDS );
	
	
	
	foreach ($data as $i => $row){
		$ID = $row["flight_number"];
		$title = $row["name"];
		$post_type = 'spacex';
		$content = $row["details"];
		$data_1 = $row["date_local"];
		$picture_url = $row["links"]["patch"]["small"];
		$video_url = $row["links"]["youtube_id"];
		$rocet = $row["rocket"];
		
		$my_post = array(
                'ID' => $ID,
				'post_type'	=> $post_type,
                'post_title'   => $title,
                'post_content' => $content,
				'meta1_data' => $data_1,
				'meta1_picture_url' => $picture_url,
				'meta1_video_url' => $video_url,
				'meta2_rocet' => $rocet
                
            );

		wp_insert_post( $my_post );
		
	
	}

    
	return $data;
	
	
	
	
   
}
