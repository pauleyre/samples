<?php

class Chart
{
	var $data;
	var $dbconn;

	function Chart($data_array)
	{
		// * constructor
		$this->data = $data_array;

		// * constructor, redaclaring global db connector
		global $dbc;
		$this->dbconn = $dbc->_db;
	}

	function monthly_chart_data($range = 7, $detailed = false)
	{
		$interval = intval($range);
		$interval = ($interval > 0) ? $interval : 7;

		// * fetch data
		if($interval == 99999){
			$sql = sprintf('SELECT		UNIX_TIMESTAMP(date) AS date, stock_value AS price
							FROM		orbx_mod_invest_stock
							WHERE
										fond=%s
							AND
										(DAYNAME(date) = "Monday")
							ORDER BY 	date',
							$this->dbconn->quote($this->data['id']));
		} else if($interval == 365) {
			$sql = sprintf('SELECT		UNIX_TIMESTAMP(date) AS date, stock_value AS price
							FROM		orbx_mod_invest_stock
							WHERE
										fond=%s
							AND
										date <= (	SELECT 		MAX(date)
													FROM 		orbx_mod_invest_stock
													WHERE 		fond=%s
													GROUP BY fond) AND
										date >= ((	SELECT 		MAX(date)
													FROM 		orbx_mod_invest_stock
													WHERE 		fond=%s
													GROUP BY 	fond) - INTERVAL %s DAY)
							AND
										(DAYNAME(date) = "Monday")
							ORDER BY 	date',
							$this->dbconn->quote($this->data['id']),
							$this->dbconn->quote($this->data['id']),
							$this->dbconn->quote($this->data['id']),
							$this->dbconn->quote($interval));

		} else if($interval == 180) {
			$sql = sprintf('SELECT		UNIX_TIMESTAMP(date) AS date, stock_value AS price
							FROM		orbx_mod_invest_stock
							WHERE
										fond=%s
							AND
										date <= (	SELECT 		MAX(date)
													FROM 		orbx_mod_invest_stock
													WHERE 		fond=%s
													GROUP BY fond) AND
										date >= ((	SELECT 		MAX(date)
													FROM 		orbx_mod_invest_stock
													WHERE 		fond=%s
													GROUP BY 	fond) - INTERVAL 6 MONTH)
							AND
										(DAYNAME(date) = "Monday" OR DAYNAME(date) = "Friday")
							ORDER BY 	date',
							$this->dbconn->quote($this->data['id']),
							$this->dbconn->quote($this->data['id']),
							$this->dbconn->quote($this->data['id']));

		} else {
			$sql = sprintf('SELECT		UNIX_TIMESTAMP(date) AS date, stock_value AS price
							FROM		orbx_mod_invest_stock
							WHERE		fond=%s AND
										date <= (	SELECT 		MAX(date)
													FROM 		orbx_mod_invest_stock
													WHERE 		fond=%s
													GROUP BY fond) AND
										date >= ((	SELECT 		MAX(date)
													FROM 		orbx_mod_invest_stock
													WHERE 		fond=%s
													GROUP BY 	fond) - INTERVAL %s DAY)
							ORDER BY 	date',
							$this->dbconn->quote($this->data['id']),
							$this->dbconn->quote($this->data['id']),
							$this->dbconn->quote($this->data['id']),
							$this->dbconn->quote($interval));
		}

		$resource = $this->dbconn->query($sql);
		$result = $this->dbconn->fetch_array($resource);
		$i = 1;

		while($result) {

			// * arrange records
			$date = date($_SESSION['site_settings']['date_format'], $result['date']);
			$amount = round($result['price'], 2);
			$values[] = $amount;

			// * put records into array for easy sorting
			if($detailed == true) {

				/*if($range > 30) {
					if($i == 1) {
						$show_names = 1;
					}
					else {
						$show_names = (is_int($i / 10)) ? 1 : 0;
					}
				}
				else {
					$show_names = 1;
				}*/

				$xml[$date] .= "<set showName='0' value='$amount' hoverText='" . date($_SESSION['site_settings']['date_format'], $result['date']) . "' name='$date' />";
				$i ++;
			}
			else {
				$xml[$date] .= "<set value='$amount' hoverText='" . $amount . ', ' . date($_SESSION['site_settings']['date_format'], $result['date']) . "' />";
			}

			$result = $this->dbconn->fetch_array($resource);
		}

		// * create string
		$xml = implode('', $xml);

		// * get min/max
		sort($values);
		$min_val = array_shift($values);
		$max_val = array_pop($values);

		if($detailed == true) {

			/*if($range > 30) {
				$anchors = "anchorscale='0' anchorScale='0' anchorAlpha='0'";
			}
			else {
				$anchors = '';
			}*/

			$xml = "<graph yAxisMinValue='$min_val' yAxisMaxValue='$max_val' showValues='0' animation='1' showAlternateHGridColor='1' AlternateHGridColor='f6f6f6' divLineColor='e2e3e4' divLineAlpha='50' alternateHGridAlpha='100' canvasBorderColor='b5b8b9' canvasBorderThickness='1' baseFontColor='686868' lineColor='d11119' lineThickness='3' bgColor='ffffff' toolTipBgColor='f4f4f4' toolTipBorderColor='e2e2e2' $anchors chartTopMargin='25'>$xml</graph>";
		}
		else {
			$xml = "<chart toolTipBgColor='f4f4f4' toolTipBorderColor='e2e2e2' yAxisMinValue='$min_val' yAxisMaxValue='$max_val' showValues='0' divLineColor='e2e3e4' divLineAlpha='50' canvasBorderColor='b5b8b9' baseFontColor='4d4f51' lineColor='d11119' showAlternateHGridColor='1' alternateHGridColor='f6f6f6' alternateHGridAlpha='20' showColumnShadow='1'>$xml</chart>";
		}

		// free some memory
		unset($values, $min_val, $max_val, $amount);

		// cleanup from all sort of breaks. we need a oneliner
		$xml = str_sanitize($xml, STR_SANITIZE_XML);
		return $xml;
	}


