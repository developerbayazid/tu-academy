<div class="wrap">

    <h1><?php _e('Edit Address', 'tu-academy'); ?></h1>

    <?php if(isset($_GET['address-updated'])): ?>

        <div class="notice notice-success is-dismissible">
            <p><?php _e('Address has been updated successfully!', 'tu-academy'); ?></p>
        </div>

    <?php endif; ?>

    <form action="" method="post">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="name"><?php _e('Name', 'tu-academy'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="name" id="name" value="<?php echo esc_attr($address->name); ?>" class="regular-text">
                        <?php if($this->has_error('name')): ?>
                            <p class="description error"><?php echo $this->get_error('name'); ?></p>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="address"><?php _e('Address', 'tu-academy'); ?></label>
                    </th>
                    <td>
                        <textarea class="regular-text" name="address" id="address"><?php echo esc_textarea($address->address); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="phone"><?php _e('Phone', 'tu-academy'); ?></label>
                    </th>
                    <td>
                        <input type="tel" name="phone" id="phone" class="regular-text" value="<?php echo esc_attr($address->phone); ?>">
                        <?php if($this->has_error('phone')): ?>
                            <p class="description error"><?php echo $this->get_error('phone'); ?></p>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <input type="hidden" name="id" value="<?php echo esc_attr($address->id); ?>">
        <?php wp_nonce_field('new-address'); ?>
        <?php submit_button(__('Update Address', 'tu-academy'), 'primary', 'submit_address'); ?>
    </form>

</div>