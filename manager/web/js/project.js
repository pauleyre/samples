function add_project_fields(num)
{
	var tbl = $('task_table');
	var i;


	for(i=1; i <= num; i++) {

		var lastRow = tbl.rows.length;
		var row = tbl.insertRow(lastRow);

		var cell_01 = row.insertCell(0);
		var el = document.createElement('input');
		el.type = 'text';
		el.name = 'employee_todo[]';
		cell_01.appendChild(el);

		var cell_02 = row.insertCell(1);
		cell_02.setAttribute('style', 'width:50%');
		el = document.createElement('textarea');
		el.name="description_todo[]";
		el.onkeyup = function(){ag(this)};
		el.onblur = function(){ag(this)};
		el.setAttribute('style', 'width:99%');
		el.rows=2;
		cell_02.appendChild(el);

		var cell_03 = row.insertCell(2);
		el = document.createElement('a');
		el.href="#";
		el.innerText = '[početak]';
		cell_03.appendChild(el);

		var cell_04 = row.insertCell(3);
	 	el = document.createElement('a');
		el.href="#";
		el.innerText = '[završetak]';
		cell_04.appendChild(el);
	}
}
