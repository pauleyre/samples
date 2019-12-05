<?php

	function split($file_path, $chunk_size)
	{
		$size = filesize($file_path);

		//find number of full $chunk_size byte portions
		$num_chunks = floor($size / $chunk_size);

		$file_handle = fopen($file_path, 'rb');         //read the file in binary mode

		$chunks = array();

		for($kk = 0; $kk < $num_chunks; $kk++) {
			$chunks[$kk] = basename($file_path) . '.chunk' . ($kk + 1);
			$chunk_handle = fopen($chunks[$kk], 'w');   //open the chunk file for writing

			//write the data to the chunk file 1k at a time
			while((ftell($chunk_handle) + 1024) <= $chunk_size) {
				fwrite($chunk_handle, fread($file_handle, 1024));
			}

			if(($leftover = $chunk_size-ftell($chunk_handle)) > 0 ){
				fwrite($chunk_handle, fread($file_handle, $leftover));
			}
			fclose($chunk_handle);
		}

		if(($leftover = $size - ftell($file_handle)) > 0) {
			$chunks[$num_chunks] = basename($file_path).'.chunk'.($num_chunks + 1);
			$chunk_handle = fopen($chunks[$num_chunks], 'w');

			while(!feof($file_handle)) {
				fwrite($chunk_handle, fread($file_handle, 1024));
			}

			fclose($chunk_handle);
		}

		fclose($file_handle);
		return $chunks;
	}

	function join($what, $where)
	{
		$r = fopen($where, 'ab');
		$i = 1;
		$file = $what . 'chunk' . $i;

		while(is_file($file)) {
			fwrite($r, file_get_contents($file));
			$file = $what . 'chunk' . $i;
			$i++;
		}

		return fclose($r);
	}

?>