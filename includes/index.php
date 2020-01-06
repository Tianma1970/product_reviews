<?php
//The Shortcode

		function pr_shortcode() {
			$pr_product = new WP_Query([ 
				'post_type'			=> 'pr_product',
				'posts_per_page'	=> -1,
				//The current post not to be shown
				'post__not_in'		=> array(get_the_ID())

				]);
				$output = "<h2>";
				$output .= __('Other Products', 'product_reviews');
				$output .= "</h2>";
				
				if($pr_product->have_posts()) {
					$output .= "<ul>";
					while($pr_product->have_posts()) {
						$pr_product->the_post(); 

						
						$output .= "<li>";
						$output .= get_the_title();
						$output .= "<br>";
						$output .= get_the_content();
						$output .= "<p>";
						$output .= "<br>";
						$output .= "<small>";
						$output .= __('Price: ', 'product_reviews');
						$output .= get_field('price');
						$output .= " &euro;";
						$output .= "</small>";
						$output .= "</p>";
						$output .= "</li>";
						/**
						 * det går inte att få taxonomien visat korrekt eftersom den alltid lägger sig längst upp på sidan
						 */
						$output .= "<div class='product-type'>";
						//$output .= "Product Type: ";
						$output .= the_terms(get_the_ID(),'pr_product_type');
						$output .= "</div>";
						$output .= "<hr>";
						
					}
					wp_reset_postdata();
					$output .= "</ul>";
				} else {
					$output .= __('No Products available', 'product_reviews');
				}

				$pr_review = new WP_Query([
                    'post_type'			=> 'pr_review',
                    /**
                     * How many pr_review we wish to output (all)
                     */
					'posts_per_page'	=> -1,
				]);
					/**
					 * Output the product reviews
					 */
					$output .= "<h3>";
					$output .= __('Product Reviews', 'product_reviews');
					$output .= "</h3>";
					/**
					 * Do we have Product Reviews?
					 */
					if($pr_review->have_posts()){
						$output .="<ul>";
						
						while($pr_review->have_posts()){ 
							$pr_review->the_post();

							$output .= "<div class='product-details'>";
							$output .= get_the_title();
							$output .= "<br>";
							$output .= "<p>Rating: ";
							$output .= get_field('rating');
							$output .= __(' of 5 points at all', 'product_reviews');
							$output .= "</p>";
							$output .= "</div>";
							
						}
							wp_reset_postdata();
							$output .= "</ul>";
					} else {
						$output .= __('No Reviews available', 'product_reviews');
					}
					return $output;
		}

	
	
		function pr_init() {
			add_shortcode('products', 'pr_shortcode');
	}
		add_action('init', 'pr_init');