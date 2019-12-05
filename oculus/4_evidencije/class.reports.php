<?php

require("class.documents.php");

define("ELF_TODO_OPEN", 0);
define("ELF_TODO_URGENT", 1);
define("ELF_TODO_CLOSED", 2);

class Reports extends Documents
{
	var $sReportsOpenedTab;
	var $sReportsCompanyName;

	function ReportsMain()
	{
		$this -> ReportsLoadVariables();
		$this -> ReportsTabLoader();
		$this -> ReportsBuildUsualActions();
	}

	function ReportsBuildUsualActions()
	{
		if(isset($_POST["SubmitReport"])) {
			$this -> ReportsAddReport();
		}

		if(isset($_POST["SubmitTodo"])) {
			$this -> ReportsAddTodo();
		}

		if(isset($_POST["SubmitLoko"])) {
			$this -> ReportsAddLoko();
		}

		if(isset($_POST["SubmitTravel"])) {
			$this -> ReportsAddTravel();
		}

		$this -> ReportsLoadReport();
		$this -> ReportsLoadTodo();
		$this -> ReportsLoadLoko();
		$this -> ReportsLoadTravel();
	}

	function ReportsLoadVariables()
	{
		(array) $aDate = getdate();
		$_GET["view"] = (isset($_GET["view"])) ? $_GET["view"] : "reports";
		$_GET["id"] = (isset($_GET["id"])) ? $_GET["id"] : NULL;

		// * Reports
		if($_GET["view"] == "reports")
		{
			$_POST["report"] = (isset($_POST["report"])) ? $_POST["report"] : NULL;
			$_POST["report_project_id"] = (isset($_POST["report_project_id"])) ? $_POST["report_project_id"] : NULL;			
			$_POST["report_start_time"] = (isset($_POST["report_start_time"])) ? $_POST["report_start_time"] : NULL;
			$_POST["report_end_time"] = (isset($_POST["report_end_time"])) ? $_POST["report_end_time"] : NULL;
		}
		// * Todos
		else if($_GET["view"] == "todos")
		{
			$_POST["todo"] = (isset($_POST["todo"])) ? $_POST["todo"] : NULL;
			$_POST["todo_status"] = (isset($_POST["todo_status"])) ? $_POST["todo_status"] : NULL;
			$_POST["todo_start_time"] = (isset($_POST["todo_start_time"])) ? $_POST["todo_start_time"] : NULL;
			$_POST["todo_end_time"] = (isset($_POST["todo_end_time"])) ? $_POST["todo_end_time"] : NULL;
		}
		// * Loko reports
		else if($_GET["view"] == "loko")
		{
			$_POST["loko_report_date_dd"] = (isset($_POST["loko_report_date_dd"])) ? $_POST["loko_report_date_dd"] : $aDate["mday"];
			$_POST["loko_report_date_mm"] = (isset($_POST["loko_report_date_mm"])) ? $_POST["loko_report_date_mm"] : $aDate["mon"];
			$_POST["loko_report_date_yyyy"] = (isset($_POST["loko_report_date_yyyy"])) ? $_POST["loko_report_date_yyyy"] : $aDate["year"];
			$_POST["loko_report_destination"] = (isset($_POST["loko_report_destination"])) ? $_POST["loko_report_destination"] : NULL;
			$_POST["loko_report_purpose"] = (isset($_POST["loko_report_purpose"])) ? $_POST["loko_report_purpose"] : NULL;
			$_POST["loko_report_car"] = (isset($_POST["loko_report_car"])) ? $_POST["loko_report_car"] : NULL;
			$_POST["loko_report_kmh"] = (isset($_POST["loko_report_kmh"])) ? $_POST["loko_report_kmh"] : NULL;
		}
		// * Travel prescription
		else if($_GET["view"] == "travel")
		{
			$this -> DB_Spoji("elf");
			(string) $sQuery = sprintf("SELECT * FROM elf_travel_prescription WHERE travel_company_owner_id = %s", $this -> QuoteSmart($this -> GetCompanyID()));
			$rResult = $this -> DB_Upit($sQuery);
			(int) $nTotal = (mysql_num_rows($rResult) + 1);

			$_POST["travel_number"] = (isset($_POST["travel_number"])) ? $_POST["travel_number"] : $nTotal;
			$_POST["travel_prescription_date_dd"] = (isset($_POST["travel_prescription_date_dd"])) ? $_POST["travel_prescription_date_dd"] : $aDate["mday"];
			$_POST["travel_prescription_date_mm"] = (isset($_POST["travel_prescription_date_mm"])) ? $_POST["travel_prescription_date_mm"] : $aDate["mon"];
			$_POST["travel_prescription_date_yyyy"] = (isset($_POST["travel_prescription_date_yyyy"])) ? $_POST["travel_prescription_date_yyyy"] : $aDate["year"];
			$_POST["travel_traveler"] = (isset($_POST["travel_traveler"])) ? $_POST["travel_traveler"] : NULL;
			$_POST["travel_traveler_occupation"] = (isset($_POST["travel_traveler_occupation"])) ? $_POST["travel_traveler_occupation"] : NULL;
			$_POST["travel_traveler_job"] = (isset($_POST["travel_traveler_job"])) ? $_POST["travel_traveler_job"] : NULL;
			$_POST["travel_date_dd"] = (isset($_POST["travel_date_dd"])) ? $_POST["travel_date_dd"] : NULL;
			$_POST["travel_date_mm"] = (isset($_POST["travel_date_mm"])) ? $_POST["travel_date_mm"] : NULL;
			$_POST["travel_date_yyyy"] = (isset($_POST["travel_date_yyyy"])) ? $_POST["travel_date_yyyy"] : NULL;
			$_POST["travel_destination"] = (isset($_POST["travel_destination"])) ? $_POST["travel_destination"] : NULL;
			$_POST["travel_assignment"] = (isset($_POST["travel_assignment"])) ? $_POST["travel_assignment"] : NULL;
			$_POST["travel_duration"] = (isset($_POST["travel_duration"])) ? $_POST["travel_duration"] : NULL;
			$_POST["travel_vehicle"] = (isset($_POST["travel_vehicle"])) ? $_POST["travel_vehicle"] : NULL;
			$_POST["travel_resources"] = (isset($_POST["travel_resources"])) ? $_POST["travel_resources"] : NULL;
		}
	}

