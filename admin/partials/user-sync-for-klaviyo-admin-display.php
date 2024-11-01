<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://oakandbeech.com
 * @since      1.0.0
 *
 * @package    User_Sync_For_Klaviyo
 * @subpackage User_Sync_For_Klaviyo/admin/partials
 */
?>
<div class="wrap">
    <h2 class="wp-heading-inline">User Sync for Klaviyo Settings</h2>
    <div class="swk-settings-left">
        <form action='options.php' method='post'>
            <div class="swk-container">
                <h2>User Sync With Klaviyo Settings Page</h2>
                <p><?php settings_errors('user_sync_for_klaviyo_settings'); ?></p>
                <?php
                settings_fields('user_sync_for_klaviyo_settings_group');
                ?>
                    <table class="form-table">
                        <?php do_settings_fields('user_sync_for_klaviyo_settings_group', "user_sync_for_klaviyo_general_section"); ?>
                    </table>
            </div>
            <div class="swk-container">
                <div class="swk-toggle-header">
                    <h2><span class="dashicons dashicons-arrow-right"></span> What this plugin will sync to Klaviyo?</h2>
                </div>
                <div class="swk-toggle-container" style="display:none">
                    <h3>WordPress - Created User Event</h3>
                    <p>Each time a new user is created</p>
                    <h3>WordPress - Updated User Event</h3>
                    <p>Each time a user is updated in WordPress.</p>
                    <p>The following details will by synchronised as profile properties
                    <ul class="swk-inline-code">
                        <li>User ID: <pre>wordpress_user_id</pre></li>
                        <li>User Registered Date: <pre>wordpress_user_registered</pre></li>
                        <li>User Login: <pre>wordpress_user_login</pre></li>
                        <li>User Role: <pre>wordpress_user_role</pre></li>
                        <li>User First Name</li>
                        <li>User Last Name</li>
                    </ul>
                    </p>
                </div>
            </div>

            <div class="swk-container">
                <h2>Sync All Existing WordPress Users</h2>
                <p>By default, this plugin will only sync users who have been created/updated after the plugin was enabled. You can sync all of your existing users by clicking the button below</p>
                <p><strong>Note: This can take a bit of time, especially if you have a large number of users.</strong></p>
                <p>If you are having memory issues, try adjusting the batch size</p>
                <p><strong>Batch size:</strong><input type="number" name="swk-batch-size" min=10 max=1000 id="swk-batch-size" value="250"> profiles synced at a time</p>
                <?php $settings = get_option(USER_SYNC_FOR_KLAVIYO_SETTINGS);
                if(isset($settings['klaviyo_private_key']) && $settings['klaviyo_private_key'] != "" && $settings['activate_user_sync'] == 'on'):?>
                <?php $total_users = count_users()['total_users']; ?>
                <div id="swk-start-sync" class="button">Start Sync (<?php echo (int) $total_users; ?> Users)</div>
                <div id="swk-stop-sync" class="button red warning" style="display:none;">Stop Sync</div>
                <!-- Add HTML for progress bar -->
                <div id="swk-progress-bar-container" style="display:none;">
                    <div id="swk-progress-bar" style="background-color: #428bca; height: 100%; width: 0;"></div>
                    <span id="swk-loader" class="loader"></span><span id="swk-status-text"></span><span id="swk-progress-text">Warming up..</span>
                </div>
                <script>
                    var swk_settings = {
                        'total_users': <?php echo (int) $total_users; ?>,
                        'number': 250
                    }
                </script>
                <?php else:?>
                    <p>Please complete the settings section and enable user sync to perform a bulk sync</p>
                <?php endif;?>
            </div>
            <div class="swk-form-footer">
                <?php
                submit_button('Save Settings', 'primary', 'submit', false, ['style'=>'float:right;']);
                ?>
            </div>
        </form>
    </div>
    <div class="swk-settings-right">
        <div class="swk-container">
            <h3>Feedback &amp; Support</h3>
            <p>You are using version <code> <?php echo USER_SYNC_FOR_KLAVIYO_VERSION;?></code></p>
            <p>We are always looking to improve our plugins, so if you encounter any issues or want to give us feedback on what you would like next, please contact us!</p>
             Email us at <a href="mailto:feedback@oakandbeech.com?subject=User%20Sync%20For%20Klaviyo%20Plugin%20Feedback">feedback@oakandbeech.com</a></p>
             <p>Crafted with ❤️ by <a href="https://oakandbeech.com?utm_source=usk_plugin" target="_blank">OakandBeech</a></p>
        </div>
    </div>
</div>