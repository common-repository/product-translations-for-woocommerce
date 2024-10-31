=== Product Translations for WooCommerce ===
Contributors: locoapp
Tags: languages, translation, multilingual, i18n, localization, Product Translations for WooCommerce, woocommerce, api2cart
Requires at least: 6.6
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.2
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Requires Plugins: woocommerce, woocommerce-multilingual, api2cart-bridge-connector

A plugin for product translations management.

== Description ==
New products are constantly being added, and product descriptions are often repetitive. Simple machine translation often falls short, and the same texts need to be checked repeatedly.

The "Product Translations" plugin automates the entire translation process. Additionally, it bridges the gap between automatic translations and professional agency services by consolidating everything into one place within a single application, offering the best combination of AI translation and human review.

Using AI, it monitors content quality, allowing you to prioritize your work. Every human review and correction is saved into a TRANSLATION MEMORY, ensuring you never repeat the same task twice. The plugin continually learns and improves the content over time. Everything is managed from an intuitive dashboard, where you can track the translation quality of each product. This helps you create high-quality SEO content at minimal cost.

* **Easy localization management:** Manage all language versions of your products and categories from one place – save time and simplify the localization and translation process into foreign languages.
* **Automated product export:** Speed up the product publishing process. Simply add a product to the source e-shop, and LOCO will automatically translate and localize it into all your language versions – including texts, images, and meta descriptions, while preserving HTML formatting, making it easier to manage across global markets.
* **AI and human review combination:** Achieve optimal translation quality and relevance while minimizing manual work – thanks to the ideal combination of AI and human review. You can easily track whether each product has been reviewed or not. The initial AI-powered translation includes a quality assessment for evaluation.
* **Localization cost savings:** Our ability to leverage previous translations and minimize repeat work can save you up to 80% on localization costs without compromising quality. Use our TRANSLATION MEMORY technology to detect duplicate texts. LOCO translates only the unique ones – each translation is saved into your memory – LOCO continuously learns and creates higher-quality content for your e-shop – reducing manual intervention to a minimum.
* **Flexible and affordable solution:** Easy installation. Try our service on 50 products for FREE. We offer a pricing structure tailored to shops of all sizes, with the option to translate products into all global languages.

= Key Plugin Features =
**Focus on Product Localization:**
* Custom **CAT tool** (translates only unique texts)
* Building a **TRANSLATION MEMORY** – **LOCO** keeps learning
* Preservation of **PRODUCT HTML formatting**
* **SEO and** URL localization
* Complete product listing on international e-commerce websites

**E-commerce-focused translations:**
* **Automate and streamline** the localization process
* Fast **AI-powered translations** (chatGTP, DeepL, Google Translate)
* **Translation quality estimation using AI**
* Decision-making on **human work** based on sales data and Google analytics
    - **Check-page** – a custom interface for translators
    - **Export** (xliff) for translation tools and agencies to ensure efficient and accurate localization by professionals.

=FREE:=
- **Analysis of your product texts – see the number of repetitions and know exactly how much the translation will cost**
- **Try LOCO for FREE on 50 of your products and data**

== Installation ==
1. Activate the plugin through the 'Plugins' menu in WordPress.
2. in menu "Plugins" -> "Product Translations for WooCommerce" enter form data and click "Connect".
You're done!

== Screenshots ==
1. LOCO App Pricing
2. LOCO App Translation memory
3. LOCO App Dashboard
4. LOCO App Quality score
5. LOCO App Check page
6. LOCO App Connections
6. LOCO App Translation correction

== External Services ==
This plugin uses the following third-party services for its functionality:

1. LOCO App – [LOCO App](https://loco-app.expan.do/)
   This plugin communicates with an external service, which is [LOCO App](https://loco-app.expan.do/), under the following circumstances:

   a) When the connection form is submitted, the information is sent to the LOCO App application, specifically to the "installShop" endpoint, where a user account is created.
   This account allows further interaction with the LOCO app.

    Data sent:
      - Website name
      - Website URL
      - Email address
      - Phone number
      - Application language
      - Api2Cart bridge URL
      - Api2Cart store key

  Based on this information, an account is generated on the Api2Cart platform to access the API of your WooCommerce store.
  (For more details on how the data transfer via API works, refer to the api2cart-bridge-connector plugin.)

  b) Upon subsequent access to the plugin page, the user is automatically redirected to the LOCO App, logged in under the account registered through this WooCommerce store.

  c) When the plugin is deactivated, information is sent to LOCO App via the "uninstall" endpoint, informing the system about the deactivation of the specific WooCommerce store. At the same time, Api2Cart's access to WooCommerce is automatically removed. After this, neither LOCO App nor Api2Cart will have access to any information on this store.

 More information about the terms of use and privacy policy can be found here:
   - [Service Terms of Use](https://loco.expan.do/en/terms-and-conditions)

2. Api2Cart - [Api2Cart](https://app.api2cart.com/)
    This plugin requires plugin api2cart-bridge-connector for its functionality.
    More information about Api2cart plugin please refer to [Api2Cart](https://app.api2cart.com/).


== Legal Disclaimer ==
By using this plugin, you agree to the aforementioned terms, and the plugin will only send data during clearly defined actions. Please ensure you are familiar with any legal issues related to data transmission to external services.
