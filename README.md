# bestit/commercetools-filter-bundle

Add filter and search implementation for commerce tools.
It still uses the [commmercetools/php-sdk](https://github.com/commercetools/commercetools-php-sdk) under the hood.

## Installation

### Step 1: Download the Bundle

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

            new BestIt\Commercetools\FilterBundle\BestItCommercetoolsFilterBundle(),
        );

        // ...
    }

    // ...
}
```

### Step 3: Configure the Bundle

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

    # Used translation domain (default "messages")
    translation_domain:   messages

    # Product normalizer (service id / implement interface)
    product_normalizer_id: best_it_commercetools_filter.normalizer.empty_product_normalizer

    # Client service id of commerce tools sdk client
    client_id:            ~ # Required

    # Optional facets factory config provider (service id / implement interface)
    config_provider_id:   best_it_commercetools_filter.provider.empty_facet_config_provider

    # Generator for filter urls
    url_generator_id:     best_it_commercetools_filter.generator.default_filter_url_generator

    # Cache life time. Enum Attribute labels are cached to minimize CommerceTools requests.
    cache_life_time:      86400
 
    # Sorting. At least one sorting options must exist
    sorting:              # Required

        # The default sort
        default:              ~ # Required

        # Define the sorting id, the translation key and sort query. This is an array with all available sortings.
        choices:              # Required

            # Prototype
            key:

                # Api query for sdk
                query:                ~ # Required

                # Translation key
                translation:          ~ # Required

    # Pagination settings
    pagination:

        # Products per page
        products_per_page:    20

        # Neighbours at pagination 1 => "1 2 3" | 2 => "1 2 3 4 5"
        neighbours:           1

    # View settings
    view:

        # Default view type (eg. grid, list)
        default:              list

    # Facet config
    facet:

        # Translation key for reset button or false for disable reset button
        reset:                reset

        # Translation key for reset button or false for disable submit button
        submit:               submit
    
    # Suggest config
    suggest:
        
        # Use fuzzy suggest (default: true)
        enable_fuzzy:         false
        
        # The fuzziness level is quantified in terms of the Damerau-Levenshtein distance.
        # If null, the platform selects level based on the length of the searched text 
        # Default: null
        fuzzy_level:          2
        
        # Mark matching variants with "isMatchingVariant". (default: false)
        match_variants:       true
        
    # Search config
    search:
        
        # Use fuzzy search (default: true)
        enable_fuzzy:         false
             
        # The fuzziness level is quantified in terms of the Damerau-Levenshtein distance.
        # If null, the platform selects level based on the length of the searched text
        # Default: null
        fuzzy_level:          2
                   
        # Mark matching variants with "isMatchingVariant". (default: false)
        match_variants:       true
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

### Url generator

The filter bundle need to create urls, but the route names can vary between projects. So you can add your own url generator for creating the correct urls with the _url_generator_id_ parameter.
The generator need to implement the FilterUrlGeneratorInterface. The bundle will use his own default generator if you do not fill the field.

### Events ###

#### Request events ####
You can modify the commercetools client request with the filter and suggest post events. Check all events @ _SuggestEvent_ and _FilterEvent_ class.
 
Example usage: Extend request
```
// FilterRequestSubscriber.php

class FilterRequestSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            SuggestEvent::PRODUCTS_REQUEST_POST => 'onProductsFilterRequest',
            FilterEvent::PRODUCTS_REQUEST_POST => 'onProductsSuggestRequest'
        ];
    }

    /**
     * On products request for filter
     * @param ProductProjectionSearchRequestEvent $event
     */
    public function onProductsFilterRequest(ProductProjectionSearchRequestEvent $event)
    {
        $request = $event->getRequest();

        $request
            ->expand('masterVariant.attributes[*].value')
            ->expand('productType');

        $event->setRequest($request);
    }

    /**
     * On products request for suggest
     * @param ProductProjectionSearchRequestEvent $event
     */
    public function onProductsSuggestRequest(ProductProjectionSearchRequestEvent $event)
    {
        $request = $event->getRequest();

        $request
            ->expand('categories[*]')
            ->expand('masterVariant.attributes[*].value[*].value')
            ->expand('productType');

        $event->setRequest($request);
    }
}
```
