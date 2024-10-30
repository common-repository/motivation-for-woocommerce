<?php
/**
 * Cart Page Gift Banner
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php 

/**
 * Motivation Advanced Notices - main class
 */
if ( ! class_exists( 'Woo_Motivation_Advanced_Notices' ) ) :

	class Woo_Motivation_Advanced_Notices {

		/**
		 * Construct
	  	 */
		public function __construct() {
			// disable hook after completed order
			add_action( 'woocommerce_add_to_cart_fragments',  array( $this, 'woo_motivation_check_page' ), 10, 1); 
			if ( ! is_admin() ) {
				add_action( 'wp_head', array( $this, 'woo_motivation_check_page' ), 10, 1 );	
			}
			add_action( 'woocommerce_check_cart_items', array( $this, 'woo_motivation_check_errors' ), 10, 1 );
			//gift
			add_action('woocommerce_checkout_order_processed',array($this,'woo_motivation_add_gift'),10,1);
			add_action( 'woocommerce_review_order_after_cart_contents', array( $this, 'woo_motivation_add_gift_to_checkout' ),10,1 );	

			/*add_action( 'woocommerce_before_cart', array( $this, 'woo_motivation_output' ),10,1 );*/
			//discount
			add_action( 'woocommerce_cart_calculate_fees', array( $this, 'woo_motivation_add_discount' ), 10,1 );	
		}

	  	/**
		 * Data Output
	  	 */
	  	/*public function woo_motivation_output(){
	  		$woo_motivation_get_data_output = array();
	  		$woo_motivation_get_data_output = $this->woo_motivation_get_data();
	  		echo '<pre>';
	  		print_r($woo_motivation_get_data_output);
	  		echo '</pre>';
	  	}*/

		/**
		 * Add discount to cart and order review
	  	 */
		public function woo_motivation_add_discount ($cart){ 		
			if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
				return;
			}
			$posts_data_all = $this->woo_motivation_get_data();
			foreach ($posts_data_all as $key => $post_data) {
				if ( $post_data['woo_motivation_triggered'] ){
					if ( ($post_data['woo_motivation_discount_bonus'] != '') && ( $post_data['woo_motivation_cart_update_discount'] == 'checked' ) ) {

						$discount_name    = 'Additional Discount';
						$cart_discount    = $post_data['woo_motivation_discount_bonus'] * ( -1 );
						$cart->add_fee( $discount_name, $cart_discount, true );
					}
				}
			}
		}

		/**
		 * Check errors for block checkout
	  	 */
		public function woo_motivation_check_errors(){
			global $has_errors;
			if ( $has_errors ){
				wc_add_notice('','error');
			}		
		}

	  	/**
		 * Check page - show only on cart and checkout
	  	 */
	  	public function woo_motivation_check_page(){
	  		$apply_notice = false;
	  		if ( ! WC()->cart->is_empty() ) {
	  			if (is_checkout()) {
	  				$apply_notice = true;
	  			} elseif ( is_cart() ) {
	  				$apply_notice = true;
	  			}
	  		}
	  		if( $apply_notice ) {
	  			$this->woo_motivation_get_notices();
	  		}
	  	}

	  	/**
		 * Get notices
	  	 */
	  	public function woo_motivation_get_notices() {
	  		global $has_errors;
	  		global $woocommerce;
	  		$cur_total = WC()->cart->get_subtotal(false);
	  		$posts_data = $this->woo_motivation_get_data();
	  		foreach ($posts_data as $key => $data) {
	  			$message_motivation = $data['woo_motivation_message_motivation'];
	  			$min_triggered_price = $data['woo_motivation_min_triggered_price'];
	  			$max_triggered_price = $data['woo_motivation_max_triggered_price'];
	  			$satisfaction_content = '';

	  			//replace dynamic content
	  			$patterns = array();
	  			$replacements = array();
	  			if ($min_triggered_price != ''){
	  				$patterns['min-triggered-price'] = '{{{min-triggered-price}}}';
	  				$patterns['min-triggered-price-left'] = '{{{min-triggered-price-left}}}';
	  				$replacements['min-triggered-price'] = wc_price($min_triggered_price);
	  				$min_triggered_price_left = $cur_total - $min_triggered_price;
					$replacements['min-triggered-price-left'] = wc_price($min_triggered_price_left);	  				
	  			}
	  			if ($max_triggered_price != ''){
	  				$patterns['max-triggered-price'] = '{{{max-triggered-price}}}';
	  				$patterns['max-triggered-price-left'] = '{{{max-triggered-price-left}}}';
	  				$replacements['max-triggered-price'] = wc_price($max_triggered_price);
	  				$max_triggered_price_left = $max_triggered_price - $cur_total;
	  				$replacements['max-triggered-price-left'] = wc_price($max_triggered_price_left);
	  			}	  							
	  			if ( $data['woo_motivation_triggered'] ) {

	  				/* if exist something except content */
	  				if ( ($data['woo_motivation_bonus_type'] == 'discount') || ($data['woo_motivation_bonus_type'] == 'gift') || ($data['woo_motivation_cta_enable'] == 'checked') ) {

	  					/* vars */
	  					$bonus_discount = $data['woo_motivation_discount_bonus'];
	  					$bonus_gift_id = $data['woo_motivation_product_for_gift'];
	  					$cart_update_gift = $data['woo_motivation_cart_update_gift'];
	  					$cta_text = $data['woo_motivation_cta_text'];
	  					$cta_link = $data['woo_motivation_cta_link'];
	  					$columns = 0;
	  					$satisfaction_content = '';
	  					$satisfaction_classes = '';

	  					/* Swap dynamic content */
	  					if ($bonus_discount != ''){
	  						$patterns['discount'] = '{{{discount}}}';	  					
	  						$replacements['discount'] = wc_price($bonus_discount);
	  					}
	  					//discount
	  					if  ( $data['woo_motivation_bonus_type'] == 'discount' ) {
	  						$satisfaction_content .= '<div class="woo-motivation-discount-item">'.wc_price($bonus_discount).'</div>';
	  						$columns++;
	  					}
	  					//gift
	  					if  ( $data['woo_motivation_bonus_type'] == 'gift' && $data['woo_motivation_product_for_gift'] != '-' ) {
	  						$_product_id = $bonus_gift_id;
	  						$_product = wc_get_product( $_product_id );
	  						$product_permalink = $_product->is_visible() ? $_product->get_permalink( $_product_id ) : '';
	  						$thumbnail = $_product->get_image( array(100,100) );
	  						$gift_content = sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( $product_permalink ), $thumbnail ); 
	  						$satisfaction_content .= '<div class="woo-motivation-gift-item">'.$gift_content.'</div>';
	  						$columns++;
	  						/*array_push($woo_motivation_gift_ids,$_product_id);*/
	  					}
	  					//cta
	  					if ( $data['woo_motivation_cta_enable'] == 'checked' ) {
	  						$satisfaction_content .= '<div class="woo-motivation-cta"><a href="'.esc_url( $cta_link ).'">'.esc_html( $cta_text ).'</a></div>';
	  						$columns++;
	  					}
	  					if ($columns < 2) {
	  						$satisfaction_classes=' single';
	  					}

	  					$satisfaction_container_open = '<div class="woo-motivation-satisfaction'.$satisfaction_classes.'">';
	  					$satisfaction_container_close = '</div>';
	  					$satisfaction_content = $satisfaction_container_open.$satisfaction_content.$satisfaction_container_close;
	  				}	
	  				if ( !empty($patterns) ){
	  					$replaced_content = preg_replace( $patterns, $replacements, $message_motivation );
	  				} else $replaced_content=$message_motivation;
	  				$notice_content = '<span>'.$replaced_content.'</span>';
	  				$notice_content.= $satisfaction_content;
	  				wc_add_notice( $notice_content, esc_html( $data['woo_motivation_notice_type']) );
	  				if ($data['woo_motivation_notice_type'] == 'error') {
	  					$has_errors = true;
	  				}
	  			}
	  		}
	  		wp_reset_postdata();
	  	}

		/**
		 * Get motivations post data
		 */
		public function woo_motivation_get_data() {
			global $woocommerce;
			$woo_motivation_gift_ids = array();
			$woo_motivation_data = array();
			$cur_total = WC()->cart->get_subtotal(false);
			/* Loop */
			$loop = new WP_Query( array( 'post_type' => 'motivation') );
			if ( $loop->have_posts() ) :
				while ( $loop->have_posts() ) : $loop->the_post(); 
					$message_motivation = get_the_content();
					$data = get_post_meta( get_the_ID(), 'woo_motivation_data', true );	
					if ($data == ""){
						$data=array();
					}
					$data['woo_motivation_message_motivation'] = $message_motivation; 
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

					$min_triggered_price = $data['woo_motivation_min_triggered_price'];
					$max_triggered_price = $data['woo_motivation_max_triggered_price'];

					// triggered?
					if (( $cur_total > $min_triggered_price || $min_triggered_price == '' ) && ( $cur_total < $max_triggered_price || $max_triggered_price == '' ) || ( ($data['woo_motivation_min_triggered_price_excluded'] == false) && $data['woo_motivation_min_triggered_price'] == $cur_total ) || ( ($data['woo_motivation_max_triggered_price_excluded'] == false) && $data['woo_motivation_max_triggered_price'] == $cur_total )) {
						$data['woo_motivation_triggered'] = true;
					}	
					else $data['woo_motivation_triggered'] = false;

					// additional data if triggered
					if ( $data['woo_motivation_triggered'] ) {

						/* if exist something except content */
						if ( ($data['woo_motivation_bonus_type'] == 'discount') || ($data['woo_motivation_bonus_type'] == 'gift') || ($data['woo_motivation_cta_enable'] == 'checked')) {

							/* vars */
							$bonus_discount = $data['woo_motivation_discount_bonus'];
							$bonus_gift_id = $data['woo_motivation_product_for_gift'];
							$cart_update_gift = $data['woo_motivation_cart_update_gift'];
							$cta_text = $data['woo_motivation_cta_text'];
							$cta_link = $data['woo_motivation_cta_link'];
							$columns = 0;
							$satisfaction_content = '';
							$satisfaction_classes = '';					
							if  ( $data['woo_motivation_bonus_type'] == 'gift' ) {
								$_product_id = $bonus_gift_id;
								array_push($woo_motivation_gift_ids,$_product_id);
							}
						}
					}
					array_push($woo_motivation_data,$data);
				endwhile;
			endif;
			wp_reset_postdata();
			return $woo_motivation_data;
		}

		/**
		 * Add gift to the order 
		 */
		public function woo_motivation_add_gift( $order_id ) {
			$posts_data_all = $this->woo_motivation_get_data();
			foreach ($posts_data_all as $key => $post_data) {
				if ( $post_data['woo_motivation_triggered'] ) {
					if ( ($post_data['woo_motivation_product_for_gift'] != '') && ( $post_data['woo_motivation_cart_update_gift'] == 'checked' ) ) {				
						$_product_id = $post_data['woo_motivation_product_for_gift'];
						$product = wc_get_product($_product_id);
						$product->set_price( 0 );
						$quantity = 1;
						wc_get_order($order_id)->add_product( $product, $quantity );
					}
				}
			}	
		}

		/**
		 * Add gift to the checkout page
		 */
		public function woo_motivation_add_gift_to_checkout(){
			$posts_data_all = $this->woo_motivation_get_data();
			foreach ($posts_data_all as $key => $post_data) {
				if ( $post_data['woo_motivation_triggered'] ) {
					if ( ($post_data['woo_motivation_product_for_gift'] != '') && ( $post_data['woo_motivation_cart_update_gift'] == 'checked' ) ) {					
						$_product_id = $post_data['woo_motivation_product_for_gift'];
						$_product = wc_get_product( $_product_id );
						?>
						<tr class="cart_item">
							<td class="product-name">
								<?php echo esc_html( $_product->get_name() ); ?>&nbsp;							 
								<strong class="product-quantity">× 1 (Gift)</strong>
							</td>
							<td class="product-total">
								<span class="woocommerce-Price-amount amount">
									<span class="woocommerce-Price-currencySymbol">
									$</span>0.00
								</span>						
							</td>
						</tr>
						<?php
					}
				}
			}	
		}
	}
endif;

if ( class_exists( 'Woo_Motivation_Advanced_Notices', false ) ) {
	return new Woo_Motivation_Advanced_Notices();
}