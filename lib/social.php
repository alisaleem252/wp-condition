<?php
/// Start Social Class

class WP_Condition_shareCount {
	private $url,$timeout;
	function __construct($url,$timeout=10) {
	$this->url=rawurlencode($url);
	$this->timeout=$timeout;
}
function get_social_counts(){
	

	$json_string = $this->file_get_contents_curl('https://count-server.sharethis.com/v2.0/get_counts?url='. $this->url);
	$json = json_decode($json_string, true);
	return $json;
	}
function get_tweets() { 
	$json_string = $this->file_get_contents_curl('http://urls.api.twitter.com/1/urls/count.json?url=' . $this->url);
	$json = json_decode($json_string, true);
	return isset($json['count'])?intval($json['count']):0;
}
function get_linkedin() { 
	$json_string = $this->file_get_contents_curl("http://www.linkedin.com/countserv/count/share?url=$this->url&format=json");
	$json = json_decode($json_string, true);
	return isset($json['count'])?intval($json['count']):0;
}
function get_fb() {
	$json_string = $this->file_get_contents_curl('http://api.facebook.com/restserver.php?method=links.getStats&format=json&urls='.$this->url);
	$json = json_decode($json_string, true);
	return isset($json[0]['total_count'])?intval($json[0]['total_count']):0;
}

function get_stumble() {
	$json_string = $this->file_get_contents_curl('http://www.stumbleupon.com/services/1.01/badge.getinfo?url='.$this->url);
	$json = json_decode($json_string, true);
	return isset($json['result']['views'])?intval($json['result']['views']):0;
}
function get_delicious() {
	$json_string = $this->file_get_contents_curl('http://feeds.delicious.com/v2/json/urlinfo/data?url='.$this->url);
	$json = json_decode($json_string, true);
	return isset($json[0]['total_posts'])?intval($json[0]['total_posts']):0;
}
function get_pinterest() {
	$return_data = $this->file_get_contents_curl('http://api.pinterest.com/v1/urls/count.json?url='.$this->url);
	$json_string = preg_replace('/^receiveCount\((.*)\)$/', "\\1", $return_data);
	$json = json_decode($json_string, true);
	return isset($json['count'])?intval($json['count']):0;
}
private function file_get_contents_curl($url){
	
	 $response = wp_remote_post( $url, array(
        'method' => 'POST',
        'timeout' => 45,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        //'body' => $query,
        'cookies' => array()
        )
    );

    if ( is_wp_error( $response ) ) {
		die($response->get_error_message());		
	}
	else{
		return wp_remote_retrieve_body($response);
		}
//return $cont;
}

}

/// END SOCIALSD CALCSAD
?>