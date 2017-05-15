# bestit/commercetools-filter-bundle

Add filter and search implementation for commerce tools.
It still uses the [commmercetools/php-sdk](https://github.com/commercetools/commercetools-php-sdk) under the hood.

## Installation

### Step 1: Add repository

Open composer.json and add the repository:

```
# composer.json

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

### Step 3: Enable the Bundle

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

### Step 4: Configure the Bundle

#### Minimum configuration (only _all_ required fields)
```
# Minimum configuration for "BestItCommercetoolsFilterBundle"
best_it_commercetools_filter:
    
    # Client service id of commerce tools sdk client
    client_id: app.commercetools.client
    
    # Sorting. At least one sorting options must exist
    sorting:
    
        # Default sorting to use if no sorting is selected
        default: name_asc
        
        # This is an array with all available sortings.
        choices:
            name_asc:
            
                # Translation key
                translation: sorting_name_asc
                
                # Api query for sdk
                query: 'name.de asc'
```

#### Maximum configuration (all required and optional fields)
```
# Maximum configuration for "BestItCommercetoolsFilterBundle"
best_it_commercetools_filter:

    # Optional used translation domain (default "messages")
    translation_domain: app
    
    # Optional product normalizer (service id / implement interface)
    product_normalizer_id: app.normalizer.product_normalizer
    
    # Client service id of commerce tools sdk client
    client_id: app.commercetools.client                                             # Required
    
    # Optional facets factory config provider (service id / implement interface)
    config_provider_id: app.provider.facet_config_provider
    
    # Optional facet config
    facet:
    
        # Translation label for reset button OR false for disable button
        reset: button_filter_reset
        
        # Translation label for submit button OR false for disable button
        submit: false
    
    # Optional pagination settings
    pagination:
        
        # Products per page
        products_per_page: 20
        
        # Neighbours at pagination 1 => "1 2 3" | 2 => "1 2 3 4 5"
        neighbours: 1
        
        # Query key name
        query_key: page
        
    # Optional view settings
    view:
    
        # Query key name
        query_key: view
        
        # Default value
        default: list
        
    # Sorting. At least one sorting options must exist
    sorting:                                                                        # Required
        
        # Optional query key name
        query_key: sort
        
        # Default sorting to use if no sorting is selected
        default: name_asc                                                           # Required
        
        # This is an array with all available sortings.
        choices:
            price_asc:
            
                # Translation key
                translation: sorting_price_asc                                      # Required
                
                # Api query for sdk
                query: 'price asc'                                                  # Required
            price_desc:
                translation: sorting_price_desc
                query: 'price desc'
            name_asc:
                translation: sorting_name_asc
                query: 'name.de asc'
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
// ListingController.php

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
// SearchController.php

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

### Product Normalizer

In most cases, products need to be normalized for the frontend. You can choose one of the base normalizer from filter bundle or use your 
own if you implement the _ProductNormalizerInterface_ and add the service id to the app config. The filter bundle contains two base normalizer:

* ArrayProductNormalizer: Converts the _ProductProjection_ object to array.
* EmptyProductNormalizer: Just return the _ProductProjection_ without normalization

_EmptyProductNormalizer_ will be use if you don't fill the _product_normalizer_id_ Parameter (@ config.yml).

### Config Provider id

You can add you own filter config provider. Just implement the _FacetConfigProviderInterface_ and add your service id to _config_provider_id_ (@ config.yml).
The filter bundle default provider will be use if you don't fill the _config_provider_id_ Parameter (@ config.yml), which returns no filters.