<?php
$this->layout = null;
$this->Csv->setFilename('multipass ' . $merchant_id);
$this->Csv->delimiter = $delimiter;

if (!empty($rows)) {
	foreach ($rows as $row) {
		$this->Csv->addRow($row);
		unset($row);
	}
}

echo $this->Csv->render(true, 'UTF-8');