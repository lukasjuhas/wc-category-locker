<?php
/**
 * get password form based on wordpress form.
 *
 * @author Lukas Juhas
 * @date   2016-02-04
 *
 * @param int $category [description]
 *
 * @return [type] [description]
 */
function wcl_get_the_password_form($category_id = false)
{
    if (!$category_id) {
        global $wp_query;
        $category_id = $wp_query->get_queried_object_id();
    }
    $label = 'wcl_pwbox-'.(!isset($category_id) ? rand() : $category_id);
    $output = '<form action="'.esc_url(site_url('wp-login.php?action=postpass', 'login_post')).'" class="post-password-form" method="post">
	<p>'.__('This content is password protected. To view it please enter your password below:').'</p>
	<p><label for="'.$label.'">'.__('Password:').'
  <div class="form__group form-group"><input name="wcl_cat_password" id="'.$label.'" type="password" size="20" /></div>
  </label>
  <input type="hidden" name="wcl_cat_id" value="'.$category_id.'" />
  <input type="submit" name="Submit" value="'.esc_attr__('Submit').'" /></p></form>
	';

    /*
     * Filter the HTML output for the protected post password form.
     *
     * If modifying the password field, please note that the core database schema
     * limits the password field to 20 characters regardless of the value of the
     * size attribute in the form input.
     *
     * @since 1.0
     *
     * @param string $output The password form HTML output.
     */
    return apply_filters('wcl_the_password_form', $output);
}

/**
 * ID list of categories that are locked
 * @author Lukas Juhas
 * @date   2016-02-08
 * @return [type]     [description]
 */
function wcl_get_locked_categories()
{
    $locked = array();
    $shop_terms = get_terms('product_cat');
    foreach ($shop_terms as $term) {
        $is_password_protected = get_woocommerce_term_meta($term->term_id, 'wcl_cat_password_protected');
        if ($is_password_protected) {
            $locked[] = $term->term_id;
        }
    }

    return $locked;
}
