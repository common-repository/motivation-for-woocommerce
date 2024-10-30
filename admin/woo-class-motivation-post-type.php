<?php
/**
 * Motivation post type
 */
if ( ! class_exists( 'Woo_Motivation_Post_Type' ) ) :

  class Woo_Motivation_Post_Type {

    public function __construct() { 
      $this->init();
      add_action( 'add_meta_boxes', array($this, 'woo_motivation_box_content') );
      add_action( 'save_post_motivation',  array($this, 'woo_motivation_save_meta') );
      
      add_filter( 'manage_motivation_posts_columns', array($this,'set_custom_edit_book_columns' ), 1);
      add_action( 'manage_motivation_posts_custom_column' , array($this,'custom_book_column'), 99, 2 );
    }

    /**
     * Custom columns
     */
    public function set_custom_edit_book_columns($columns) {
      $columns['type'] = __( 'Notice Type', 'woo_motivation' );

      return $columns;
    }

    public function custom_book_column( $column, $post_id ) {
      switch ( $column ) {

        case 'type' :
        $data = get_post_meta( $post_id, 'woo_motivation_data', true );
        echo '<span class="post-list-type '.$data['woo_motivation_notice_type'].'">';
        echo $data['woo_motivation_notice_type']; 
        echo '</span>';
        break;

      }
    }

    /**
     * Motivation post type
     */
    private function init() {
      $post_type = 'motivation';
      $args = array(
       'label'  => 'Motivations',
       'labels' => array(
        'name' => __( 'Motivations' , 'woo_motivation' ),
        'singular_name' => __( 'Motivation' , 'woo_motivation' ),
      ),
      	'public' => false,  // it's not public, it shouldn't have it's own permalink, and so on
      	'publicly_queryable' => true,  // you should be able to query it
      	'show_ui' => true,  // you should be able to edit it in wp-admin
      	'exclude_from_search' => true,  // you should exclude it from search results
      	'show_in_nav_menus' => false,  // you shouldn't be able to add it to menus
      	'has_archive' => false,  // it shouldn't have archive page
      	'rewrite' => false,  // it shouldn't have rewrite rules
      	'menu_icon' => 'dashicons-screenoptions',
      );
      register_post_type( $post_type, $args );
    }
    
    /**
     * Box content
     */
    public function woo_motivation_box_content( $post ){
    	add_meta_box( 'woo_motivation_box', __( 'Settings', 'woo_motivation' ), array( $this, 'woo_motivation_box_callback'), 'motivation');
    }

    /**
     * Check option
     */
    /*public function woo_motivation_set_default( $key, $data_array ){
      if (! array_key_exists($key,$data_array) ){
        return $data_array[$key] = '';
      }
    }*/

    /**
     * Input (not realized)
     */
    /*public function woo_motivation_input($title, $type, $atts='', $classes, $data, $name){
      $output='<tr>';
      $output.='<th>'; 
      $output.=$title;
      $output.='    
      </th>
      <td>
      <label>
      <input type="'.$type.'" '.$atts.'>
      </label>
      </td>
      </tr>';
      return $output;
    }*/

    /**
     * Box callback
     */
    public function woo_motivation_box_callback($post)
    {
      // Default values
      $data = get_post_meta( $post->ID, 'woo_motivation_data', true );
      // $this->woo_motivation_set_default( 'woo_motivation_cta_enable' ,$data);
      if ($data == ""){
        $data=array();
      }
      if (! array_key_exists('woo_motivation_notice_type',$data) ){
        $data['woo_motivation_notice_type'] = '';
      }
      if (! array_key_exists('woo_motivation_cta_enable',$data) ){
        $data['woo_motivation_cta_enable'] = '';
      }
      if (! array_key_exists('woo_motivation_bonus_type',$data) ){
        $data['woo_motivation_bonus_type'] = 'none';
      }
      if (! array_key_exists('woo_motivation_discount_bonus',$data) ){
        $data['woo_motivation_discount_bonus'] = '';
      }
      if (! array_key_exists('woo_motivation_cart_update_discount',$data) ){
        $data['woo_motivation_cart_update_discount'] = '';
      }
      if (! array_key_exists('woo_motivation_product_for_gift',$data) ){
        $data['woo_motivation_product_for_gift'] = '';
      }
      if (! array_key_exists('woo_motivation_cart_update_gift',$data) ){
        $data['woo_motivation_cart_update_gift'] = '';
      }
      if (! array_key_exists('woo_motivation_cta_text',$data) ){
        $data['woo_motivation_cta_text'] = '';
      }
      if (! array_key_exists('woo_motivation_cta_link',$data) ){
        $data['woo_motivation_cta_link'] = '';
      }
      if (! array_key_exists('woo_motivation_max_triggered_price',$data) ){
        $data['woo_motivation_max_triggered_price'] = '';
      }
      if (! array_key_exists('woo_motivation_min_triggered_price',$data) ){
        $data['woo_motivation_min_triggered_price'] = '';
      }
      if (! array_key_exists('woo_motivation_min_triggered_price_excluded',$data) ){
        $data['woo_motivation_min_triggered_price_excluded'] = '';
      }
      if (! array_key_exists('woo_motivation_max_triggered_price_excluded',$data) ){
        $data['woo_motivation_max_triggered_price_excluded'] = '';
      }      
      ?>
      <div class="woo_motivation_container">
       <ul class="woo_motivation_nav">
        <li>
         <a class="active" href="#woo_motivation_main">
          <i class="dashicons dashicons-admin-generic" aria-hidden="true"></i>
          <span class="label">
           Main
         </span>
       </a>
     </li>
     <li>
       <a href="#woo_motivation_cta">
        <i class="dashicons dashicons-admin-links" aria-hidden="true"></i>
        <span class="label">
         CTA
       </span>
     </a>
   </li>
   <li>
    <a href="#woo_motivation_triggered_price">
      <i class="dashicons dashicons-cart" aria-hidden="true"></i>
      <span class="label">
        Triggered Price
      </span>
    </a>
  </li>
</ul>
<div class="woo_motivation_content">
  <div id="woo_motivation_main" class="woo_motivation_section active">
   <table class="form-table">
    <tr>
      <th>
        Notice type
      </th>
      <td>
        <?php
        $woo_motivation_notice_types = array(
          'notice'  => 'Notice',
          'success' => 'Success',
          'error'   => 'Error', 
        );
          //print_r($notice_type);
          //echo $data;
        if ($data == ""){
          $data['woo_motivation_notice_type'] = 'notice';
        }
        foreach ( $woo_motivation_notice_types as $key => $woo_motivation_notice_type ){
          if ( $data['woo_motivation_notice_type'] == $key){
            $checked = "checked";
          } else $checked = "";
          if ( $key == 'notice' && $data['woo_motivation_notice_type'] == '' ){
            $checked = "checked";
          }
          ?>
          <label class="input-label">
            <input name="woo_motivation_notice_type" type="radio" value="<?php echo $key; ?>" <?php echo $checked; ?> >
            <?php echo $woo_motivation_notice_type; ?>
          </label>
          <?php
        }
        ?>
      </td>
      </tr>
      <tr>
        <th>

        </th>
        <td>
          <label>
            <?php //print_r($data); ?>
            <b>Notice banner.</b> Give to your customers information about available discounts and gifts.
          </label>
        </td>
      </tr>
      <tr>
        <th>

        </th>
        <td>
          <label>
            <b>Success banner.</b> Show success message to your customers.
          </label>
        </td>
      </tr>
      <tr>
        <th>

        </th>
        <td>
          <label>
            <b>Error banner.</b> Prohibition message. <b>Warning!</b> This will prevent users from making purchases if the conditions are met.
          </label>
        </td>
      </tr>
      <tr>
       <th>
        Bonus for trigger price
      </th>
      <td>
        <?php 
        $woo_motivation_bonus_types= array(
          'none'          => 'None',
          'discount'      => 'Discount',
          'gift'          => 'Gift', 
        );
        foreach ( $woo_motivation_bonus_types as $key => $woo_motivation_bonus_type ){
          if ( $data['woo_motivation_bonus_type'] == $key){
            $checked = "checked";
          } else $checked = "";
          ?>
          <label class="input-label">
            <input name="woo_motivation_bonus_type" type="radio" value="<?php echo $key; ?>" <?php echo $checked; ?> >
            <?php echo __( $woo_motivation_bonus_type, 'woo_motivation' ); ?>
          </label>
          <?php 
        }
        ?>
      </td>
    </tr>
    <tr class="woo-motivation-tab-section main-discount-section">
      <th>
        Discount bonus 
      </th>
      <td>
        <label>
          <input type="number" name="woo_motivation_discount_bonus" value="<?php echo $data['woo_motivation_discount_bonus']; ?>">
        </label>
      </td>
    </tr>
    <tr class="woo-motivation-tab-section main-discount-section">
      <th>
        Cart update  
      </th>
      <td>
        <label>
          <input type="checkbox" value="checked" name="woo_motivation_cart_update_discount" <?php
          if ( ($data['woo_motivation_cart_update_discount'] == 'checked') ){
           echo 'checked'; 
         }
         ?>
         >
         <p class="description" id="timezone-description">Recommended for Success type. Add a discount to your cart totals. (If you use other plugin for discount and want to use this banner for info only, then leave this checkbox empty).</p>
       </label>
     </td>
   </tr>
   <tr class="woo-motivation-tab-section main-discount-section">
    <th>
      Additional dynamic data 
    </th>
    <td>
      <label>
        <b>{{discount}}</b> - Discount<br>
<!--           <b>{{price-additional}}</b> - Left money for bonus 
-->        </label>
</td>
</tr>
<tr class="woo-motivation-tab-section main-gift-section">
 <th>
  Product for gift 
</th>
<td>
  <?php 
  $products = wc_get_products( 
    array( 
      'limit' => -1,
    ) 
  );
  $products_edited=[];
  ?>
  <label>
    <select name="woo_motivation_product_for_gift" class="">
      <?php
      $product_for_gift = $data['woo_motivation_product_for_gift'];
      foreach ( $products as $product ){
        $id = $product->get_id();
        $title = $product->get_title();
        $products_edited[$id]=$title;
        ?>
        <option value="<?php echo $id; ?>" <?php if ($id == $product_for_gift) { echo ' selected="selected"'; };?>><?php echo $title; ?></option>
        <?php
      };
      ?>
    </select>
  </label>
</td>
</tr>
<tr class="woo-motivation-tab-section main-gift-section">
  <th>
    Cart update  
  </th>
  <td>
    <label>
      <input type="checkbox" value="checked" name="woo_motivation_cart_update_gift" <?php
      if ( ($data['woo_motivation_cart_update_gift'] == 'checked') ){
       echo 'checked'; 
     }
     ?>
     >
     <p class="description" id="timezone-description">Recommended for Success type. Add a free gift info to the checkout page and order. (If you use other plugin for gift and want to use this banner for info only, then leave this checkbox empty). </p>
   </label>
 </td>
</tr>
</table>
</div>	
<div id="woo_motivation_cta" class="woo_motivation_section">
  <table class="form-table">
    <tr>
      <th>
        Enable 
      </th>
      <td>
        <label>
          <input type="checkbox" value="checked" name="woo_motivation_cta_enable" <?php
          if ( ($data['woo_motivation_cta_enable'] == 'checked') ){
           echo 'checked'; 
         }
         ?>
         >
       </label> 
     </td>
   </tr>
   <tr>
    <th>
      Call to action text 
    </th>
    <td>
      <label>
        <input type="text" name="woo_motivation_cta_text" value="<?php echo $data['woo_motivation_cta_text']; ?>">
      </label> 
    </td>
  </tr>
  <tr>
    <th>
      Call to action link 
    </th>
    <td>
      <label>
        <input type="text" name="woo_motivation_cta_link" value="<?php echo $data['woo_motivation_cta_link']; ?>">
      </label> 
    </td>
  </tr>
</table>
</div>	
<div id="woo_motivation_triggered_price" class="woo_motivation_section">
  <table class="form-table">
    <tr>
      <th>
        Minimum triggered price
      </th>
      <td>
        <label>
          <input type="number" name="woo_motivation_min_triggered_price" value="<?php echo $data['woo_motivation_min_triggered_price']; ?>">
        </label>
      </td>
    </tr>
    <tr class="sectioned">
      <th>
        Excluded?
      </th>
      <td>
        <label>
          <input type="checkbox" value="checked" name="woo_motivation_min_triggered_price_excluded" <?php
          if ( ($data['woo_motivation_min_triggered_price_excluded'] == 'checked') ){
           echo 'checked'; 
         }
         ?>>Exclude the exact value from the condition.
        </label>
      </td>
    </tr>
    <tr>
      <th>
        Maximum triggered price
      </th>
      <td>
        <label>
          <input type="number" name="woo_motivation_max_triggered_price" value="<?php echo $data['woo_motivation_max_triggered_price']; ?>">
        </label>
      </td>
    </tr>
    <tr class="sectioned">
      <th>
        Excluded?
      </th>
      <td>
        <label>
          <input type="checkbox" value="checked" name="woo_motivation_max_triggered_price_excluded" <?php
          if ( ($data['woo_motivation_max_triggered_price_excluded'] == 'checked') ){
           echo 'checked'; 
         }
         ?>>Exclude the exact value from the condition.
        </label>
      </td>
    </tr>
    <tr>
      <th>
        Dynamic data 
      </th>
      <td>
        <label>
          <b>{{min-triggered-price}}</b> - Minimum triggered price<br>
          <b>{{max-triggered-price}}</b> - Maximum triggered price<br>
          <b>{{min-triggered-price-left}}</b> - Left for Minimum triggered price<br>
          <b>{{max-triggered-price-left}}</b> - Left for Maximum triggered price<br>
        </label>
      </td>
    </tr>
  </table>
</div>
</div>
</div>

<?php
wp_nonce_field( 'woo_motivation_metabox_nonce', 'woo_motivation_nonce' ); 
}
    /* 
     * Save meta
     */
    function woo_motivation_save_meta( $post_id ) {
      if( !isset( $_POST['woo_motivation_nonce'] ) || !wp_verify_nonce( $_POST['woo_motivation_nonce'],'woo_motivation_metabox_nonce') ) 
        return;

      if ( !current_user_can( 'edit_post', $post_id ))
        return;

      if ( isset($_POST['woo_motivation_notice_type']) ) {  
        $data_new['woo_motivation_notice_type'] = sanitize_text_field($_POST['woo_motivation_notice_type']);               
      }  
      if ( isset($_POST['woo_motivation_cta_enable']) ) {  
        $data_new['woo_motivation_cta_enable'] = sanitize_text_field($_POST['woo_motivation_cta_enable']);               
      }  
      if ( isset($_POST['woo_motivation_bonus_type']) ) {  
        $data_new['woo_motivation_bonus_type'] = sanitize_text_field($_POST['woo_motivation_bonus_type']);               
      } 
      if ( isset($_POST['woo_motivation_discount_bonus']) ) {  
        $data_new['woo_motivation_discount_bonus'] = sanitize_text_field($_POST['woo_motivation_discount_bonus']);              
      }
      if ( isset($_POST['woo_motivation_cart_update_discount']) ) {  
        $data_new['woo_motivation_cart_update_discount'] = sanitize_text_field($_POST['woo_motivation_cart_update_discount']);              
      }
      if ( isset($_POST['woo_motivation_cart_update_gift']) ) {  
        $data_new['woo_motivation_cart_update_gift'] = sanitize_text_field($_POST['woo_motivation_cart_update_gift']);              
      }
      if ( isset($_POST['woo_motivation_product_for_gift']) ) {  
        $data_new['woo_motivation_product_for_gift'] = sanitize_text_field($_POST['woo_motivation_product_for_gift']);              
      }
      if ( isset($_POST['woo_motivation_cta_text']) ) {  
        $data_new['woo_motivation_cta_text'] = sanitize_text_field($_POST['woo_motivation_cta_text']);              
      }
      if ( isset($_POST['woo_motivation_cta_link']) ) {  
        $data_new['woo_motivation_cta_link'] = sanitize_text_field($_POST['woo_motivation_cta_link']);              
      }
      if ( isset($_POST['woo_motivation_min_triggered_price']) ) {  
        $data_new['woo_motivation_min_triggered_price'] = sanitize_text_field($_POST['woo_motivation_min_triggered_price']);              
      }
      if ( isset($_POST['woo_motivation_max_triggered_price']) ) {  
        $data_new['woo_motivation_max_triggered_price'] = sanitize_text_field($_POST['woo_motivation_max_triggered_price']);              
      }
      if ( isset($_POST['woo_motivation_min_triggered_price_excluded']) ) {  
        $data_new['woo_motivation_min_triggered_price_excluded'] = sanitize_text_field($_POST['woo_motivation_min_triggered_price_excluded']);              
      }  
      if ( isset($_POST['woo_motivation_max_triggered_price_excluded']) ) {  
        $data_new['woo_motivation_max_triggered_price_excluded'] = sanitize_text_field($_POST['woo_motivation_max_triggered_price_excluded']);              
      }
      update_post_meta($post_id, 'woo_motivation_data',  $data_new);  
    }

  }
endif;

if ( class_exists( 'Woo_Motivation_Post_Type', false ) ) {
  return new Woo_Motivation_Post_Type();
}