<?php 

/**
 * 
 */


 class Wedevs_Custom_texonomy_make{

    public function __construct(){
        add_action('init', [$this, 'init'] );
        add_action('init', [$this, 'register_post_type'] );
        add_action('init', [$this, 'register_taxonomies']);

    }

    /**
     * 
     * add summry of init function for add filter hooks
     */

     public function init(){
        add_filter('the_content', [$this, 'add_movies_details'] );
        add_filter('the_title', [$this, 'add_movies_years'],10,2 );

        add_filter('the_content', [$this, 'add_related_movies'] );
     }


     

    

    /**
     * Summary of register_post_type
     * @return void 
     * 
     */
    public function register_post_type(){
        register_post_type('movie',[
            'label' => 'Movies',
                'labels' => [
                    'name'=>'movies',
                    'singular_name'=>'movie',
                    'add_new_item'=>'Add New Movie',
                ],
                'public' => true,
                'has_archive' => true,
                'taxonomies' => ['genres','actors','Directors','years'],
                'supports' => ['title','editor','thumbnail']
            ]);
    }

    public function register_taxonomies(){

          /**
         * Summary of register_taxonomies for Genres
         */
        register_taxonomy('genres', ['movie'],[
            'label' => 'Genres',
                'labels' => [
                    'name'=>'genres',
                    'singular_name'=>'genre',
                    'add_new_item'=>'Add New Genre',
                ],
                'public' => true,
                'has_archive' => true,
                'hierarchical' => true,
               'show_admin_column' => true
        ]);

        /**
         * Summary of register_taxonomies for actors
         */
        register_taxonomy('actors', ['movie'],[
            'label' => 'actors',
                'labels' => [
                    'name'=>'Actors',
                    'singular_name'=>'actors',
                    'add_new_item'=>'Add New actors',
                ],
                'public' => true,
                'hierarchical' => false,
                'has_archive' => true,
                'hierarchical' => true,
                'show_admin_column' => true
        ]);


        /**
         * Summary of register_taxonomies for Directors
         */
        register_taxonomy('Directors', ['movie'],[
            'label' => 'Directors',
                'labels' => [
                    'name'=>'Directors',
                    'singular_name'=>'Director',
                    'add_new_item'=>'Add New Directors',
                ],
                'public' => true,
                'has_archive' => true,
                'hierarchical' => true,
                'show_admin_column' => true
        ]);


        /**
         * Summary of register_taxonomies for Years
         */
        register_taxonomy('Years', ['movie'],[
            'label' => 'Years',
                'labels' => [
                    'name'=>'Years',
                    'singular_name'=>'Year',
                    'add_new_item'=>'Add New Years',
                ],
                'public' => true,

                'rewrite' => [
                    'slug' => 'movie-year',
                            ],
                'hierarchical' => false,
                'has_archive' => true,
                'show_admin_column' => true
        ]);
    }
    /**
     * summary of add_movies_details
     * if I ckick on movie it will show genre thats related to movie
     * 
     */

    public function add_movies_details($content){

        // $post= get_post( post: get_the_ID() );

        // if($post->post_type !== 'movies'){
        //     return $content;
        // }

       $genre= get_the_term_list(get_the_ID(), 'genres','',', ');
       $actors= get_the_term_list(get_the_ID(), 'actors','',', ');
       $directors= get_the_term_list(get_the_ID(), 'Directors','',', ');
       $years= get_the_term_list(get_the_ID(), 'Years','',', ');

        $info='<ul>';
        if($genre){
            $content .= '<li>';
            $content .= '<strong>Genres :</strong> ';
            $content .= $genre;
            $content .='</li>';
          
        }
        if($actors){
            $content .= '<li>';
            $content .= '<strong>Actors :</strong> ';
            $content .= $actors;
            $content .='</li>';
          
        }
       
        if($directors){
            $content .= '<li>';
            $content .= '<strong>Directors :</strong> ';
            $content .= $directors;
            $content .='</li>';
          
        }
        if(! is_wp_error($years) && $years){
            $content .= '<li>';
            $content .= '<strong>Years :</strong> ';
            $content .= $years;
            $content .='</li>';
          
        }
       
        $info.='</ul>';
        return $content;
     }


     public function add_movies_years($title, $id){
        
        $post= get_post( get_the_ID() );

       

        if($post->post_type !== 'movie'){
            return $title;
        }
        

        $years= get_the_terms(get_the_ID(), 'Years');
        /**
         * if exists years so show the year
         */
            if($years){
                $title .= ' (' . $years[0]->name . ')';
            }
                return $title;
      }



      public function add_related_movies($content){
          $genre= get_the_terms(get_the_ID(), 'genres');


            //if it doesn't have genre so return content
          if(!$genre){
              return $content;
          }

         /**
          * @var WP_Query 
          * @var mixed
          */
          //dump(wp_list_pluck($genre, 'term_id'));

         $query= new WP_Query([
             'post_type' => 'movie',
             'posts_per_page' => -1,
             'post_not_in' => [get_the_ID()],
             'tax_query' => [
                'relation' => 'OR',
                [
                    'taxonomy' => 'genres',
                    
                    'terms' => wp_list_pluck($genre, 'term_id')
                ]
             ]
         ]);

        // dump($query->get_posts());
        if(! $query->have_posts()){
            return $content;
        }
        $related= '<h2>Related Movies</h2>';
        $related .= '<ul>';
        foreach($query->get_posts() as $post){
            $related .= '<li><a href="' . get_permalink($post) . '">' .get_the_title($post) . '</a></li>';
        }
        $related .= '</ul>';
          return $content . $related;
      }

     
 }

  /**
      * helper function for register_post_type
      */

      function dump($data){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
      }