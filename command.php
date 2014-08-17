<?php

if ( !defined( 'WP_CLI' ) ) return;

class Static_Wordpress extends WP_CLI_Command {

    protected function _init()
    {
        include dirname(__FILE__) . '/library.php';

        WP_CLI::success('Loaded library');


	if ( !class_exists( 'StaticWordpress' ) )
	{
		return;
	}
    }


    function generate($args, $assoc_args) {

        $this->_init();

        $plugin = new StaticWordpress_Cli();

        $uris = array();

        $uris = $plugin->get_index_uris($uris);
        $uris = $plugin->get_tag_uris($uris);
        $uris = $plugin->get_page_uris($uris);
        $uris = $plugin->get_post_uris($uris);

	$local_uris = $uris;

        foreach($uris as $uri) {
                try {
			$web = new WebInterface($uri);
			$local_uris = array_merge($local_uris, $web->get_local_linked_resources());
			$local_uris = array_unique($local_uris);
			
                }
                catch (Exception $e) {
				WP_CLI::warning($e);
                }
                WP_CLI::success($uri);

        }
	
	var_dump($uris);

        }

}

WP_CLI::add_command( 'static_wordpress', 'Static_Wordpress' );



