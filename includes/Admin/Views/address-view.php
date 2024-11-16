<?php
    $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
    $contact = $id ? tu_ac_get_address($id) : null;

    $name = !empty($contact->name) ? $contact?->name : '';
    $address = !empty($contact->address) ? $contact?->address : '';
    $phone = !empty($contact->phone) ? $contact?->phone : '';
?>


<div class="wrap">

    <h1><?php _e($name, 'tu-academy'); ?></h1>

    <form>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="name"><?php _e('Name', 'tu-academy'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="name" value="<?php echo esc_attr( $name ); ?>" class="regular-text" <?php _e('disabled', 'tu-academy'); ?>>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="address"><?php _e('Address', 'tu-academy'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="address" value="<?php echo esc_attr( $address ); ?>" class="regular-text" <?php _e('disabled', 'tu-academy'); ?>>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="phone"><?php _e('Phone', 'tu-academy'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="phone" class="regular-text" value="<?php echo esc_attr( $phone ); ?>" <?php _e('disabled', 'tu-academy'); ?> >
                        
                    </td>
                </tr>
            </tbody>
        </table>
    </form>

</div>