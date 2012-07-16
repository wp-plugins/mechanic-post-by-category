<?php
/*
Plugin Name: Mechanic Post by Category
Plugin URI: http://balimechanicweb.net
Description: Display Summary Post by Category widget.
Version: 1.0
Author: Aditya Subawa
Author URI: http://www.adityawebs.com
*/
class Wp_mechanicpost extends WP_Widget{
    
    function __construct(){
       $params=array(
            'description' => 'Display summary post by category widgets', 
            'name' => 'Mechanic - Post by Category'  
        );
        
        parent::__construct('WP_mechanicpost', '', $params); 
    }
    
    public function form($instance){
   extract($instance); 
    $cats_name=$this->wpmechanicpost_get_category(); 
    ?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
        <p>
            <label for="<?php echo $this->get_field_id('category');?>">Category :</label>
            <select
                class="widefat"
                id="<?php echo $this->get_field_id('category'); ?>"
                name="<?php echo $this->get_field_name('category'); ?>"
            >
                <?php foreach($cats_name as $cat_name){
                    $selected=($category == $cat_name) ? 'selected="selected"' : "";
                    echo "<option value='$cat_name' $selected>$cat_name</option>";      
                }
                ?>
            </select>
        </p>
        
         <p>
            <label for="<?php echo $this->get_field_id('total_posts');?>">Total Post :</label>
            <input
                type='number'
                style="width:40px;"
                class="widefat"
                id="<?php echo $this->get_field_id('total_posts'); ?>"
                name="<?php echo $this->get_field_name('total_posts'); ?>"
                value="<?php if(isset($total_posts)) echo esc_attr($total_posts); ?>"
            />
        </p>
        <p><label for="<?php echo $this->get_field_id('author_credit'); ?>"><?php _e('Give credit to plugin author?'); ?><input type="checkbox" class="checkbox" <?php checked( $instance['author_credit'], 'on' ); ?> id="<?php echo $this->get_field_id('author_credit'); ?>" name="<?php echo $this->get_field_name('author_credit'); ?>" /></label></p>
        <p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ZMEZEYTRBZP5N&lc=ID&item_name=Aditya%20Subawa&item_number=426267&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted" target="_blank"><img src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" alt="<?_e('Donate')?>" /></a></p>
    <?php 
	}
private function wpmechanicpost_get_category(){
        $cats_name=array();
        $cat_ids = get_all_category_ids();
        foreach($cat_ids as $cat_id) {
            $cat_name=get_cat_name($cat_id);
            $check_cat=$this->wpmechanicpost_check_cat_posts($cat_name); 
            if($check_cat){
                $cats_name[] .= $cat_name;  
            }    
        }
        return $cats_name;
    } 
private function wpmechanicpost_check_cat_posts($cat){    
        $check=get_posts('category_name='.$cat);
        if($check) return true;
    }   
    public function widget($args,$instance){
       extract($args); 
        extract($instance);
        $authorcredit = isset($instance['author_credit']) ? $instance['author_credit'] : false ; 
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        
        echo $before_widget;
            echo $before_title . $title . $after_title;
                query_posts(array 
                        (
                            'showposts' => $total_posts, 
                            'post_type' => array('post','Event'),
                            'category_name' => $category 
                        )
                );
             if(have_posts()) : while(have_posts()) : the_post(); 
             ?>
            <div class="post-widget">
                <h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <?php the_excerpt(); ?>
            </div>
            
            <?php endwhile;?>
            <?php endif;?>
            <?php wp_reset_query(); ?>
            <?php if ($authorcredit) { ?>
			<p style="font-size:10px;">
				Plugins by <a href="http://balimechanicweb.net" title="Bali Web Design">Bali Web Design</a>
			</p>
			<?php }
        echo $after_widget; 
    }
    
 }
add_action('widgets_init', 'register_wp_mechanicpost');
function register_wp_mechanicpost(){
    register_widget('Wp_mechanicpost');
}

add_action('wp_head','wpmechanicpost_styling');
function wpmechanicpost_styling(){
?>
    <style type="text/css">
        .post-widget{
            border-bottom:1px solid black;
            margin-bottom:5px;
        }
        
        .post-widget p{
            font-size:12px;
            text-align:justify;
        }
        
        .more-widget{
            font-size:13px;
        }
    </style>
<?php
}