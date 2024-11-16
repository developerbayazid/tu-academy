<div class="wrap">

    <h1 class="wp-heading-inline"><?php _e('Address Book', 'tu-academy'); ?></h1>
    <a href="<?php echo admin_url('admin.php?page=tu-academy&action=new'); ?>" class="page-title-action"><?php _e('Add New Address', 'tu-academy'); ?></a>

    <?php if(isset($_GET['inserted'])): ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Address has been added successfully!', 'tu-academy'); ?></p>
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['address-deleted']) && isset($_GET['address-deleted']) === true): ?>
        <div class="notice notice-success">
            <p><?php _e('Address has been deleted successfully!', 'tu-academy'); ?></p>
        </div>
    <?php endif; ?>

    <form action="" method="post">
        <?php 
            $table = new \Tu\Academy\Admin\Address_List();
            $table->prepare_items();
            $table->display();
        ?>
    </form>

</div>