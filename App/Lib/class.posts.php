<?php 
if ( !class_exists('App_post' ) ) :
    class App_post 
    {
        public function getAuthor( $att = array() ) 
        {
            $author_id = get_post_field ('post_author', $att['post_id']);
            $display_name = get_the_author_meta( 'display_name' , $att['post_id'] ); 
            return $display_name;
        }
        public function title( $att = array() )
        {
            if ( get_post_format( $att['post_id'] ) ) {
                $format = '<span class="App-format App-format-'.get_post_format( $att['post_id'] ).'"></span>';
            } else {
                $format = '';
            }
            $out  = '<div class="title App-icon">';
            if ( $att['type'] == 'swiper' ) {
                $out .= '<h3 id="swiper-title">'.$format.'<a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a></h3>';
            } else {
                $out .= '<h3>';
                $out .= $format;
                $out .= '<a href="'.get_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a>';
                $out .= '</h3>';
            }
            $out .= '</div>';
            return $out;
        }
        public function desc()
        {
            $excerpt = get_the_excerpt();
            if ( $excerpt ) {
                $out = '<div class="desc">';
                $charlength = '150';
                if ( mb_strlen( $excerpt ) > $charlength ) {
                    $subex = mb_substr( $excerpt, 0, $charlength - 5 );
                    $exwords = explode( ' ', $subex );
                    $excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
                    if ( $excut < 0 ) {
                        $out .=  mb_substr( $subex, 0, $excut );
                    } else {
                        $out .= $subex;
                    }
                    $out .= '...';
                } else {
                    $out .= $excerpt;
                }
                $out .= '</p></div>';
                return $out;
            } else {
                //
            }
        }
        public function tag()
        {
            $tags = get_the_tags();
            if ( $tags ) {
                $out  = '<div class="footer">';
                foreach ($tags as $atts ) {
                    $out .= '<a href="'.get_tag_link( $atts->term_id ).'" title="Thẻ '.$atts->name.'">';
                    $out .= $atts->name;
                    $out .= '</a>';
                }
                $out .= '</div>';
            } else {
                $out = '';
            }
            return $out;
        }
        public function media( $atts = array() )
        {
            global $App_getMetapost;
            if ( get_post_format( $atts['post_id'] ) == 'gallery' ) {
                $class = 'gallery';
            } elseif( get_post_format( $atts['post_id'] ) == 'video' ) {
                $class = 'video';
            } else {
                $class = 'media';
            }
            $out  = '<div class="app-'.$class.' thumbnail">';
            $out .= $App_getMetapost->media( $atts );
            $out .= '</div>';
            return $out;
        }
        public function meta()
        {
            $cats = get_the_category();
            $out  = '<div class="postmeta">';
            $out .= '<span class="category"><a href="'.get_category_link( $cats[0]->term_id ).'" title="'.$cats[0]->name.'">'.$cats[0]->name.'</a></span>';
            $out .= '<time>'.human_time_diff( get_the_time('U'), current_time('timestamp') ).'Trước</time>';
            $out .= '</div>';
            return $out;
        }
        public function listPost( $atts = array() )
        {
            global $App_mobile;
            if ( $atts['type'] == 'normal' ) {
                $out_info = '<div class="app-info">';
                $out_endinfo = '</div>';
                $out_item = '<div class="app-info-item col-md-7">';
                $out_enditem = '</div>';
                if ( ! $App_mobile->isMobile() ) {
                    $desc = $this->desc();
                } else {
                    $desc = '';
                }
                $postitemClass = 'App-content-item';
            } else {
                $out_info = '';
                $out_endinfo = '';
                $out_item = '';
                $out_enditem = '';
                $desc = '';
                $postitemClass = 'App-swiper swiper-slide';
            }
            $out  = '';
            $out .= '<article data-post="trangfox-'.$atts['post_id'].'" class="'.$postitemClass.'">';
            $out .= $out_info;
            $out .= $this->media( array(
                'post_id' => $atts['post_id'],
                'lazyClass' => ( isset( $atts['thumbnail']['lazyClass'] ) ) ? $atts['thumbnail']['lazyClass'] : 'app-lazy',
                'gallery' => ( $App_mobile->isMobile() ) ? true : false,
                'alt' => get_the_title(),
                'key_name' => ( isset( $atts['thumbnail']['key_name'] ) ) ? $atts['thumbnail']['key_name'] : '_meta_post',
                'echo' => ( isset( $atts['thumbnail']['echo'] ) ) ? $atts['thumbnail']['echo'] : true,
                'type' => $atts['type'],
            ) ); 
            if ( get_post_format( $atts['post_id'] ) != 'video' && get_post_format( $atts['post_id'] ) != 'gallery' && $atts['type'] != 'swiper' || ! $App_mobile->isMobile() ) {
                $out .= $out_item;
                $out .= $this->title( array(
                    'post_id' => $atts['post_id'],
                    'type' => $atts['type'],
                ) );
                $out .= $this->meta();
                $out .= $desc;
                $out .= $out_enditem;
            }
            $out .= $out_endinfo;
            $out .= '</article>';
            return $out;
        }
    }
endif;
$App_ListPost = new App_post;