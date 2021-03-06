<?php
class WC_Category_Locker_Admin
{
    /**
     * constructor, all hooks admin related.
     *
     * @author Lukas Juhas
     * @date   2016-02-04
     */
    public function __construct()
    {
        add_action('product_cat_add_form_fields', array( $this, 'add_category_fields' ), 25);
        add_action('product_cat_edit_form_fields', array( $this, 'edit_category_fields' ), 25);
        add_action('created_term', array( $this, 'save_category_fields' ), 10, 3);
        add_action('edit_term', array( $this, 'save_category_fields' ), 10, 3);
    }

    /**
     * extra fields while creating a new category.
     *
     * @author Lukas Juhas
     * @date   2016-02-04
     */
    public function add_category_fields()
    { ?>
    <div class="form-field">
        <label id="wcl_cat_password_protected">
            <input type="checkbox" name="wcl_cat_password_protected" value="1" />
            <?php _e('Password Protected', WCL_PLUGIN_DOMAIN); ?>
        </label>
        <div id="wcl_cat_password" style="display:none; float: left;">
          <label>
              <?php _e('Password:', WCL_PLUGIN_DOMAIN); ?>
              <input type="text" name="wcl_cat_password" value="" required="required" />
          </label>
        </div>
        <script>
            jQuery('#wcl_cat_password_protected').on('click', function() {
                var $checked = jQuery('input[name="wcl_cat_password_protected"]:checkbox:checked').length > 0;
                if($checked) {
                  jQuery('#wcl_cat_password').find('input').prop('disabled', false);
                  jQuery('#wcl_cat_password').slideDown();
                } else {
                  jQuery('#wcl_cat_password').find('input').prop('disabled', true);
                  jQuery('#wcl_cat_password').slideUp();
                }
            });
        </script>
        <div class="clear"></div>
    </div>
    <?php

    }

    /**
     * extra fields while editing category.
     *
     * @author Lukas Juhas
     * @date   2016-02-04
     *
     * @param [type] $term [description]
     *
     * @return [type] [description]
     */
    public function edit_category_fields($term)
    {
        $wcl_cat_password_protected = absint(get_woocommerce_term_meta($term->term_id, 'wcl_cat_password_protected', true));
        $wcl_cat_password = get_woocommerce_term_meta($term->term_id, 'wcl_cat_password', true);
        ?>
        <tr class="form-field">
            <th scope="row" valign="top">
                Password Protection
            </th>
            <td>
                <label id="wcl_cat_password_protected">
                    <input type="checkbox" name="wcl_cat_password_protected" value="1" <?php if ($wcl_cat_password_protected) { echo 'checked="checked"'; } ?> />
                    <?php _e('Password Protected', WCL_PLUGIN_DOMAIN); ?>
                </label>
                <div class="clear"></div>
                <div id="wcl_cat_password" style="<?php if (!$wcl_cat_password_protected) { echo 'display:none;'; } ?> float: left;">
                  <label>
                      <?php _e('Password:', WCL_PLUGIN_DOMAIN); ?>
                      <input type="text" name="wcl_cat_password" value="<?php echo $wcl_cat_password; ?>" <?php if (!$wcl_cat_password_protected) { echo 'disabled="disabled"'; } ?> required="required" />
                  </label>
                </div>
                <script>
                    jQuery('#wcl_cat_password_protected').on('click', function() {
                        var $checked = jQuery('input[name="wcl_cat_password_protected"]:checkbox:checked').length > 0;
                        if($checked) {
                          jQuery('#wcl_cat_password').find('input').prop('disabled', false);
                          jQuery('#wcl_cat_password').slideDown();
                        } else {
                          jQuery('#wcl_cat_password').find('input').prop('disabled', true);
                          jQuery('#wcl_cat_password').slideUp();
                        }
                    });
                </script>
                <div class="clear"></div>
            </td>
        </tr>
      <?php

    }

    /**
     * save fields when updating or creating category.
     *
     * @author Lukas Juhas
     * @date   2016-02-04
     *
     * @param [type] $term_id  [description]
     * @param string $tt_id    [description]
     * @param string $taxonomy [description]
     *
     * @return [type] [description]
     */
    public function save_category_fields($term_id, $tt_id = '', $taxonomy = '')
    {
        if (isset($_POST['wcl_cat_password_protected']) && 'product_cat' === $taxonomy) {
            update_woocommerce_term_meta($term_id, 'wcl_cat_password_protected', absint($_POST['wcl_cat_password_protected']));
        } elseif ('product_cat' === $taxonomy) {
            update_woocommerce_term_meta($term_id, 'wcl_cat_password_protected', 0);
        }

        if (isset($_POST['wcl_cat_password']) && 'product_cat' === $taxonomy) {
            update_woocommerce_term_meta($term_id, 'wcl_cat_password', esc_attr($_POST['wcl_cat_password']));
        }
    }
}

# init
$WC_Category_Locker_Admin = new WC_Category_Locker_Admin();
