braintree-payments
==================

Braintree Payments Opencart module is the simple and lightweight implementation of braintree.com payment service for Opencart. It's licensed under MIT license, feel free to use it in any project or modify the source code at your will.

<h3>Getting Started</h3>

<h4>Installation</h4>

The most convenient way to install the extension is with Composer, add following code to your _composer.json_ file:

```
{
  "require": {
    "andyvr/braintree-payments": "@dev"
  },
  "scripts": {
    "post-install-cmd": [
      "sh ./vendor/andyvr/braintree-payments/extension-install.sh"
    ]
   }
}
```

If you don't use Composer you can just copy over _<b>admin</b>_, _<b>catalog</b>_ and _<b>vendor</b>_ folders to your Opencart root.

<h4>Setup</h4>

Activate the extension thru your Opencart Admin Panel.

The extension uses the Braintree API. You need to enter either Sandbox or Production Public/Private API keys and the Merchant ID on the extension settings page.

In order to obtain this information please login to your Braintree account dashboard, click on _Account_ in the top right corner of the page, then _API Keys_.

When switching to Production mode please replace API keys and Merchant ID with Production ones and change _Transaction Mode_ setting to _Production_. You're now ready to accept credit card payments thru Braintree. 
