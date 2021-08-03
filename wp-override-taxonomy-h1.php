<?php
/*
Plugin Name: WP Override Taxonomy H1
Plugin URI: https://github.com/workeffortwaste/wp-override-taxonomy-h1/
Description: Override the H1 for taxonomies.
Author: Chris Johnson (@defaced)
Version: 1.0
*/

function dfcd_custom_tax_title($title) {
  $term = get_queried_object();
  $custom_h1 = get_option( "taxonomy_$term->term_id")['custom_h1'];

  if ($custom_h1) { 
    $title = $custom_h1; 
  }
  return $title;
}

add_filter( 'get_the_archive_title', 'dfcd_custom_tax_title' );
add_filter( 'woocommerce_page_title', 'dfcd_custom_tax_title');
 
function dfcd_taxonomy_edit_meta_field($term) {
  $term_id = $term->term_id;
  $term_meta = get_option( "taxonomy_$term_id" ); 
  ?>
    <tr class="form-field">
    <th scope="row" valign="top">Custom H1</label></th>
        <td>
            <input type="text" name="term_meta[custom_h1]" id="term_meta[custom_h1]" value="<?php echo esc_attr( $term_meta['custom_h1'] ) ? esc_attr( $term_meta['custom_h1'] ) : ''; ?>">
            <p class="description">Override the H1 for this taxonomy.</p>
        </td>
    </tr>
  <?php
}

add_action( 'category_edit_form_fields', 'dfcd_taxonomy_edit_meta_field', 10, 2 );
add_action( 'post_tag_edit_form_fields', 'dfcd_taxonomy_edit_meta_field', 10, 2 );
add_action( 'product_cat_edit_form_fields', 'dfcd_taxonomy_edit_meta_field', 10, 2 );
add_action( 'product_tag_edit_form_fields', 'dfcd_taxonomy_edit_meta_field', 10, 2 );

 
function dfcd_save_taxonomy_custom_meta( $term_id ) {
  if ( isset( $_POST['term_meta'] ) ) {
    $term_meta = get_option( "taxonomy_$term_id" );
    $cat_keys = array_keys( $_POST['term_meta'] );
    foreach ( $cat_keys as $key ) {
        if ( isset ( $_POST['term_meta'][$key] ) ) {
            $term_meta[$key] = $_POST['term_meta'][$key];
        }
    }
    update_option( "taxonomy_$term_id", $term_meta );
  }
}

add_action( 'edited_category', 'dfcd_save_taxonomy_custom_meta', 10, 2 );
add_action( 'create_category', 'dfcd_save_taxonomy_custom_meta', 10, 2 );
add_action( 'edited_post_tag', 'dfcd_save_taxonomy_custom_meta', 10, 2 );
add_action( 'create_post_tag', 'dfcd_save_taxonomy_custom_meta', 10, 2 );
add_action( 'edited_product_cat', 'dfcd_save_taxonomy_custom_meta', 10, 2 );
add_action( 'create_product_cat', 'dfcd_save_taxonomy_custom_meta', 10, 2 );
add_action( 'edited_product_tag', 'dfcd_save_taxonomy_custom_meta', 10, 2 );
add_action( 'create_product_tag', 'dfcd_save_taxonomy_custom_meta', 10, 2 );

function dfcd_taxonomy_add_new_meta_field() {
  ?>
    <div class="form-field">
        <label for="term_meta[custom_h1]">Custom H1</label>
        <input type="text" name="term_meta[custom_h1]" id="term_meta[custom_h1]" value="">
        <p class="description">Override the H1 for this taxonomy.</p>
    </div>
  <?php
}

add_action( 'category_add_form_fields', 'dfcd_taxonomy_add_new_meta_field', 10, 2 );
add_action( 'post_tag_add_form_fields', 'dfcd_taxonomy_add_new_meta_field', 10, 2 );
add_action( 'product_tag_add_form_fields', 'dfcd_taxonomy_add_new_meta_field', 10, 2 );
add_action( 'product_cat_add_form_fields', 'dfcd_taxonomy_add_new_meta_field', 10, 2 );
