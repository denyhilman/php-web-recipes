<?php // 0.0.1
function crosstab($rawTable, $columnHeader, $rowHeader, $aggregateColumn, $operation) {
	// validate operations
	$operations = array('average', 'count', 'max', 'min', 'sum');
	if (!in_array($operation, $operations)) {
		throw new Exception('Error: Operation argument is not valid.  It must be either average, count, max, min or sum in the crosstab function.');
	}

	// do first pass to extract column headers to create an empty row array
	$emptyRow = array();
	foreach($rawTable as $row) {
		foreach($row as $key => $value) {
			if ($key == $columnHeader) {
				$emptyRow[$value] = NULL;
			}
		}
	}

	// do second pass to create a resultset
	$result = array();
	foreach($rawTable as $rowNumber => $row) {
		unset($rowValue);
		unset($columnValue);
		unset($aggregateValue);
		foreach($row as $key => $value) {
			switch ($key) {
				case $columnHeader:
					$columnValue = $value;
					break;
				case $rowHeader:
					$rowValue = $value;
					break;
				case $aggregateColumn:
					$aggregateValue = $value;
					break;
			}
		}
		if (!isset($rowValue)) {
			throw new Exception("Missing row key: Column $rowHeader is missing from row $rowNumber.");
		} else if (!isset($columnValue)) {
			throw new Exception("Missing column key: Column $columnHeader is missing from row $rowNumber.");
		} else if (!isset($aggregateValue)) {
			throw new Exception("Missing aggregate key: Column $aggregateColumn is missing from row $rowNumber.");
		} else {
			if (!isset($result[$rowValue])) {
				$result[$rowValue] = $emptyRow;
			}
			$cellValue = &$result[$rowValue][$columnValue];
			switch ($operation) {
				case 'average':
					if ($cellValue === NULL) {
						$cellValue = array('count' => 0, 'total' => 0);
					}
					$cellValue['count']++;
					$cellValue['total'] += $aggregateValue;
					break;
				case 'count':
					if ($cellValue === NULL) {
						$cellValue = 0;
					}
					$cellValue++;
					break;
				case 'min':
					if ($cellValue === NULL) {
						$cellValue = $aggregateValue;
					} else if ($aggregateValue < $cellValue) {
						$cellValue = $aggregateValue;
					}
					break;
				case 'max':
					if ($cellValue === NULL) {
						$cellValue = $aggregateValue;
					} else if ($aggregateValue > $cellValue) {
						$cellValue = $aggregateValue;
					}
					break;
				case 'sum':
					$cellValue += $aggregateValue;
					break;
			}
		}
	}

	// if we're calculating averages, do third pass to calculate them
	if ($operation == 'average') {
		foreach($result as $rowKey => $row) {
			foreach($row as $columnKey => $value) {
				$cellData = &$result[$rowKey][$columnKey];
				if ($cellData !== NULL) {
					$cellData = $cellData['total'] / $cellData['count'];
				}
			}
		}
	}

	return $result;
}
?>