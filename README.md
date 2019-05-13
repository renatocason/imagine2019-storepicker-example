# Store Picker example extension
Used for the talk _The Beginning of your GraphQL Journey with Magento 2.3_ at Magento Imagine 2019, by [Renato Cason](https://github.com/renatocason).

## Introduction
This extension is meant to be a proof of concept and a sandbox for understanding the explained concepts.
It is not meant to be actually used in a real Magento shop.

The module is a simple extension with an entity, stored in the database and managed via the admin, that represent the link between a country and a store.
For example, you might want to direct US and GB users to the EN (English) store view, Spanish and Argentinian users to the ES (Spanish) store view, and so on.
The configuration is accessible from a GraphQL client.
No other interaction has been developed for the proof of concept, but for instance the information could be used from a mobile app or PWA frontend to route the customer to the preferred store.

## Usage
The module should be installed via composer and enabled with the Magento CLI tool.
On _setup:upgrade_, all the avilable countries will be used to populate the _storepicker_location_ database table.

### Configuration
For a better result, all store views should have different base Urls. This can be easily done by setting _Add Store Code to Urls_ to _Yes_.
The links between country and store view can be edited, added and removed via the admin, under _Stores > Store Picker Locations_.

### GraphQL
A GraphQL client can be pointed to the `/graphql` endpoint of your store, and you can query the extension's data - i.e.:

```
{
  locations {
    country_id
    store_id
    country {
      full_name_english
      full_name_locale
    }
  }
}
```
