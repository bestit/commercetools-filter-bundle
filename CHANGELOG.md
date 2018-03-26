## [8.3.0] - 2018-02-05
### Added
- Added the base query to the suggest result to prevent duplicated queries in frontends @avareldev

## [8.2.1] - 2018-02-05
### Bugfix
- Fix exception while execute invalid request caused by invalid attributes names @avareldev

## [8.2.0] - 2018-01-19
### Added
- Added options for setting a different default sorting for search and listing @bestit-knoop

## [8.1.0] - 2017-11-16
### Added
- Added SortType as enum to config. Added sorting in buildForm-Function to be backwards compatible @avareldev

## [8.0.0] - 2017-11-03
### Added
- Add TermNormalizerCollection and tag `best_it_commercetools_filter.term_normalizer` @migo315
- Add FacetFilterType configuration for category normalizer @chowanski
- Add SkipTermException possibility for TermNormalizer if you want to skip the term @migo315
- Add `base_category_query` for defining a base category via config @migo315

### Removed
- Remove CategoryListener and EnumAttributeListener - normalizer now injected directly via the term normalizer tag @migo315
- Remove event dispatcher from FacetCollectionFactory (use TermNormalizer tag and interface instead) @migo315
- Remove configuration for `normalizer_id` @migo315

### Changed
- TermNormalizerInterface has a new additional argument: SearchContext @migo315

## [7.1.0] - 2017-10-26
### Added
- Add SetType handling for EnumAttributeNormalizer @migo315
- Add search keyword for products request events @migo315

## [7.0.0] - 2017-10-06
### Added
- New argument for filter manager / suggest manager for defining search language @migo315

### Removed
- Language from commercetools client will be ignored @migo315

### Bugfix
- Fix wrong facet results which caused filter combinations with zero products @migo315
- Fix wrong facet sorting (high to low) @migo315
- Fix wrong ltext facet definition @migo315

## [6.1.0] - 2017-10-05
### Added
- Add new event to collect and manipulate facet terms (see FilterEvent.php) @migo315
- New interface for term manipulation: _TermNormalizerInterface_ @migo315
- Detailed configuration (service id, cache id, cache time, state) for enum term normalization @migo315
- Detailed configuration (service id, cache id, cache time, state) for category term normalization @migo315

### Changed
- Sorting query can be null for "no-sorting" / "relevance sorting" @migo315

### Deprecated
- Global cache time from config (can now defined for each service separate) @migo315

## [6.0.0] - 2017-09-25
### Added
- Added fuzzy config for search and suggest / with optional fuzzy level @migo315
- All Result classes now also contain the origin commercetools response @migo315
- Add config class for suggest

### Changed
- FilterManagerInterface now return a SearchResult instead of old Result class (Breaking Change!) @migo315
- Suggest and Keywords return a result class (KeywordsResult / SuggestResult) instead of array (Breaking Change!) @migo315
- Hard localization in search for 'de' removed / now all languages of the commercetools client will be used for full-text keywords (Breaking Change!) @migo315
- Models refactored and have subdirectories (Breaking Change!) @migo315

## [5.1.0] - 2017-09-18
### Added
- Add config flag to mark matching variants.

## [5.0.1] - 2017-08-24
### Bugfix
- Added bugfix for undefined index.

## [5.0.0] - 2017-08-17
### Added
- Added caching for enum attributes.

## [4.1.0] - 2017-07-19
### Added
- Adding config option to add term count to label. @bestit-tkellner

## [4.0.0] - 2017-07-14
### Added
- Adding improved functions for sorting, pagination and views. @bestit-tkellner

## [3.2.1] - 2017-07-13
### Added
- Adding functionality for form labels for (l)enums @bestit-tkellner

## [3.2.0] - 2017-05-22
### Added
- Add FilterUrlGeneratorInterface @migo315
- Add DefaultFilterUrlGenerator @migo315
- Add Changelog.md @migo315
- Add ResetType @migo315

### Deprecated
- Property 'route' of Context object will be removed @migo315

## [3.1.0] - 2017-05-15
### Added
- Initial stable release @migo315