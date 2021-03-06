<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
require_once( dirname(__FILE__) . '/class.field.php' );
require_once( dirname(__FILE__) . '/class.seo-meta.php' );

if ( !class_exists('App_control_seo') ) :
    class App_control_seo extends Controller
    {
        public function __construct()
        {
            add_action( 'app_seo', array( $this, 'app_temp_seo' ), 10 );
            add_action( 'app_seo', array( $this, 'app_temp_seo_all' ), 20 );
        }
        public function app_temp_seo()
        {
            if ( is_home() || is_front_page() ) {
                $this->app_seo_home();  
            } elseif ( is_category() ) {
                $this->app_seo_category();
            } elseif( is_tag() ) {
                //
            } elseif ( is_single() ) {
                $this->app_seo_single();
            } elseif ( is_404() ) {
                $this->app_seo_404();
            } elseif ( is_page( array('video', 'gallery') ) ) {
                $this->app_seo_custom_page();
            } else {

            }
        }
        public function app_temp_seo_all()
        {
            global $App_setMeta;
            $site_name = explode( '//', get_bloginfo( 'url' ) );
            $App_setMeta->meta_all( array(
                'site_name' => $site_name[1],
                'app_id' => '476780242707904',
                'admin_id' => '',
                'card' => 'summary_large_image',
                'creator' => '@trangfox'
            ) );
        }
        private function app_seo_home()
        {
            global $post, $App_setMeta, $App_seo_field;
            $App_setMeta->meta( array( 
                'title' => $App_seo_field->field( array(
                    'key_name' => '_meta_seo',
                    'type' => 'title',
                ) ),
                'desc' => $App_seo_field->field( array( 
                    'type' => 'desc',
                    'key_name' => '_meta_seo',
                    'cat_id' => get_query_var( 'cat' ) ? get_query_var( 'cat' ) : '',
                    'tag_id' => get_query_var( 'tag_id' ) ? get_query_var( 'tag_id' ) : '',
                )),
                'url' => get_bloginfo( 'url' ),
                'img' => 'https://lh3.googleusercontent.com/KHhaBnVpLpxQtm6Mo8W8dJH4vqqDaiahbZ_OnUCeZsKo_Jc4DfZ1Dez0ukT7VpKNtEBe=w300',
                'type' => 'website',
                'alt' => $App_seo_field->field( array(
                    'key_name' => '_meta_seo',
                    'type' => 'title',
                ) ),
            ) );
        }
        private function app_seo_category()
        {
            global $post, $App_setMeta, $App_seo_field;
            $cat_id = get_query_var( 'cat' );
            $App_setMeta->meta( array( 
                'title' => $App_seo_field->field( array( 
                    'type' => 'title',
                    'cate_id' => $cat_id,
                    'key_name' => '_meta_cate_',
                ) ),
                'desc' => $App_seo_field->field( array( 
                    'type' => 'desc',
                    'cate_id' => $cat_id,
                    'key_name' => '_meta_cate_',
                ) ),
                'url' => get_bloginfo( 'url' ),
                'img' => $App_seo_field->field( array( 
                    'type' => 'img',
                    'cate_id' => $cat_id,
                    'key_name' => '_meta_cate_',
                ) ),
                'type' => 'object',
                'alt' => $App_seo_field->field( array( 
                    'type' => 'title',
                    'cate_id' => $cat_id,
                    'key_name' => '_meta_cate_',
                ) ),
            ) );
        }
        private function app_seo_custom_page()
        {
            global $post, $App_setMeta, $App_seo_field;
            $App_setMeta->meta( array( 
                'title' => $App_seo_field->field( array(
                    'key_name' => '_meta_seo',
                    'type' => 'title',
                ) ),
                'desc' => $App_seo_field->field( array( 
                    'type' => 'desc',
                    'key_name' => '_meta_seo',
                )),
                'url' => get_bloginfo( 'url' ),
                'img' => 'https://lh3.googleusercontent.com/KHhaBnVpLpxQtm6Mo8W8dJH4vqqDaiahbZ_OnUCeZsKo_Jc4DfZ1Dez0ukT7VpKNtEBe=w300',
                'type' => 'website',
                'alt' => $App_seo_field->field( array(
                    'key_name' => '_meta_seo',
                    'type' => 'title',
                ) ),
            ) );
        }
        private function app_seo_single()
        {
            global $post, $App_setMeta, $App_seo_field;
            $cate = get_the_category();
            $author_id = get_post_field ('post_author', $post->ID );
            $display_name = get_the_author_meta( 'nickname' , $author_id ); 
            $App_setMeta->meta( array(
                'title' => $App_seo_field->field( array(
                    'type' => 'title',
                ) ),
                'desc' => $App_seo_field->field( array(
                    'type' => 'desc',
                )),
                'url' => get_permalink(),
                'img' => $App_seo_field->field( array( 
                    'type' => 'img',
                    'post_id' => $post->ID,
                    'key_name' => '_meta_post',
                    'echo' => false,
                ) ),
                'type' => 'article',
                'alt' => $App_seo_field->field( array(
                    'type' => 'title',
                )),
                'single' => array( 
                    'enbale' => true,
                    'author' => $display_name,
                    'cate' => get_cat_name( $cate[0]->term_id ),
                    'date_public' => get_the_time( 'c' ),
                    'date_modfied' => get_the_modified_time( 'c' ),
                )
            ) );
        }
        private function app_seo_404()
        {

        }
    }
    
endif;

new App_control_seo;