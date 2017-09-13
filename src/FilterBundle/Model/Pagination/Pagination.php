<?php

namespace BestIt\Commercetools\FilterBundle\Model\Pagination;

/**
 * Data model for pagination
 *
 * @author     chowanski <chowanski@bestit-online.de>
 * @package    BestIt\Commercetools\FilterBundle
 * @subpackage Model\Pagination
 */
class Pagination
{
    /**
     * The first page
     *
     * @var int
     */
    private $firstPage = 1;

    /**
     * The last page
     *
     * @var int
     */
    private $lastPage;

    /**
     * The current page
     *
     * @var int
     */
    private $currentPage;

    /**
     * Total pages
     *
     * @var int
     */
    private $totalPages;

    /**
     * Array of prev pages
     *
     * @var int[]
     */
    private $previousPages = [];

    /**
     * Array of next pages
     *
     * @var int[]
     */
    private $nextPages = [];

    /**
     * Pagination constructor
     *
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        foreach ($values as $key => $value) {
            $setter = sprintf('set%s', ucfirst($key));
            if (property_exists($this, $key) && method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }
    }

    /**
     * Get currentPage
     *
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * Get firstPage
     *
     * @return int
     */
    public function getFirstPage(): int
    {
        return $this->firstPage;
    }

    /**
     * Get lastPage
     *
     * @return int
     */
    public function getLastPage(): int
    {
        return $this->lastPage;
    }

    /**
     * Get nextPages
     *
     * @return int[]
     */
    public function getNextPages(): array
    {
        return $this->nextPages;
    }

    /**
     * Get previousPages
     *
     * @return int[]
     */
    public function getPreviousPages(): array
    {
        return $this->previousPages;
    }

    /**
     * Get totalPages
     *
     * @return int
     */
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * Set currentPage
     *
     * @param int $currentPage
     *
     * @return Pagination
     */
    public function setCurrentPage(int $currentPage): Pagination
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    /**
     * Set firstPage
     *
     * @param int $firstPage
     *
     * @return Pagination
     */
    public function setFirstPage(int $firstPage): Pagination
    {
        $this->firstPage = $firstPage;

        return $this;
    }

    /**
     * Set lastPage
     *
     * @param int $lastPage
     *
     * @return Pagination
     */
    public function setLastPage(int $lastPage): Pagination
    {
        $this->lastPage = $lastPage;

        return $this;
    }

    /**
     * Set nextPages
     *
     * @param int[] $nextPages
     *
     * @return Pagination
     */
    public function setNextPages(array $nextPages): Pagination
    {
        $this->nextPages = $nextPages;

        return $this;
    }

    /**
     * Set previousPages
     *
     * @param int[] $previousPages
     *
     * @return Pagination
     */
    public function setPreviousPages(array $previousPages): Pagination
    {
        $this->previousPages = $previousPages;

        return $this;
    }

    /**
     * Set totalPages
     *
     * @param int $totalPages
     *
     * @return Pagination
     */
    public function setTotalPages(int $totalPages): Pagination
    {
        $this->totalPages = $totalPages;

        return $this;
    }
}
