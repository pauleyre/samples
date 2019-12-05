	function resize()
	{
		sh_ind();
		var handleSuccess = function(o) {
			if(o.responseText !== undefined) {
				redirect(orbx_site_url + '/?' + __orbicon_ln + '=orbicon/venus&read=expo/' + o.responseText + '/&tools=resize');
			}
			sh_ind();
		}

		var callback =
		{
			success:handleSuccess
		};

		var overwrite = window.confirm('Overwrite original image?');

		var data = new Array();
		data[0] = 'file=' + $('input_image_ref').value;
		data[1] = 'overwrite=' + overwrite;
		data[2] = 'width=' + $('width').value;
		data[3] = 'height=' + $('height').value;
		data[4] = 'unit=' + $('unit').value;
		data[5] = 'tools=resize';

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', crop_script_server_file, callback, data);

	}

	function switch_image_unit()
	{
		var img_unit = $('unit');
		var img_unit_val = img_unit.options[img_unit.selectedIndex].value;
		var width = $('width');
		var height = $('height');
		var current_imageWidth = parseInt(width.value);
		var current_imageHeight = parseInt(height.value);

		if(img_unit_val == 'px') {
			image_width = parseInt(org_imageWidth * (current_imageWidth / 100));
			image_height = parseInt(org_imageHeight * (current_imageHeight / 100));
		}
		else if(img_unit_val == 'percent') {
			image_width = parseInt((current_imageWidth / org_imageWidth) * 100);
			image_height = parseInt((current_imageHeight / org_imageHeight) * 100);
		}
		width.value = image_width;
		height.value = image_height;
	}

	function resize_normalize_inputs(xy)
	{
		var image_height;
		var image_width;
		var my_image = $('current_image');
		var img_unit = $('unit');
		var img_unit_val = img_unit.options[img_unit.selectedIndex].value;
		var width = $('width');
		var height = $('height');
		var proportions = $('proportions');

		var current_imageWidth = parseInt(width.value);
		var current_imageHeight = parseInt(height.value);

		image_width = parseInt(current_imageWidth);
		image_height = parseInt(current_imageHeight);

		if(xy == 'width' && proportions.checked == true) {
			var ratio = (current_imageWidth / org_imageWidth);
			image_height = (img_unit_val != 'px') ? current_imageWidth : Math.round(org_imageHeight * ratio);
		}
		else if(xy == 'height' && proportions.checked == true)
		{
			var ratio = (current_imageHeight / org_imageHeight);
			image_width = (img_unit_val != 'px') ? current_imageHeight : Math.round(org_imageWidth * ratio);
		}

		// calculate live preview for percent
		var times_w = 1;
		var times_h = 1;

		if(img_unit_val != 'px') {
			times_w = (image_width / 100);
			times_h = (image_height / 100);
		}

		if(image_width < 1 || typeof image_width != 'number') {
			image_width = 1;
		}
		if(image_height < 1 || typeof image_height != 'number') {
			image_height = 1;
		}

		width.value = image_width;
		height.value = image_height;
		// live preview
		if(img_unit_val != 'px') {
			var rendered_w_per = parseInt(org_imageWidth * times_w);
			var rendered_h_per = parseInt(org_imageHeight * times_h);

			if(rendered_w_per < 1 || typeof rendered_w_per != 'number') {
				rendered_w_per = 1;
			}
			if(rendered_h_per < 1 || typeof rendered_h_per != 'number') {
				rendered_h_per = 1;
			}

			my_image.style.width = rendered_w_per + 'px';
			my_image.style.height = rendered_h_per + 'px';
		}
		else {
			my_image.style.width = image_width + 'px';
			my_image.style.height = image_height + 'px';
		}
	}

	function sharpen()
	{
		sh_ind();
		var handleSuccess = function(o) {
			if(o.responseText!==undefined) {
				redirect(orbx_site_url + '/?' + __orbicon_ln + '=orbicon/venus&read=expo/' + o.responseText + '/&tools=sharpen');
			}
			sh_ind();
		}

		var callback =
		{
			success:handleSuccess
		};

		var overwrite = window.confirm('Overwrite original image?');

		var data = new Array();
		data[0] = 'file=' + $('input_image_ref').value;
		data[1] = 'overwrite=' + overwrite;
		data[2] = 'tools=sharpen';

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', crop_script_server_file, callback, data);

	}

	function blur_image()
	{
		sh_ind();
		var handleSuccess = function(o) {
			if(o.responseText!==undefined) {
				redirect(orbx_site_url + '/?' + __orbicon_ln + '=orbicon/venus&read=expo/' + o.responseText + '/&tools=blur');
			}
			sh_ind();
		}

		var callback =
		{
			success:handleSuccess
		};

		var overwrite = window.confirm('Overwrite original image?');

		var data = new Array();
		data[0] = 'file=' + $('input_image_ref').value;
		data[1] = 'overwrite=' + overwrite;
		data[2] = 'tools=blur';

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', crop_script_server_file, callback, data);

	}

	function emboss()
	{
		sh_ind();
		var handleSuccess = function(o) {
			if(o.responseText!==undefined)
			{
				redirect(orbx_site_url + '/?' + __orbicon_ln + '=orbicon/venus&read=expo/' + o.responseText + '/&tools=emboss');
			}
			sh_ind();
		}

		var callback =
		{
			success:handleSuccess
		};

		var overwrite = window.confirm('Overwrite original image?');

		var data = new Array();
		data[0] = 'file=' + $('input_image_ref').value;
		data[1] = 'overwrite=' + overwrite;
		data[2] = 'tools=emboss';

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', crop_script_server_file, callback, data);

	}

	function grayscale()
	{
		sh_ind();
		var handleSuccess = function(o) {
			if(o.responseText!==undefined)
			{
				redirect(orbx_site_url + '/?' + __orbicon_ln + '=orbicon/venus&read=expo/' + o.responseText + '/&tools=grayscale');
			}
			sh_ind();
		}

		var callback =
		{
			success:handleSuccess
		};

		var overwrite = window.confirm('Overwrite original image?');

		var data = new Array();
		data[0] = 'file=' + $('input_image_ref').value;
		data[1] = 'overwrite=' + overwrite;
		data[2] = 'tools=grayscale';

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', crop_script_server_file, callback, data);

	}

	function edge_detect()
	{
		sh_ind();
		var handleSuccess = function(o) {
			if(o.responseText!==undefined)
			{
				redirect(orbx_site_url + '/?' + __orbicon_ln + '=orbicon/venus&read=expo/' + o.responseText + '/&tools=edge_detect');
			}
			sh_ind();
		}

		var callback =
		{
			success:handleSuccess
		};

		var overwrite = window.confirm('Overwrite original image?');

		var data = new Array();
		data[0] = 'file=' + $('input_image_ref').value;
		data[1] = 'overwrite=' + overwrite;
		data[2] = 'tools=edge_detect';

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', crop_script_server_file, callback, data);

	}

	function edge_enhance()
	{
		sh_ind();
		var handleSuccess = function(o) {
			if(o.responseText!==undefined)
			{
				redirect(orbx_site_url + '/?' + __orbicon_ln + '=orbicon/venus&read=expo/' + o.responseText + '/&tools=edge_enhance');
			}
			sh_ind();
		}

		var callback =
		{
			success:handleSuccess
		};

		var overwrite = window.confirm('Overwrite original image?');

		var data = new Array();
		data[0] = 'file=' + $('input_image_ref').value;
		data[1] = 'overwrite=' + overwrite;
		data[2] = 'tools=edge_enhance';

		data = data.join('&');

		YAHOO.util.Connect.asyncRequest('POST', crop_script_server_file, callback, data);
	}