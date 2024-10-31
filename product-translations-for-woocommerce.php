<?php
/*
Plugin Name: Product Translations for WooCommerce
Description: Product Translations for WooCommerce
Author: Loco
Author URI: https://loco-app.expan.do/
Version: 1.0.2
Requires Plugins: woocommerce, api2cart-bridge-connector
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

/*
Workaround for Api2Cart over Loco connection

This Bridge Connector is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Loco Api2Cart Bridge Connector. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define('PTFW_API_URL', 'https://loco-app.expan.do/woocommerce/');
define('PTFW_BRIDGE_INSTALED', 'ptfw_bridge_instaled');
define('PTFW_BRIDGE_SHOP_ID', 'ptfw_bridge_shop_id');
define('PTFW_BRIDGE_SHOP_HASH', 'ptfw_bridge_shop_hash');
define('PTFW_BRIDGE_TITLE', 'Product Translations for WooCommerce');

$ptfw_errors = [];

function ptfw_connector_config()
{
    global $ptfw_errors;
    if (isset($_POST['action'])) {
        if (!isset($_POST['my_form_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['my_form_nonce'])), 'my_form_action')) {
            die(esc_html__('Security check', 'product-translations-for-woocommerce'));
        }

        if ($_POST['action'] == 'connect') {
            ptfw_connect();
        }
        if ($_POST['action'] == 'uninstall') {
            ptfw_disconnect();
        }
    }

    $pluginTitle = esc_html__('Product Translations for WooCommerce', 'product-translations-for-woocommerce');
    $activated = (bool)get_option('A2C_woocommerce_bridge_connector_is_custom');
    $email = isset($_GET['email']) ? sanitize_email(wp_unslash($_GET['email'])) : (get_option('ptfw_bridge_email') ? : get_option('admin_email'));
    $phone = !empty($_GET['phone']) ? sanitize_text_field(wp_unslash($_GET['phone'])) : get_option('ptfw_bridge_phone');
    $webName = get_bloginfo('name');
    $webUrl = site_url();

    $ptfwConfigUrl = PTFW_API_URL . 'installShop/' . get_option(PTFW_BRIDGE_SHOP_ID) . '/' . get_option(PTFW_BRIDGE_SHOP_HASH);
    $current_url = home_url(add_query_arg(null, null));
    $redirectUrl = $ptfwConfigUrl . '?back_url=' . urlencode($current_url);

    if ($activated) {
        if (!empty($_GET['message'])) {
            $ptfw_errors[] = sanitize_text_field(wp_unslash($_GET['message']));
        } else {
            wp_redirect($redirectUrl);

            return true;
        }
    }

    include 'html/settings.phtml';

    return true;
}

function ptfw_connect()
{
    if (!isset($_POST['my_form_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['my_form_nonce'])), 'my_form_action')) {
        die(esc_html__('Security check', 'product-translations-for-woocommerce'));
    }
    global $ptfw_errors;
    $email = !empty($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : null;
    if (!$email) {
        $ptfw_errors[] = esc_html__('Email is required', 'product-translations-for-woocommerce');

        return false;
    }
    if (empty($_POST['agree'])) {
        $ptfw_errors[] = esc_html__('Please, check I agree with the Terms and conditions', 'product-translations-for-woocommerce');

        return false;
    }

    global $worker;
    if (!$worker) {
        $ptfw_errors[] = esc_html__('Api2cart Bridge Connector plugin required', 'product-translations-for-woocommerce');

        return false;
    }
    include_once $worker->bridgePath . $worker->configFilePath;

    $data = [
        'bridgeUrl' => $worker->getBridgeUrl(),
        'storeKey' => $worker->getStoreKey(),
        'webUrl' => site_url(),
        'email' => $email,
        'name' => get_bloginfo('name'),
        'phone' => !empty($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '',
        'language_locale' => get_user_locale(),
    ];

    update_option('ptfw_bridge_email', $email);
    update_option('ptfw_bridge_phone', !empty($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '');

    $response = wp_remote_post(PTFW_API_URL . 'install', [
        'body' => $data,
        'sslverify' => false,
        'timeout' => 60,
    ]);
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);

    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        /* translators: 1: Error message */
        $ptfw_errors[] = sprintf(esc_html__('Install went wrong: ', 'product-translations-for-woocommerce'), $error_message);
    }

    if (!$data || !isset($data->shop_id)) {
        /* translators: 1: Error message or empty string */
        $ptfw_errors[] = sprintf(esc_html__('Install went wrong - no data: %s', 'product-translations-for-woocommerce'), (isset($data->message) ? $data->message : ''));
    } else {
        update_option('A2C_woocommerce_bridge_connector_is_installed', true);
        $status = $worker->installBridge();
        if ($status['success'] && isset($data->shop_id)) {
            update_option('A2C_woocommerce_bridge_connector_is_custom', isset($status['custom']) ? $status['custom'] : false);
            update_option(PTFW_BRIDGE_SHOP_ID, $data->shop_id);
            update_option(PTFW_BRIDGE_SHOP_HASH, $data->shop_hash);
        } else {
            update_option('A2C_woocommerce_bridge_connector_is_installed', false);
            /* translators: 1: Status message */
            $ptfw_errors[] = sprintf(esc_html__('Something went wrong - install bridge fail: %s', 'product-translations-for-woocommerce'), $status['message']);
        }
    }

    return true;
}

function ptfw_removeAllOptions()
{
    update_option('A2C_woocommerce_bridge_connector_is_installed', false);
    delete_option('A2C_woocommerce_bridge_connector_is_custom');
    delete_option(PTFW_BRIDGE_SHOP_ID);
    delete_option(PTFW_BRIDGE_SHOP_HASH);
}

function ptfw_disconnect()
{
    if (get_option(PTFW_BRIDGE_SHOP_ID)) {
        $data = [
            'shop_id' => get_option(PTFW_BRIDGE_SHOP_ID),
            'shop_hash' => get_option(PTFW_BRIDGE_SHOP_HASH),
        ];
        wp_remote_post(PTFW_API_URL . 'uninstall', [
            'body' => $data,
            'timeout' => 60,
            'sslverify' => false,
        ]);

        ptfw_removeAllOptions();
    }
}

function ptfw_activate()
{
    update_option(PTFW_BRIDGE_INSTALED, true);
}

function ptfw_deactivate()
{
    if (get_option('A2C_woocommerce_bridge_connector_is_installed')) {
        ptfw_disconnect();
    }
    // better to remove all options
    ptfw_removeAllOptions();

    update_option(PTFW_BRIDGE_INSTALED, false);
}


function ptfw_connector_load_menu()
{
    add_submenu_page('plugins.php',
        __('Product Translations for WooCommerce', 'product-translations-for-woocommerce'),
        __('Product Translations for WooCommerce', 'product-translations-for-woocommerce'),
        'manage_options',
        'ptfw-connector-config',
        'ptfw_connector_config');
}

add_action('admin_menu', 'ptfw_connector_load_menu');
register_activation_hook(__FILE__, 'ptfw_activate');
register_uninstall_hook(__FILE__, 'ptfw_uninstall');
register_deactivation_hook(__FILE__, 'ptfw_deactivate');

if (!class_exists('BridgeConnector')) {
    return;
}
$worker = new BridgeConnector();
