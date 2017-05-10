# bestit/commercetools-filter-bundle

Add filter and search implementation for commerce tools.
It still uses the [commmercetools/php-sdk](https://github.com/commercetools/commercetools-php-sdk) under the hood.

## Installation

### Step 1: Add repository

Open composer.json and add the repository:

```composer.json
    "repositories": [
        {
            "type": "vcs",
            "url":  "https://bitbucket.org/best-it/commercetools-filter-bundle.git"
        }
    ],
```

### Step 2: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require bestit/commercetools-filter-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new new BestIt\Commercetools\FilterBundle\BestItCommercetoolsFilterBundle(),
        );

        // ...
    }

    // ...
}
```

### Step 3: Configure the Bundle

```
# Default configuration for "BestItCommercetoolsFilterBundle"
best_it_commercetools_filter:

    # Amount of products per page
    products_per_page: 20
    
    # Amount of neighbours at pagination
    neighbours: 1
    
    # Http query key for current page
    page_query_key: page
    
    # Http query key for current sort
    sort_query_key: sort
    
    # Http query key for current view
    view_query_key: view
    
    # Default view mode 
    default_view: list
    
    # Product normalizer for normalizing fetched products (service id)
    product_normalizer_id: bh.app.normalizer.product_normalizer
    
    # Used http client (service id)
    client_id: bh.app.commercetools.client                                      # Required
    
    # Config provider for filter configs (service id)
    config_provider_id: bh_app.factory.facet_config_collection_factory
    
    # Available sortings
    sorting:                                                                    # Required (at least one element)
        price_asc:
            # Translation key for translation
            translation: sorting_price_asc                                      # Required
            
            # Commerce tools query command
            query: 'price asc'                                                  # Required
        price_desc:
            translation: sorting_price_desc
            query: 'price desc'
        name_asc:
            translation: sorting_name_asc
            query: 'name.de asc'
            
            # Use this as default
            default: true
        name_desc:
            translation: sorting_name_desc
            query: 'name.de desc'
        created_at_asc:
            translation: sorting_created_at_asc
            query: 'createdAt asc'
        created_at_desc:
            translation: sorting_created_at_desc
            query: 'createdAt desc'
```

## Usage

### Listing

Just execute the _listing_ method of the filter manager in your listing controller. It need the Symfony Request object 
and the current listing category (Commercetools Object) for creating the listing request.

Example:

```php
# ListingController.php
    public function indexAction(Request $request, Category $category): array
    {
        $result = $this->get('best_it_commercetools_filter.manager.filter_manager')->listing($request, $category);
        
        return [
            'products' => $result->getProducts(),
            'totalProducts' => $result->getTotalProducts(),
            'context' => $result->getContext(),
            'pagination' => $result->getPagination(),
            'sorting' => $result->getSorting(),
            'facetForm' => $result->getForm()
        ];
    }
```

### Search

Just execute the _search_ method of the filter manager in your listing controller. It need the Symfony Request object 
and the search string for creating the search request.

Example:

```php
# SearchController.php
    public function indexAction(Request $request, Category $category): array
    {
        $result = $this->get('best_it_commercetools_filter.manager.filter_manager')->search($request, $request->query->get('search'));
        
        return [
            'products' => $result->getProducts(),
            'totalProducts' => $result->getTotalProducts(),
            'context' => $result->getContext(),
            'pagination' => $result->getPagination(),
            'sorting' => $result->getSorting(),
            'facetForm' => $result->getForm()
        ];
    }
```