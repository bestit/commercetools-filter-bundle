<?php

namespace BestIt\Commercetools\FilterBundle\Model\Facet;

use BestIt\Commercetools\FilterBundle\Enum\FacetType;
use InvalidArgumentException;

/**
 * Config object for facet
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model\Facet
 */
class FacetConfig
{
    /**
     * Facet name
     *
     * @var string
     */
    private $name;

    /**
     * Field
     *
     * @var string
     */
    private $field;

    /**
     * Filter field
     *
     * @var string
     */
    private $filterField;

    /**
     * Facet field
     *
     * @var string
     */
    private $facetField;

    /**
     * Alias field
     *
     * @var string
     */
    private $alias;

    /**
     * Is hierarchical?
     *
     * @var bool
     */
    private $hierarchical = false;

    /**
     * Is multiselect?
     *
     * @var bool
     */
    private $multiSelect = true;

    /**
     * Type of facet
     *
     * @var string
     */
    private $type;

    /**
     * Weight of facet
     *
     * @var int
     */
    private $weight = 0;

    /**
     * Show term count in label
     * @var bool
     */
    private $showCount = false;

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias(): string
    {
        $result = $this->alias;
        if ($this->alias === null) {
            $result = $this->name;
        }

        return $result;
    }

    /**
     * Get facetField
     *
     * @return string
     */
    public function getFacetField(): string
    {
        if ($this->facetField === null) {
            switch ($this->type) {
                case FacetType::TEXT:
                    $this->facetField = sprintf('variants.attributes.%s', $this->getField());
                    break;
                case FacetType::LOCALIZED_TEXT:
                    $this->facetField = sprintf('variants.attributes.de.%s', $this->getField());
                    break;
                case FacetType::ENUM:
                case FacetType::LENUM:
                    $this->facetField = sprintf('variants.attributes.%s.key', $this->getField());
                    break;
                case FacetType::CATEGORY:
                    $this->facetField = 'categories.id';
                    break;
                case FacetType::RANGE:
                    $this->facetField = sprintf('%s:range(0 to *)', $this->getField());
                    break;
                default:
                    throw new InvalidArgumentException(sprintf('Facet type not configured for facet %s', $this->name));
            }
        }

        return $this->facetField;
    }

    /**
     * Get field
     *
     * @return string
     */
    public function getField(): string
    {
        if ($this->field === null) {
            $this->field = $this->name;
        }

        return $this->field;
    }

    /**
     * Getter for ShowCount.
     *
     * @return bool
     */
    public function isShowCount(): bool
    {
        return $this->showCount;
    }

    /**
     * Setter for ShowCount.
     *
     * @param bool $showCount
     *
     * @return FacetConfig
     */
    public function setShowCount(bool $showCount): FacetConfig
    {
        $this->showCount = $showCount;
        return $this;
    }

    /**
     * Get filterField
     *
     * @return string
     */
    public function getFilterField(): string
    {
        if ($this->filterField === null) {
            switch ($this->type) {
                case FacetType::TEXT:
                    $this->filterField = sprintf('variants.attributes.%s', $this->getField());
                    break;
                case FacetType::LOCALIZED_TEXT:
                    $this->filterField = sprintf('variants.attributes.de.%s', $this->getField());
                    break;
                case FacetType::ENUM:
                case FacetType::LENUM:
                    $this->filterField = sprintf('variants.attributes.%s.key', $this->getField());
                    break;
                case FacetType::CATEGORY:
                    $this->filterField = 'categories.id';
                    break;
                case FacetType::RANGE:
                    $this->filterField = $this->getField();
                    break;
                default:
                    throw new InvalidArgumentException(
                        sprintf(
                            'Facet type not configured for facet %s',
                            $this->getField()
                        )
                    );
            }
        }

        return $this->filterField;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get weight
     *
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * Get hierarchical
     *
     * @return bool
     */
    public function isHierarchical(): bool
    {
        return $this->hierarchical;
    }

    /**
     * Get multiSelect
     *
     * @return bool
     */
    public function isMultiSelect(): bool
    {
        return $this->multiSelect;
    }

    /**
     * Set alias
     *
     * @param string $alias
     *
     * @return FacetConfig
     */
    public function setAlias(string $alias): FacetConfig
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Set facetField
     *
     * @param string $facetField
     *
     * @return FacetConfig
     */
    public function setFacetField(string $facetField): FacetConfig
    {
        $this->facetField = $facetField;

        return $this;
    }

    /**
     * Set field
     *
     * @param string $field
     *
     * @return FacetConfig
     */
    public function setField(string $field): FacetConfig
    {
        $this->field = $field;

        return $this;
    }

    /**
     * Set filterField
     *
     * @param string $filterField
     *
     * @return FacetConfig
     */
    public function setFilterField(string $filterField): FacetConfig
    {
        $this->filterField = $filterField;

        return $this;
    }

    /**
     * Set hierarchical
     *
     * @param bool $hierarchical
     *
     * @return FacetConfig
     */
    public function setHierarchical(bool $hierarchical): FacetConfig
    {
        $this->hierarchical = $hierarchical;

        return $this;
    }

    /**
     * Set multiSelect
     *
     * @param bool $multiSelect
     *
     * @return FacetConfig
     */
    public function setMultiSelect(bool $multiSelect): FacetConfig
    {
        $this->multiSelect = $multiSelect;

        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return FacetConfig
     */
    public function setName(string $name): FacetConfig
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return FacetConfig
     */
    public function setType(string $type): FacetConfig
    {
        if (!FacetType::isValid($type)) {
            throw new InvalidArgumentException('Type must be a valid enum value');
        }

        $this->type = $type;

        return $this;
    }

    /**
     * Set weight
     *
     * @param int $weight
     *
     * @return FacetConfig
     */
    public function setWeight(int $weight): FacetConfig
    {
        $this->weight = $weight;

        return $this;
    }
}
