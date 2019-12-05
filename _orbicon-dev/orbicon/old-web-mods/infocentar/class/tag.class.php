<?php

class Tag
{
	var $data;
	var $lang;
	var $dbconn;



	function Tag($data_array = NULL)
	{
		// * do some setup here
		global $dbc;
		global $orbicon_x;

		$this->lang		= $orbicon_x->ptr;
		$this->data 	= $data_array;
		$this->dbconn	= $dbc->_db;

	}

	function set_new_tag()
	{
		$sql = sprintf('INSERT INTO
							orbx_mod_ic_tags

							(tag_title,
							lang) VALUES (%s, %s)',
						$this->dbconn->quote($this->data['tag_title']),
						$this->dbconn->quote($this->lang));

		$this->dbconn->query($sql);
	}

	function edit_tag()
	{
		$sql = sprintf('UPDATE
							orbx_mod_ic_tags
						SET
							tag_title = %s
						WHERE
							id = %s',
						$this->dbconn->quote($this->data['tag_title']),
						$this->dbconn->quote($this->data['id']));

		$this->dbconn->query($sql);
	}

	function get_tag($id)
	{
		$sql = sprintf('SELECT * FROM
							orbx_mod_ic_tags
						WHERE
							id = %s',
						$this->dbconn->quote($id));

		$resource = $this->dbconn->query($sql);

		return $resource;
	}

	function get_tag_list()
	{
		$sql = sprintf('SELECT * FROM
							orbx_mod_ic_tags
						WHERE
							lang = %s
						ORDER BY tag_title',
						$this->dbconn->quote($this->lang));

		$resource = $this->dbconn->query($sql);

		return $resource;
	}

	function get_handle_tags($id)
	{
		$sql = sprintf('SELECT * FROM orbx_mod_ic_tag_handler WHERE qid = %s',
						$this->dbconn->quote($id));

		$resource = $this->dbconn->query($sql);
		$result = $this->dbconn->fetch_array($resource);

		return $result;
	}

	function build_tag_cloud($tags=NULL)
	{
		$min_font_size = 10;
		$max_font_size = 24;

		// fetch all existing tags
		$tag_list = $this->get_tag_list();
		while($tag_item = $this->dbconn->fetch_array($tag_list)){

			$tag_array[$tag_item['id']]['title'] = $tag_item['tag_title'];

			// fetch tag assignement number
			$tag_count = $this->count_tag($tag_item['id']);

			// assign it to global array
			$tag_array[$tag_item['id']]['total'] = $tag_count['total'];

		}

		$total_assignements = $this->count_total_tags();
		/*echo '<pre>';
		var_dump($tag_array);
echo '</pre>';*/
		// write tags
		$tagCloud .= '<p id="tag_cloud_holder">';

		foreach ($tag_array as $key=>$val){

			// let's find percentage of tag appereal
			$tag_percent = $val['total'] / $total_assignements;

			$font_range = $max_font_size - $min_font_size;

			$font_percent = round(($tag_percent * 100 ) * $font_range / $min_font_size, 2);

			$font_size = round(((ceil($font_percent * $font_range) + $min_font_size) / 100), 1) + $min_font_size;



			$tagCloud.= '<span style="font-size: '.$font_size.'px !important;"><a href="?'.$this->lang.'=mod.infocentar&amp;sp=tag&amp;tag='.$val['title'].'" title="'.$val['title'].' ('.$val['total'].')">
							'.$val['title'].'</a></span> ';

		}

		// close tag cloud holder
		$tagCloud.= '</p>';

		return $tagCloud;


	}

	function count_tag($tagid)
	{
		$sql = sprintf('SELECT
							COUNT(id) AS total
						FROM
							orbx_mod_ic_tag_handler
						WHERE
							tagid = %s
						GROUP BY
							tagid',
						$this->dbconn->quote($tagid));

		$resource = $this->dbconn->query($sql);
		$result = $this->dbconn->fetch_array($resource);

		return $result;
	}

	function count_total_tags()
	{
		$sql = sprintf('SELECT
							COUNT(id) AS total
						FROM
							orbx_mod_ic_tag_handler',
						$this->dbconn->quote($tagid));

		$resource = $this->dbconn->query($sql);
		$result = $this->dbconn->fetch_array($resource);

		return $result['total'];
	}


	function removeTag($id)
	{
		$sql = sprintf('DELETE FROM orbx_mod_ic_tags WHERE id = %s',
						$this->dbconn->quote($id));

		return $this->dbconn->query($sql);
	}

}


?>