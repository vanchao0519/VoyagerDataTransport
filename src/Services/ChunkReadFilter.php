<?php


namespace VoyagerDataTransport\Services;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class ChunkReadFilter implements IReadFilter
{
    /**
     * Start row
     *
     * @var int
     */
    private $startRow = 0;

    /**
     * End row
     *
     * @var int
     */
    private $endRow = 0;

    /**
     * Set rows
     *
     * @param int $startRow
     * @param int $chunkSize
     * @return void
     */
    public function setRows (int $startRow, int $chunkSize): void {
        $this->startRow = $startRow;
        $this->endRow = $startRow + $chunkSize;
    }

    /**
     * Read cell
     *
     * @param string $columnAddress
     * @param int $row
     * @param string $worksheetName
     * @return bool
     */
    public function readCell ($columnAddress, $row, $worksheetName = ''): bool
    {
        if (($row === 1) || ($row >= $this->startRow && $row < $this->endRow)) {
            return true;
        }
        return false;
    }

}