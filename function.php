<?php
 
function Backup() {

  $sql_db_host = 'localhost';
  $sql_db_user = 'root';
  $sql_db_pass = '';
  $sql_db_name = 'seubanco';
  $tables = false;
  $backup_name = false;


  $mysqli = new mysqli($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name);
  $mysqli->select_db($sql_db_name);
  $mysqli->query("SET NAMES 'utf-8'");
  $queryTables = $mysqli->query('SHOW TABLES');


  while ($row = $queryTables->fetch_row()) {
    $target_tables[] = $row[0];
  }

  if($tables !== false) {
    $target_tables =array_intersect($target_tables, $tables);
  }
	$content = "-- phpMyAdmin SQL Dump
	-- http://www.phpmyadmin.net
	--
	-- Host Connection Info: " . $mysqli->host_info . "
	-- Generation Time: " . date('F d, Y \a\t H:i A ( e )') . "
	-- Server version: " . mysqli_get_server_info($mysqli) . "
	-- PHP Version: " . PHP_VERSION . "
	--\n
	SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";
	SET time_zone = \"+00:00\";\n
	/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
	/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
	/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
	/*!40101 SET NAMES utf8mb4 */;\n\n";
		foreach ($target_tables as $table) {
			$result        = $mysqli->query('SELECT * FROM ' . $table);
			$fields_amount = $result->field_count;
			$rows_num      = $mysqli->affected_rows;
			$res           = $mysqli->query('SHOW CREATE TABLE ' . $table);
			$TableMLine    = $res->fetch_row();
			$content       = (!isset($content) ? '' : $content) . "
	-- ---------------------------------------------------------
	--
	-- Table structure for table : `{$table}`
	--
	-- ---------------------------------------------------------
	\n" . $TableMLine[1] . ";\n";
			for ($i = 0, $st_counter = 0; $i < $fields_amount; $i++, $st_counter = 0) {
				while ($row = $result->fetch_row()) {
					if ($st_counter % 100 == 0 || $st_counter == 0) {
						$content .= "\n--
	-- Dumping data for table `{$table}`
	--\n\nINSERT INTO " . $table . " VALUES";
					}
					$content .= "\n(";
					for ($j = 0; $j < $fields_amount; $j++) {
						$row[$j] = str_replace("\n", "\\n", addslashes($row[$j]));
						if (isset($row[$j])) {
							$content .= '"' . $row[$j] . '"';
						} else {
							$content .= '""';
						}
						if ($j < ($fields_amount - 1)) {
							$content .= ',';
						}
					}
					$content .= ")";
					if ((($st_counter + 1) % 100 == 0 && $st_counter != 0) || $st_counter + 1 == $rows_num) {
						$content .= ";\n";
					} else {
						$content .= ",";
					}
					$st_counter = $st_counter + 1;
				}
			}
			$content .= "";
		}
		$content .= "
		/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
		/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
		/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
		
		if (!file_exists('script_backups/' . date('d-m-Y'))) {
			@mkdir('script_backups/' . date('d-m-Y'), 0777, true);
		}
		if (!file_exists('script_backups/' . date('d-m-Y') . '/' . time())) {
			mkdir('script_backups/' . date('d-m-Y') . '/' . time(), 0777, true);
		}
		if (!file_exists("script_backups/" . date('d-m-Y') . '/' . time() . "/index.html")) {
			$f = @fopen("script_backups/" . date('d-m-Y') . '/' . time() . "/index.html", "a+");
			@fwrite($f, "");
			@fclose($f);
		}
		if (!file_exists('script_backups/.htaccess')) {
			$f = @fopen("script_backups/.htaccess", "a+");
			@fwrite($f, "deny from all\nOptions -Indexes");
			@fclose($f);
		}
		if (!file_exists("script_backups/" . date('d-m-Y') . "/index.html")) {
			$f = @fopen("script_backups/" . date('d-m-Y') . "/index.html", "a+");
			@fwrite($f, "");
			@fclose($f);
		}
		if (!file_exists('script_backups/index.html')) {
			$f = @fopen("script_backups/index.html", "a+");
			@fwrite($f, "");
			@fclose($f);
		}
		
	$folder_name = "script_backups/" . date('d-m-Y') . '/' . time();
    $put         = @file_put_contents($folder_name . '/SQL-Backup-' . time() . '-' . date('d-m-Y') . '.sql', $content);
    if ($put) {
        $rootPath = realpath('./');
        $zip      = new ZipArchive();
        $open     = $zip->open($folder_name . '/Files-Backup-' . time() . '-' . date('d-m-Y') . '.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
        if ($open !== true) {
            return false;
        }
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);
        foreach ($files as $name => $file) {
            if (!preg_match('/\bscript_backups\b/', $file)) {
                if (!$file->isDir()) {
                    $filePath     = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($rootPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
        }
        $zip->close();
        $mysqli->close();
        return true;
    } else {
        return false;
    }


}


?>