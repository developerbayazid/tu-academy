<div class="tu-academy-enquiry-form" id="tu-academy-enquiry-form">

    <h2 class="form-title"><?php _e('Enquiry Form', 'tu-academy'); ?></h2>
    <form action="" method="post">

        <div class="form-row">
            <label for="name"><?php _e('Name', 'tu-academy'); ?></label>
            <input type="text" name="name" id="name" required value="">
        </div>
        <div class="form-row">
            <label for="email"><?php _e('E-mail', 'tu-academy'); ?></label>
            <input type="email" name="email" id="email" required value="">
        </div>
        <div class="form-row">
            <label for="message"><?php _e('Message', 'tu-academy'); ?></label>
            <textarea name="message" id="message" required></textarea>
        </div>
        <div class="form-row">

            <?php wp_nonce_field('enquiry-form'); ?>
            <input type="hidden" name="action" value="tu_academy_enquiry">
            <input type="submit" name="send_enquiry" value="<?php esc_attr_e('Send Enquiry', 'tu-academy'); ?>">

        </div>

    </form>
</div>