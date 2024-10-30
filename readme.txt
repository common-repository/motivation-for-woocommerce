=== Motivation for WooCommerce ===
Contributors: ivanchernyakov
Tags: notices, gifts, discounts, cart, ecommerce, call to action, woocommerce, woo, sales, checkout, sell, store
Requires at least: 5.0.1
Tested up to: 5.2.2
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Motivate your customers for desired actions with combination of advanced notices, discounts and gifts.

== Description ==
Motivation for WooCommerce display on cart and checkout pages advanced notices, that may offer the buyer to change their cart, for example, by adding additional products. These messages display a gift/discount, as well as a call to action. 

= Examples of usage =
*   Offer free shipping if your customer has ordered a minimum amount of goods.
*   Сart notice that will motivate the buyer to make more purchases to get this gift. In this case, he will be helped by a call to action, which can also be added to the right of the gift.
*   Similarly, the situation with a discount. But instead of a gift the discount amount will be shown.
*   Forbid to switch to checkout page if the minimum threshold is not crossed in the cart.

= List of the features =
*   **Create informational notices** - let your customers know that they can get some benefit.
*   **Create success notices** - let them see that they have achieved their desired goal and received a gift or a discount.
*   **Create error notices** - disallow your customers to proceed to payment if your conditions are not met.
*   **Add gifts to your message** - give gifts for certain conditions.
*   **Add discounts to your message** - give discounts for certain conditions.
*   **Add call to action to your message** - post the link so that the user can return to the store or to another location.

== Installation ==
1. Upload the entire plugin folder to the /wp-content/plugins/ directory.
2. Activate the plugin through the “Plugins” menu in WordPress.
3. Go to “Motivations” tab.

== Frequently Asked Questions ==
= Can I use it with other discount/gift plugins? =
Yes, you can. Then do not use a special checkbox that activates the built-in functions of discounts/gifts.
= Why there is no success notice when the condition is met? =
You need to create such a notice separately with the type "success" and put on it conditions different from the notice in which the conditions must be fulfilled.

== Screenshots ==
1. Cart Notices
2. Admin Panel Motivations
3. Single Motivation Notice
4. Order Review

== Changelog ==
= 1.0.0 =
Improved admin style.
Added new dynamic content variables {{min-triggered-price-left}} and {{max-triggered-price-left}}.
Added "excluded" checkbox for triggered values.
Fixed bug when discount was always applied, regardless of conditions.

= 0.9.1 =
Fixed Style Issues.

= 0.9 =
First Publication.