	// * Load reports tabs
	function ReportsTabLoader()
	{
		switch($_GET["view"])
		{
			case "todos": 				$this -> sReportsOpenedTab = "todos.php"; break;
			case "loko": 				$this -> sReportsOpenedTab = "loko.php"; break;
			case "travel": 				$this -> sReportsOpenedTab = "travel_prescription.php"; break;
			case "account": 			$this -> sReportsOpenedTab = "travel_account.php"; break;
			case "reports-archive": 	$this -> sReportsOpenedTab = "reports_archive.php"; break;
			case "todos-archive": 		$this -> sReportsOpenedTab = "todos_archive.php"; break;			
			default: 					$this -> sReportsOpenedTab = "reports.php"; break;
		}
	}

	// * Checks if reports's (or todo's) ID is ok
	function GetReportsValidReportID()
	{
		if(isset($_GET["id"]) && 
		is_numeric($_GET["id"]) && 
		!empty($_GET["id"])) {
			return TRUE;
		}
		return FALSE;
	}

	// * REPORTS

	// * Add or modify your report
	function ReportsAddReport()
	{
		if(!$this -> GetReportsValidReportID() && !empty($_POST["report"]))
		{
			(string) $sQuery = sprintf("INSERT INTO elf_daily_reports (
											id, report, report_project_id, report_employee_id, report_entered_time,
											report_start_time, report_end_time, report_company_owner_id) VALUES 
											('', %s, %s, %s, %s, 
											%s, %s, %s)",
											$this -> QuoteSmart($_POST["report"]), $this -> QuoteSmart($_POST["report_project_id"]), $this -> QuoteSmart($this -> GetEmployeeID()), $this -> QuoteSmart(time()), 
											$this -> QuoteSmart(strtotime($_POST["report_start_time"])), $this -> QuoteSmart(strtotime($_POST["report_end_time"])), $this -> QuoteSmart($this -> GetCompanyID())); 

			$this -> DB_Spoji("elf");
			$this -> DB_Upit($sQuery);
			$this -> DB_Zatvori();
			header("Location: http://".ROOT_SERVER.MAIN_DIR."loader.php?rd=reports&view=reports");
		}
		else
		{
			(string) $sQuery = sprintf("UPDATE elf_daily_reports SET 
											report = %s, report_project_id = %s WHERE id = %s AND report_employee_id = %s AND report_company_owner_id = %s",
											$this -> QuoteSmart($_POST["report"]), $this -> QuoteSmart($_POST["report_project_id"]), $this -> QuoteSmart($_GET["id"]), $this -> QuoteSmart($this -> GetEmployeeID()), $this -> QuoteSmart($this -> GetCompanyID())); 

			$this -> DB_Spoji("elf");
			$this -> DB_Upit($sQuery);
			$this -> DB_Zatvori();
		}
	}

	// * Load report
	function ReportsLoadReport()
	{
		if($this -> GetReportsValidReportID() && $_GET["view"] == "reports")
		{
			$this -> DB_Spoji("elf");
			(string) $sQuery = sprintf("SELECT * FROM elf_daily_reports WHERE id = %s AND report_company_owner_id = %s", $this -> QuoteSmart($_GET["id"]), $this -> QuoteSmart($this -> GetCompanyID()));
			$rResult = $this -> DB_Upit($sQuery);
			(array) $aEmployee = mysql_fetch_array($rResult, MYSQL_ASSOC);

			foreach($aEmployee as $rVarName => $rVarValue) {
				$_POST[$rVarName] = $rVarValue;
			}

			$this -> DB_Zatvori();
			$_POST["report_start_time"] = strftime("%H:%M", $_POST["report_start_time"]);
			$_POST["report_end_time"] = strftime("%H:%M", $_POST["report_end_time"]);
		}
	}

	// * Display our reports
	function ReportsDisplayReports()
	{
		(string) $sReportList = "";
		(string) $sQueryProjectName = "";
		(int) $nCurrentDayStart = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
		(int) $nCurrentDayEnd = mktime(23, 59, 59, date("m"), date("d"), date("Y"));

		(array) $aProjectName = array();
		$this -> DB_Spoji("elf");
		(string) $sQuery = sprintf("SELECT * FROM elf_daily_reports WHERE report_employee_id = %s AND report_company_owner_id = %s AND report_start_time >= %s AND report_end_time <= %s ORDER BY id ASC", $this -> QuoteSmart($this -> GetEmployeeID()), $this -> QuoteSmart($this -> GetCompanyID()), $nCurrentDayStart, $nCurrentDayEnd);
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aReport = mysql_fetch_array($rResult);

		while($aReport)
		{
			// * Get our project's name
			$sQueryProjectName = sprintf("SELECT project_name FROM elf_projects WHERE id = %s AND project_owner_company_id = %s", $this -> QuoteSmart($aReport["report_project_id"]), $this -> QuoteSmart($this -> GetCompanyID()), $this -> QuoteSmart($this -> GetCompanyID()));
			$rResultProjectName = $this -> DB_Upit($sQueryProjectName);
			$aProjectName = mysql_fetch_row($rResultProjectName);

			$sReportList .= sprintf("<div title=\"%s\"><span class=\"edit_report\"><a href=\"loader.php?rd=reports&amp;view=reports&amp;id=%s\" title=\"Edit report\">Edit</a></span>%s %s-%s [%s]</div>\n", $aReport["report"], $aReport["id"], $aProjectName[0], strftime("%H:%M", $aReport["report_start_time"]), strftime("%H:%M", $aReport["report_end_time"]), strftime("%d/%m/%Y", $aReport["report_end_time"]));
			$aReport = mysql_fetch_array($rResult);
		}

		$this -> DB_Zatvori();
		return $sReportList;
	}

	// * Display our archived reports
	function ReportsDisplayArchivedReports()
	{
		(string) $sReportList = "";
		$sReportList .= "<div style=\"padding-left: 10px;\"><input type=\"button\" value=\"Close\" class=\"ie_submit_input\" onclick=\"javascript: CloseReportsArchive('reports');\" /></div>";		
		(string) $sQueryProjectName = "";
		(int) $nCurrentDayStart = mktime(0, 0, 0, date("m"), date("d"), date("Y"));

		(array) $aProjectName = array();
		$this -> DB_Spoji("elf");
		(string) $sQuery = sprintf("SELECT * FROM elf_daily_reports WHERE report_employee_id = %s AND report_company_owner_id = %s AND report_start_time < %s ORDER BY id ASC", $this -> QuoteSmart($this -> GetEmployeeID()), $this -> QuoteSmart($this -> GetCompanyID()), $nCurrentDayStart);
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aReport = mysql_fetch_array($rResult);

		while($aReport)
		{
			// * Get our project's name
			$sQueryProjectName = sprintf("SELECT project_name FROM elf_projects WHERE id = %s AND project_owner_company_id = %s", $this -> QuoteSmart($aReport["report_project_id"]), $this -> QuoteSmart($this -> GetCompanyID()), $this -> QuoteSmart($this -> GetCompanyID()));
			$rResultProjectName = $this -> DB_Upit($sQueryProjectName);
			$aProjectName = mysql_fetch_row($rResultProjectName);

			$sReportList .= sprintf("<div title=\"%s\"><span class=\"edit_report\"><a href=\"loader.php?rd=reports&amp;view=reports&amp;id=%s\" title=\"Edit report\">Edit</a></span>%s %s-%s [%s]</div>\n", $aReport["report"], $aReport["id"], $aProjectName[0], strftime("%H:%M", $aReport["report_start_time"]), strftime("%H:%M", $aReport["report_end_time"]), strftime("%d/%m/%Y", $aReport["report_end_time"]));
			$aReport = mysql_fetch_array($rResult);
		}

		$this -> DB_Zatvori();
		return $sReportList;
	}

	// * TODOS

	// * Close or modify your todo
	function ReportsAddTodo()
	{
		if(!$this -> GetReportsValidReportID() && !empty($_POST["todo"])
		&& $this -> GetIsCompanyAdministrator())
		{
			(string) $sQuery = sprintf("INSERT INTO elf_daily_todos (
											id, todo, todo_employee_id, todo_start_time, todo_end_time,
											todo_added_time, todo_status, todo_company_owner_id) VALUES 
											('', %s, %s, %s, %s, 
											%s, %s, %s)",
											$this -> QuoteSmart($_POST["todo"]), $this -> QuoteSmart($_POST["todo_employee_id"]), $this -> QuoteSmart(strtotime($_POST["todo_start_time"])), $this -> QuoteSmart(strtotime($_POST["todo_end_time"])), 
											$this -> QuoteSmart(time()), $this -> QuoteSmart($_POST["todo_status"]), $this -> QuoteSmart($this -> GetCompanyID()));

			$this -> DB_Spoji("elf");
			$this -> DB_Upit($sQuery);
			$this -> DB_Zatvori();
			header("Location: http://".ROOT_SERVER.MAIN_DIR."loader.php?rd=reports&view=todos");
		}
		else
		{
			(string) $sQuery = sprintf("UPDATE elf_daily_todos SET 
											todo = %s, todo_start_time = %s, todo_end_time = %s, todo_status = %s WHERE id = %s AND todo_employee_id = %s AND todo_company_owner_id = %s",
											 $this -> QuoteSmart($_POST["todo"]), $this -> QuoteSmart(strtotime($_POST["todo_start_time"])), $this -> QuoteSmart(strtotime($_POST["todo_end_time"])), $this -> QuoteSmart($_POST["todo_status"]), $this -> QuoteSmart($_GET["id"]), $this -> QuoteSmart($this -> GetEmployeeID()), $this -> QuoteSmart($this -> GetCompanyID())); 

			$this -> DB_Spoji("elf");
			$this -> DB_Upit($sQuery);
			$this -> DB_Zatvori();
		}
	}

	// * Load Todo
	function ReportsLoadTodo()
	{
		if($this -> GetReportsValidReportID() && $_GET["view"] == "todos")
		{
			$this -> DB_Spoji("elf");
			(string) $sQuery = sprintf("SELECT * FROM elf_daily_todos WHERE id = %s AND todo_company_owner_id = %s", $this -> QuoteSmart($_GET["id"]), $this -> QuoteSmart($this -> GetCompanyID()));
			$rResult = $this -> DB_Upit($sQuery);
			(array) $aEmployee = mysql_fetch_array($rResult, MYSQL_ASSOC);

			foreach($aEmployee as $rVarName => $rVarValue) {
				$_POST[$rVarName] = $rVarValue;
			}

			$this -> DB_Zatvori();
			$_POST["todo_start_time"] = strftime("%H:%M", $_POST["todo_start_time"]);
			$_POST["todo_end_time"] = strftime("%H:%M", $_POST["todo_end_time"]);
		}
	}

	// * Return the total number of opened todos
	function ReportsGetTotalTodos()
	{
		$this -> DB_Spoji("elf");
		(string) $sQuery = sprintf("SELECT id FROM elf_daily_todos WHERE todo_employee_id = %s AND todo_status != %s AND todo_company_owner_id = %s", $this -> QuoteSmart($this -> GetEmployeeID()), $this -> QuoteSmart(ELF_TODO_CLOSED), $this -> QuoteSmart($this -> GetCompanyID()));
		$rResult = $this -> DB_Upit($sQuery);
		(int) $nTotal = mysql_num_rows($rResult);
		$this -> DB_Zatvori();
		return $nTotal;
	}

	// * Determine if we should notify the user about new todo(s)
	function _ReportsGetNewTodos($nTotalTodos)
	{
		$_GET["current_todos"] = (isset($_GET["current_todos"])) ? $_GET["current_todos"] : "";

		if($_GET["current_todos"] != "" && $_GET["current_todos"] != $nTotalTodos) {
			return "1";
		}
		return "0";
	}

	// * Display todos, order them by id
	function ReportsDisplayTodos()
	{
		(int) $i = 0;
		(string) $sTodoList = "";
		(string) $sColor = "";
		(int) $nCurrentDayStart = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
		(int) $nCurrentDayEnd = mktime(23, 59, 59, date("m"), date("d"), date("Y"));

		$this -> DB_Spoji("elf");

		(string) $sQuery = sprintf("SELECT * FROM elf_daily_todos WHERE todo_employee_id = %s AND todo_company_owner_id = %s AND todo_added_time >= %s AND todo_added_time <= %s ORDER BY id DESC", $this -> QuoteSmart($this -> GetEmployeeID()), $this -> QuoteSmart($this -> GetCompanyID()), $nCurrentDayStart, $nCurrentDayEnd);
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aTodo = mysql_fetch_array($rResult);

		while($aTodo)
		{
			$sColor = (gettype($i / 2) == "integer") ? "ActiveCaption" : "transparent";
			$sTodoList .= sprintf("<div class=\"%s\" style=\"border: thin solid $sColor !important;\" title=\"%s\"><span class=\"edit_todo\"><a href=\"loader.php?rd=reports&amp;view=todos&amp;id=%s\" title=\"Edit todo\">Edit</a></span>%s</div>", $this -> _ReportsTodoColor($aTodo["todo_added_time"], $aTodo["todo_status"]), $aTodo["todo"], $aTodo["id"], substr($aTodo["todo"], 0, 15)."...");
			$aTodo = mysql_fetch_array($rResult);
			$i ++;
		}

		$this -> DB_Zatvori();
		return $sTodoList;
	}

	// * Display archived todos, order them by added date
	function ReportsDisplayArchivedTodos()
	{
		(int) $i = 0;
		(string) $sTodoList = "";
		$sTodoList .= "<div style=\"padding-left: 10px;\"><input type=\"button\" value=\"Close\" class=\"ie_submit_input\" onclick=\"javascript: CloseReportsArchive('todos');\" /></div>";
		(string) $sColor = "";
		(int) $nCurrentDayStart = mktime(0, 0, 0, date("m"), date("d"), date("Y"));

		$this -> DB_Spoji("elf");

		(string) $sQuery = sprintf("SELECT * FROM elf_daily_todos WHERE todo_employee_id = %s AND todo_company_owner_id = %s AND todo_added_time < %s ORDER BY todo_added_time DESC", $this -> QuoteSmart($this -> GetEmployeeID()), $this -> QuoteSmart($this -> GetCompanyID()), $nCurrentDayStart);
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aTodo = mysql_fetch_array($rResult);

		while($aTodo)
		{
			$sColor = (gettype($i / 2) == "integer") ? "ActiveCaption" : "transparent";
			$sTodoList .= sprintf("<div class=\"%s\" style=\"border: thin solid $sColor !important;\" title=\"%s\"><span class=\"edit_todo\"><a href=\"loader.php?rd=reports&amp;view=todos&amp;id=%s\" title=\"Edit todo\">Edit</a></span>[%s] %s</div>", $this -> _ReportsTodoColor($aTodo["todo_added_time"], $aTodo["todo_status"]), $aTodo["todo"], $aTodo["id"], substr($aTodo["todo"], 0, 15)."...", strftime("%d/%m/%Y", $aTodo["todo_added_time"]));
			$aTodo = mysql_fetch_array($rResult);
			$i ++;
		}

		$this -> DB_Zatvori();
		return $sTodoList;
	}

	// * Determine our todo's color
	function _ReportsTodoColor($nEnteredTime, $nStatus)
	{
		// * Urgent
		if($nStatus == ELF_TODO_URGENT) {
			return "todo_urgent";
		}
		// * Closed todo
		else if($nStatus == ELF_TODO_CLOSED) {
			return "todo_closed";
		}
		// * New todo
		else if((time() - $nEnteredTime) < 3600) {
			return "todo_new";			
		}
		// * Normal
		else {
			return "todo_normal";
		}
		return NULL;
	}

	function ReportsBuildTodoStatusList()
	{
		(string) $sTodoStatus = "";
		(string) $sOpenSelected = "";
		(string) $sUrgentSelected = "";
		(string) $sClosedSelected = "";

		$this -> DB_Spoji("elf");
		(string) $sQuery = sprintf("SELECT todo_status FROM elf_daily_todos WHERE id = %s AND todo_company_owner_id = %s", $this -> QuoteSmart($_GET["id"]), $this -> QuoteSmart($this -> GetCompanyID()));
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aTodoStatus = mysql_fetch_array($rResult, MYSQL_ASSOC);

		(string) $sOpenSelected = ($aTodoStatus["todo_status"] == ELF_TODO_OPEN) ? "selected=\"selected\"" : "";
		(string) $sUrgentSelected = ($aTodoStatus["todo_status"] == ELF_TODO_URGENT) ? "selected=\"selected\"" : "";
		(string) $sClosedSelected = ($aTodoStatus["todo_status"] == ELF_TODO_CLOSED) ? "selected=\"selected\"" : "";

		$sTodoStatus = sprintf("
		<option value=\"%s\" %s>Open</option>
		<option value=\"%s\" %s>Urgent</option>
		<option value=\"%s\" %s>Closed</option>", ELF_TODO_OPEN, $sOpenSelected, ELF_TODO_URGENT, $sUrgentSelected, ELF_TODO_CLOSED, $sClosedSelected);

		$this -> DB_Zatvori();
		return $sTodoStatus;
	}

	// * LOKO

	// * Display loko reports, order them by id
	function ReportsDisplayLoko()
	{
		(int) $i = 0;
		(string) $sLokoList = "";
		(string) $sColor = "";

		$this -> DB_Spoji("elf");

		(string) $sQuery = sprintf("SELECT * FROM elf_loko_reports WHERE loko_report_employee_owner_id = %s ORDER BY id ASC", $this -> QuoteSmart($this -> GetEmployeeID()));
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aLoko = mysql_fetch_array($rResult);

		while($aLoko)
		{
			$sColor = (gettype($i / 2) == "integer") ? "ActiveCaption" : "transparent";
			$sLokoList .= sprintf("<div style=\"border: thin solid $sColor !important;\"><span class=\"edit_loko\"><a href=\"loader.php?rd=reports&amp;view=loko&amp;id=%s\" title=\"Edit loko report\">Edit</a></span>%s [%s]</div>", $aLoko["id"], $aLoko["loko_report_destination"], strftime("%d/%m/%Y", $aLoko["loko_report_date"]));
			$aLoko = mysql_fetch_array($rResult);
			$i ++;
		}

		$this -> DB_Zatvori();
		return $sLokoList;
	}

	// * Add or modify loko report
	function ReportsAddLoko()
	{
		if(!$this -> GetReportsValidReportID() && !empty($_POST["loko_report_destination"]))
		{
			(string) $sQuery = sprintf("INSERT INTO elf_loko_reports (
											id, loko_report_date, loko_report_destination, loko_report_purpose, loko_report_car,
											loko_report_kmh, loko_report_employee_owner_id) VALUES 
											('', %s, %s, %s, %s, 
											%s, %s)",
											$this -> QuoteSmart($this -> _ReportsGetLokoDateTimestamp()), $this -> QuoteSmart($_POST["loko_report_destination"]), $this -> QuoteSmart($_POST["loko_report_purpose"]), $this -> QuoteSmart($_POST["loko_report_car"]), 
											$this -> QuoteSmart($_POST["loko_report_kmh"]), $this -> QuoteSmart($this -> GetEmployeeID())); 

			$this -> DB_Spoji("elf");
			$this -> DB_Upit($sQuery);
			$this -> DB_Zatvori();
			header("Location: http://".ROOT_SERVER.MAIN_DIR."loader.php?rd=reports&view=loko");
		}
		else
		{
			(string) $sQuery = sprintf("UPDATE elf_loko_reports SET 
											loko_report_date = %s, loko_report_destination = %s, loko_report_purpose = %s, loko_report_car = %s, loko_report_kmh = %s WHERE id = %s AND loko_report_employee_owner_id = %s",
											$this -> QuoteSmart($this -> _ReportsGetLokoDateTimestamp()), $this -> QuoteSmart($_POST["loko_report_destination"]), $this -> QuoteSmart($_POST["loko_report_purpose"]), $this -> QuoteSmart($_POST["loko_report_car"]), $this -> QuoteSmart($_POST["loko_report_kmh"]), $this -> QuoteSmart($_GET["id"]), $this -> QuoteSmart($this -> GetEmployeeID())); 

			$this -> DB_Spoji("elf");
			$this -> DB_Upit($sQuery);
			$this -> DB_Zatvori();
		}
	}

	// * Load Loko
	function ReportsLoadLoko()
	{
		if($this -> GetReportsValidReportID() && $_GET["view"] == "loko")
		{
			$this -> DB_Spoji("elf");
			(string) $sQuery = sprintf("SELECT * FROM elf_loko_reports WHERE id = %s AND loko_report_employee_owner_id = %s", $this -> QuoteSmart($_GET["id"]), $this -> QuoteSmart($this -> GetEmployeeID()));
			$rResult = $this -> DB_Upit($sQuery);
			(array) $aLoko = mysql_fetch_array($rResult, MYSQL_ASSOC);

			foreach($aLoko as $rVarName => $rVarValue) {
				$_POST[$rVarName] = $rVarValue;
			}

			$this -> DB_Zatvori();
			$this -> _ReportsGetLokoDateFormated($aLoko["loko_report_date"]);
		}
	}

	// * Convert our loko date entries to timestamp, english format only
	function _ReportsGetLokoDateTimestamp() {
		return strtotime(sprintf("%s/%s/%s", $_POST["loko_report_date_mm"], $_POST["loko_report_date_dd"], $_POST["loko_report_date_yyyy"]));
	}

	function _ReportsGetLokoDateFormated($nTimestamp)
	{
		$_POST["loko_report_date_dd"] = strftime("%d", $nTimestamp);
		$_POST["loko_report_date_mm"] = strftime("%m", $nTimestamp);
		$_POST["loko_report_date_yyyy"] = strftime("%Y", $nTimestamp);
	}

	// * TRAVEL PRESCRIPTIONS

	// * Add or modify your travel prescription
	function ReportsAddTravel()
	{
		if(!$this -> GetReportsValidReportID())
		{
			(string) $sQuery = sprintf("INSERT INTO elf_travel_prescription (
											id, 
											travel_number, travel_prescription_date, travel_traveler, travel_traveler_occupation, travel_traveler_job, 
											travel_date, travel_destination, travel_assignment, travel_duration, travel_vehicle, 
											travel_resources, travel_company_owner_id) VALUES 
											('', 
											%s, %s, %s, %s, %s,
											%s, %s, %s, %s, %s,
											%s, %s)",
											$this -> QuoteSmart($_POST["travel_number"]), $this -> QuoteSmart($this -> _ReportsGetTravelDateTimestamp()), $this -> QuoteSmart($_POST["travel_traveler"]), $this -> QuoteSmart($_POST["travel_traveler_occupation"]), $this -> QuoteSmart($_POST["travel_traveler_job"]), 
											$this -> QuoteSmart($this -> _ReportsGetTravelDateTimestampB()), $this -> QuoteSmart($_POST["travel_destination"]), $this -> QuoteSmart($_POST["travel_assignment"]), $this -> QuoteSmart($_POST["travel_duration"]), $this -> QuoteSmart($_POST["travel_vehicle"]), 
											$this -> QuoteSmart($_POST["travel_resources"]), $this -> QuoteSmart($this -> GetCompanyID()));

			$this -> DB_Spoji("elf");
			$this -> DB_Upit($sQuery);
			$this -> DB_Zatvori();
			header("Location: http://".ROOT_SERVER.MAIN_DIR."loader.php?rd=reports&view=travel");
		}
		else
		{
			(string) $sQuery = sprintf("UPDATE elf_travel_prescription SET 
											travel_number = %s, travel_prescription_date = %s, travel_traveler = %s, travel_traveler_occupation = %s, travel_traveler_job = %s, 
											travel_date = %s, travel_destination = %s, travel_assignment = %s, travel_duration = %s, travel_vehicle = %s, 
											travel_resources = %s WHERE id = %s AND travel_company_owner_id = %s",
											$this -> QuoteSmart($_POST["travel_number"]), $this -> QuoteSmart($this -> _ReportsGetTravelDateTimestamp()), $this -> QuoteSmart($_POST["travel_traveler"]), $this -> QuoteSmart($_POST["travel_traveler_occupation"]), $this -> QuoteSmart($_POST["travel_traveler_job"]), 
											$this -> QuoteSmart($this -> _ReportsGetTravelDateTimestampB()), $this -> QuoteSmart($_POST["travel_destination"]), $this -> QuoteSmart($_POST["travel_assignment"]), $this -> QuoteSmart($_POST["travel_duration"]), $this -> QuoteSmart($_POST["travel_vehicle"]), 
											$this -> QuoteSmart($_POST["travel_resources"]), $this -> QuoteSmart($_GET["id"]), $this -> QuoteSmart($this -> GetCompanyID()));

			$this -> DB_Spoji("elf");
			$this -> DB_Upit($sQuery);
			$this -> DB_Zatvori();
		}
	}

	// * Load report
	function ReportsLoadTravel()
	{
		if($this -> GetReportsValidReportID() && $_GET["view"] == "travel")
		{
			$this -> DB_Spoji("elf");
			(string) $sQuery = sprintf("SELECT * FROM elf_travel_prescription WHERE id = %s AND travel_company_owner_id = %s", $this -> QuoteSmart($_GET["id"]), $this -> QuoteSmart($this -> GetCompanyID()));
			$rResult = $this -> DB_Upit($sQuery);
			(array) $aEmployee = mysql_fetch_array($rResult, MYSQL_ASSOC);
			(int) $nTotal = mysql_num_rows($rResult);

			foreach($aEmployee as $rVarName => $rVarValue) {
				$_POST[$rVarName] = $rVarValue;
			}

			$this -> DB_Zatvori();
			$this -> _ReportsGetTravelDateFormated($_POST["travel_prescription_date"]);
			$this -> _ReportsGetTravelDateFormatedB($_POST["travel_date"]);
			$_POST["travel_number"] = $nTotal;
		}
	}

	// * Convert our travel date entries to timestamp, english format only
	function _ReportsGetTravelDateTimestamp() {
		return strtotime(sprintf("%s/%s/%s", $_POST["travel_prescription_date_mm"], $_POST["travel_prescription_date_dd"], $_POST["travel_prescription_date_yyyy"]));
	}

	// * Convert our travel date entries to timestamp, english format only
	function _ReportsGetTravelDateTimestampB() {
		return strtotime(sprintf("%s/%s/%s", $_POST["travel_date_mm"], $_POST["travel_date_dd"], $_POST["travel_date_yyyy"]));
	}

	function _ReportsGetTravelDateFormated($nTimestamp)
	{
		$_POST["travel_prescription_date_dd"] = strftime("%d", $nTimestamp);
		$_POST["travel_prescription_date_mm"] = strftime("%m", $nTimestamp);
		$_POST["travel_prescription_date_yyyy"] = strftime("%Y", $nTimestamp);
	}

	function _ReportsGetTravelDateFormatedB($nTimestamp)
	{
		$_POST["travel_date_dd"] = strftime("%d", $nTimestamp);
		$_POST["travel_date_mm"] = strftime("%m", $nTimestamp);
		$_POST["travel_date_yyyy"] = strftime("%Y", $nTimestamp);
	}

	function ReportsGenerateTravelPrescriptionsList()
	{
		(int) $i = 0;
		(string) $sTravelList = "";
		(string) $sColor = "";
		(string) $sEmployeeName = "";

		$this -> DB_Spoji("elf");

		(string) $sQuery = sprintf("SELECT * FROM elf_travel_prescription WHERE travel_company_owner_id = %s ORDER BY id ASC", $this -> QuoteSmart($this -> GetCompanyID()));
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aTravel = mysql_fetch_array($rResult);

		while($aTravel)
		{
			$rResultEmployee = $this -> DB_Upit(sprintf("SELECT employee_first_name, employee_last_name FROM elf_company_employees WHERE employee_company_id = %s AND id = %s", $this -> QuoteSmart($this -> GetCompanyID()), $this -> QuoteSmart($aTravel["travel_traveler"])));
			(array) $aEmployee = mysql_fetch_array($rResultEmployee);

			$sEmployeeName = sprintf("%s %s", $aEmployee["employee_first_name"], $aEmployee["employee_last_name"]);

			$sColor = (gettype($i / 2) == "integer") ? "ActiveCaption" : "transparent";
			$sTravelList .= sprintf("
									<div style=\"border: thin solid $sColor !important;\">
										<span class=\"edit_loko\">
											<a href=\"loader.php?rd=reports&amp;view=travel&amp;id=%s\" title=\"Edit travel prescription\">Edit</a>
										</span>%s [%s]
									</div>", $aTravel["id"], $sEmployeeName, strftime("%d/%m/%Y", $aTravel["travel_prescription_date"]));
			$aTravel = mysql_fetch_array($rResult);
			$i ++;
		}

		$this -> DB_Zatvori();
		return $sTravelList;
	}
}

?>