	function summary_chart_data($id)
	{

		// * fetch data
		$sql = sprintf('SELECT		UNIX_TIMESTAMP(date) AS date, stock_value AS price
						FROM		orbx_mod_invest_stock
						WHERE		fond=%s AND
									date <= (	SELECT 		MAX(date)
												FROM 		orbx_mod_invest_stock
												WHERE 		fond=%s
												GROUP BY fond) AND
									date >= ((	SELECT 		MAX(date)
												FROM 		orbx_mod_invest_stock
												WHERE 		fond=%s
												GROUP BY 	fond) - INTERVAL 10 DAY)
						ORDER BY 	date',
						$this->dbconn->quote($id),
						$this->dbconn->quote($id),
						$this->dbconn->quote($id));

		$resource = $this->dbconn->query($sql);
		$result = $this->dbconn->fetch_array($resource);

		$i = 1;

		while($result) {

			// * arrange records
			$date = date($_SESSION['site_settings']['date_format'], $result['date']);
			$amount = round($result['price'], 2);
			$values[] = $amount;

			// * put records into array for easy sorting
			$xml[$date] .= "<set showName='0' value='$amount' hoverText='" . date($_SESSION['site_settings']['date_format'], $result['date']) . "' name='$date' />";
			$i ++;

			$result = $this->dbconn->fetch_array($resource);
		}

		// * create string
		$xml = implode('', $xml);

		// * get min/max
		sort($values);
		$min_val = array_shift($values);
		$max_val = array_pop($values);

		$xml = "<graph yAxisMinValue='$min_val' yAxisMaxValue='$max_val' showValues='0' animation='1' showAlternateHGridColor='1' AlternateHGridColor='f6f6f6' divLineColor='e2e3e4' divLineAlpha='50' alternateHGridAlpha='100' canvasBorderColor='cdcfd0' canvasBorderThickness='1' baseFontColor='686868' lineColor='d11119' lineThickness='3' bgColor='ffffff' bgAlpha='1' toolTipBgColor='f4f4f4' toolTipBorderColor='e2e2e2' $anchors chartTopMargin='25'>$xml</graph>";
		//$xml = "<chart toolTipBgColor='f4f4f4' toolTipBorderColor='e2e2e2' yAxisMinValue='$min_val' yAxisMaxValue='$max_val' showValues='0' divLineColor='e2e3e4' divLineAlpha='50' canvasBorderColor='b5b8b9' baseFontColor='4d4f51' lineColor='d11119' showAlternateHGridColor='1' alternateHGridColor='f6f6f6' alternateHGridAlpha='20' showColumnShadow='1' yAxisValuesPadding='5'>$xml</chart>";
		// free some memory
		unset($values, $min_val, $max_val, $amount);

		// cleanup from all sort of breaks. we need a oneliner
		$xml = str_sanitize($xml, STR_SANITIZE_XML);
		return $xml;
	}
}

?>