<?php
# ======================================================================== #
#
#  Title      [PHP] FileUploader
#  Author:    Innostudio.de
#  Website:   http://innostudio.de/fileuploader/
#  Version:   1.0.0.4
#  License:   http://innostudio.de/fileuploader/documentation/#license
#  Date:      21-Apr-2017
#  Purpose:   Validate, Remove and Upload files to server.
#  Information: Don't forget to check the options file_uploads, upload_max_filesize, max_file_uploads and post_max_size in the php.ini
#
# ======================================================================== #

class FileUploader {
    private $default_options = array(
        'limit' => null,
        'maxSize' => null,
		'fileMaxSize' => null,
        'extensions' => null,
        'required' => false,
        'uploadDir' => '../docs/',
        'title' => array('auto', 12),
		'replace' => false,
        'listInput' => true,
		'files' => array()
    );
	private $field = null;
	private $options = null;
	
	/**
     * __construct method
     *
	 * @public
     * @param $name {$_FILES key}
     * @param $options {null, Array}
     */
	public function __construct($name, $options = null) {
        return $this->initialize($name, $options);
    }
    
	/**
     * initialize method
     * initialize the plugin
     *
	 * @private
     * @param $name {String} Input name
     * @param $options {null, Array}
     */
    private function initialize($name, $options) {
		// merge options
		$this->options = $this->default_options;
		if ($options)
			$this->options = array_merge($this->options, $options);
		if (!is_array($this->options['files']))
			$this->options['files'] = array();
		
        // create field array
        $this->field = array(
            'name' => $name,
            'input' => null,
			'listInput' => $this->getListInputFiles($name)
        );
        
        if (isset($_FILES[$name])) {
            // set field input
			$this->field['input'] = $_FILES[$name];
            
            // tranform an no-multiple input to multiple
            // made only to simplify the next uploading steps
            if (!is_array($this->field['input']['name'])) {
           	    $this->field['input'] = array_merge($this->field['input'], array(
                    "name" => array($this->field['input']['name']),
                    "tmp_name" => array($this->field['input']['tmp_name']),
                    "type" => array($this->field['input']['type']),
                    "error" => array($this->field['input']['error']),
                    "size" => array($this->field['input']['size'])
                ));
            }
            
            // remove empty filenames
            // only for addMore option
            foreach($this->field['input']['name'] as $key=>$value){ if (empty($value)) { unset($this->field['input']['name'][$key]); unset($this->field['input']['type'][$key]); unset($this->field['input']['tmp_name'][$key]); unset($this->field['input']['error'][$key]); unset($this->field['input']['size'][$key]); } }
            
            // set field length (files count)
            $this->field['count'] = count($this->field['input']['name']);
			return true;
        } else {
			return false;
		}
    }
	
	/**
     * upload method
     * Call the uploadFiles method
     *
	 * @public
	 * @return {Array}
     */
    public function upload() {
    	return $this->uploadFiles();
    }
	
	/**
     * getFileList method
     * Get the list of the appended and uploaded files
     *
	 * @public
	 * @param @customKey {null, String} File attrbite that should be in the list
	 * @return {null, Array}
     */
	public function getFileList($customKey = null) {
		$result = null;
		
		if($customKey != null) {
			$result = array();
			foreach($this->options['files'] as $key=>$value) {
				$attribute = $this->getFileAttribute($value, $customKey);
				$result[] = $attribute ? $attribute : $value['file'];
			}
		} else {
			$result = $this->options['files'];
		}
		
		return $result;
	}
	
	/**
     * getRemovedFiles method
     * Get removed files as array
     *
	 * @public
	 * @param $customKey {String} The file attribute which is also defined in listInput element
	 * @return {Array}
     */
	public function getRemovedFiles($customKey = 'file') {
		$removedFiles = array();

		if (is_array($this->field['listInput']) && is_array($this->options['files'])) {
			foreach($this->options['files'] as $key=>$value) {
				if (!in_array($this->getFileAttribute($value, $customKey), $this->field['listInput']) && (!isset($value['uploaded']) || !$value['uploaded'])) {
					$removedFiles[] = $value;
					unset($this->options['files'][$key]);
				}
			}
		}
		
		if (is_array($this->options['files']))
			$this->options['files'] = array_values($this->options['files']);
			
		return $removedFiles;
	}
	
	/**
     * getListInput method
     * Get the listInput value as null or array
     *
	 * @public
	 * @return {null, Array}
     */
	public function getListInput() {
		return $this->field['listInput'];
	}
	
	/**
     * generateInput method
     * Generate a string with HTML input
     *
	 * @public
	 * @return {String}
     */
	public function generateInput() {
		$attributes = array();
		
		// process options
		foreach(array_merge(array('name'=>$this->field['name']), $this->options) as $key=>$value) {
			if ($value) {
				switch($key) {
					case 'limit':
					case 'maxSize':
					case 'fileMaxSize':
						$attributes['data-fileuploader-' . $key] = $value;
						break;
					case 'listInput':
						$attributes['data-fileuploader-' . $key] = is_bool($value) ? var_export($value, true) : $value;
						break;
					case 'extensions':
						$attributes['data-fileuploader-' . $key] = implode(',', $value);
						break;
					case 'name':
						$attributes[$key] = $value;
						break;
					case 'required':
						$attributes[$key] = '';
						break;
					case 'files':
						$value = array_values($value);
						$attributes['data-fileuploader-' . $key] = json_encode($value);
						break;
				}
			}
		}
		
		// generate input attributes
		$dataAttributes = array_map(function($value, $key) {
			return $key . "='" . (str_replace("'", '"', $value)) . "'";
		}, array_values($attributes), array_keys($attributes));
		
		return '<input type="file"' . implode(' ', $dataAttributes) . '>';
	}
	
	/**
     * uploadFiles method
     * Process and upload the files
     *
	 * @private
     * @return {null, Array}
     */
	private function uploadFiles() {
		$data = array(
			"hasWarnings" => false,
			"isSuccess" => false,
			"warnings" => array(),
			"files" => array()
		);
        $listInput = $this->field['listInput'];
		
		if ($this->field['input']) {
			// validate ini settings and some generally options
			$validate = $this->validate();
			$data['isSuccess'] = true;
			
			if ($validate === true) {
                // process the files
				for($i = 0; $i < count($this->field['input']['name']); $i++) {
					$file = array(
						'name' => $this->field['input']['name'][$i],
						'tmp_name' => $this->field['input']['tmp_name'][$i],
						'type' => $this->field['input']['type'][$i],
						'error' => $this->field['input']['error'][$i],
						'size' => $this->field['input']['size'][$i]
					);

					$metas = array();
                    $metas['tmp_name'] = $file['tmp_name'];
					$metas['extension'] = substr(strrchr($file['name'], "."), 1);
					$metas['type'] = $file['type'];
					$metas['old_name'] = $file['name'];
					$metas['old_title'] = substr($metas['old_name'], 0, (strlen($metas['extension']) > 0 ? -(strlen($metas['extension'])+1) : strlen($metas['old_name'])));
					$metas['size'] = $file['size'];
					$metas['size2'] = $this->formatSize($file['size']);
					$metas['name'] = $this->generateFileName($this->options['title'], array(
						'title' => $metas['old_title'],
						'size' => $metas['size'],
						'extension' => $metas['extension']
					));
					$metas['title'] = substr($metas['name'], 0, (strlen($metas['extension']) > 0 ? -(strlen($metas['extension'])+1) : strlen($metas['name'])));
					$metas['file'] = str_replace(getcwd() . '/', '', $this->options['uploadDir']) . $metas['name'];
					$metas['replaced'] = file_exists($metas['file']);
					$metas['date'] = date('r');
					ksort($metas);

					// validate file
					$validateFile = $this->validate(array_merge($metas, array('index' => $i, 'tmp' => $file['tmp_name'])));

					// check if file is in listInput
                    $listInputName = '0:/' . $metas['old_name'];
					$fileInList = $listInput === null || in_array($listInputName, $listInput);

					// add file to memoery
					if ($validateFile === true) {
						if ($fileInList) {
							$data['files'][] = $metas;
                            
                            if($listInput)
                                unset($listInput[array_search($listInputName, $listInput)]);
						}
					} else {
						$data['isSuccess'] = false;
						$data['hasWarnings'] = true;
						$data['warnings'][] = $validateFile;
						$data['files'] = array();

						break;
					}
				}
                
                // upload the files
                if (!$data['hasWarnings']) {
                    foreach($data['files'] as $key => $file) {
                        if (move_uploaded_file($file['tmp_name'], $file['file'])) {
                            unset($data['files'][$key]['tmp_name']);
							$data['files'][$key]['uploaded'] = true;
							$this->options['files'][] = $data['files'][$key];
                        } else {
                            unset($data['files'][$key]);
                        }
                    }
                }
			} else {
				$data['isSuccess'] = false;
				$data['hasWarnings'] = true;
				$data['warnings'][] = $validate;
			}
		} else {
			if($this->options['required'] && (isset($_SERVER) && strtolower($_SERVER['REQUEST_METHOD']) == "post")) {
				$data['hasWarnings'] = true;
				$data['warnings'][] = $this->codeToMessage('required_and_no_file');
			}
				
		}

		return $data;
	}
    
    /**
     * validation method
     * Check ini settings, field and files
     *
	 * @private
	 * @param $file {Array} File metas
     * @return {boolean, String}
     */
    private function validate($file = null) {		
        if ($file == null) {
			// check ini settings and some generally options
            $ini = array(
				(boolean) ini_get('file_uploads'),
				(int) ini_get('upload_max_filesize'),
				(int) ini_get('post_max_size'),
				(int) ini_get('max_file_uploads'),
				(int) ini_get('memory_limit')
			);
			
            if (!$ini[0])
				return $this->codeToMessage('file_uploads');
            if ($this->options['required'] && (isset($_SERVER) && strtolower($_SERVER['REQUEST_METHOD']) == "post") && $this->field['count'] + count($this->options['files']) == 0)
				return $this->codeToMessage('required_and_no_file');
            if (($this->options['limit'] && $this->field['count'] + count($this->options['files']) > $this->options['limit']) || ($ini[3] != 0 && ($this->field['count']) > $ini[3]))
				return $this->codeToMessage('max_number_of_files');
            if (!file_exists($this->options['uploadDir']) && !is_writable($this->options['uploadDir']))
				return $this->codeToMessage('invalid_folder_path');

            $total_size = 0; foreach($this->field['input']['size'] as $key=>$value){ $total_size += $value; } $total_size = $total_size/1000000;
            if ($ini[2] != 0 && $total_size > $ini[2])
				return $this->codeToMessage('post_max_size');
			if ($this->options['maxSize'] && $total_size > $this->options['maxSize'])
				return $this->codeToMessage('max_files_size');
        } else {
			// check file
            if ($this->field['input']['error'][$file['index']] > 0)
				return $this->codeToMessage($this->field['error'][$file['index']], $file);
            if ($this->options['extensions'] && (!in_array($file['extension'], $this->options['extensions']) && !in_array($file['type'], $this->options['extensions'])))
				return $this->codeToMessage('accepted_file_types', $file);
			if ($this->options['fileMaxSize'] && $file['size']/1000000 > $this->options['fileMaxSize'])
				return $this->codeToMessage('max_file_size', $file);
            if ($this->options['maxSize'] && $file['size']/1000000 > $this->options['maxSize'])
				return $this->codeToMessage('max_file_size', $file);
        }

        return true;
	}
	
	/**
     * getListInputFiles method
     * Get value from listInput
     *
	 * @private
     * @param $name {String} FileUploader $_FILES name
     * @return {null, Array}
     */
	private function getListInputFiles($name = null) {
		$inputName = 'fileuploader-list-' . ($name ? $name : $this->field['name']);
		if (is_string($this->options['listInput']))
			$inputName = $this->options['listInput'];
		
		if (isset($_POST[$inputName]) && $this->isJSON($_POST[$inputName])) {
			$fileList = json_decode($_POST[$inputName], true);
			
			return $fileList;
		}
		
		return null;
	}
    
    /**
     * codeToMessage method
     * Translate a warning code into text
     *
	 * @private
     * @param $code {Number, String}
	 * @param $file {null, Array}
     * @return {String}
     */
    private function codeToMessage($code, $file = null) {
        $message = null;
		
	    switch ($code) {
	        case UPLOAD_ERR_INI_SIZE:
	            $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
	            break;
	        case UPLOAD_ERR_FORM_SIZE:
	            $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
	            break;
	        case UPLOAD_ERR_PARTIAL:
	            $message = "The uploaded file was only partially uploaded";
	            break;
	        case UPLOAD_ERR_NO_FILE:
	            $message = "No file was uploaded";
	            break;
	        case UPLOAD_ERR_NO_TMP_DIR:
	            $message = "Missing a temporary folder";
	            break;
	        case UPLOAD_ERR_CANT_WRITE:
	            $message = "Failed to write file to disk";
	            break;
	        case UPLOAD_ERR_EXTENSION:
	            $message = "File upload stopped by extension";
	            break;
            case 'accepted_file_types':
                $message = "File type is not allowed for " . $file['old_name'];
                break;
            case 'file_uploads':
                $message = "File uploading option in disabled in php.ini";
                break;
            case 'max_file_size':
                $message = $file['old_name'] . " is too large";
                break;
            case 'max_files_size':
                $message = "Files are too big";
                break;
            case 'max_number_of_files':
                $message = "Maximum number of files is exceeded";
                break;
            case 'required_and_no_file':
                $message = "No file was choosed. Please select one";
                break;
			case 'invalid_folder_path':
				$message = "Upload folder doesn't exist or is not writable";
				break;
	        default:
	            $message = "Unknown upload error";
	            break;
	    }
        
	    return $message;
    }
	
	private function getFileAttribute($file, $attribute) {
		$result = null;

		if (isset($file['data'][$attribute]))
			$result = $file['data'][$attribute];
		if (isset($file[$attribute]))
			$result = $file[$attribute];

		return $result;
	}
    
    /**
     * formatSize method
     * Cover bytes to readable file size format
     *
	 * @private
	 * @param $bytes {Number}
     * @return {Number}
     */
    private function formatSize($bytes) {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }elseif ($bytes > 0) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }else{
            $bytes = '0 bytes';
        }

        return $bytes;
    }
	
	/**
     * isJson method
     * Check if string is a valid json
     *
	 * @private
	 * @param $string {String}
     * @return {boolean}
     */
    private function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
	
	/**
     * filterFilename method
     * Remove invalid characters from filename
     *
	 * @private
     * @param $filename {String}
     * @return {String}
     */
    private function filterFilename($filename) {
        $delimiter = '_';
        $invalidCharacters = array_merge(array_map('chr', range(0,31)), array("<", ">", ":", '"', "/", "\\", "|", "?", "*"));
        
        // remove invalid characters
        $filename = str_replace($invalidCharacters, $delimiter, $filename);
        // remove duplicate delimiters
        $filename = preg_replace('/(' . preg_quote($delimiter, '/') . '){2,}/', '$1', $filename);

        return $filename;
	}
    
    /**
     * generateFileName method
     * Generated a new file name
     *
	 * @private
	 * @param $conf {null, String, Array} FileUploader title option
	 * @param $file {Array} File data as title, extension and size
	 * @param $skip_replace_check {boolean} Used only for recursive auto generating file name to exclude replacements 
     * @return {String}
     */
	private function generateFilename($conf, $file, $skip_replace_check = false) {
		$conf = !is_array($conf) ? array($conf) : $conf;
		$type = $conf[0];
		$length = isset($conf[1]) ? (int) $conf[1] : 12;
		$random_string = substr(str_shuffle("_0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
        $extension = !empty($file['extension']) ? "." . $file['extension'] : "";
        $string = "";
		
		switch($type) {
			case null:
			case "auto":
				$string = $random_string;
				break;
			case "name":
				$string = $file['title'];
				break;
			default:
				$string = $type;

				$string = str_replace("{random}", $random_string, $string);
				$string = str_replace("{file_name}", $file['title'], $string);
				$string = str_replace("{file_size}", $file['size'], $string);
				$string = str_replace("{timestamp}", time(), $string);
				$string = str_replace("{date}", date('Y-n-d_H-i-s'), $string);
				$string = str_replace("{extension}", $file['extension'], $string);
		}
        $string .= $extension;
        
		// generate another filename if a file with the same name already exists
		// only when replace options is true
		if (!$this->options['replace'] && !$skip_replace_check) {
            $title = $file['title'];
            $i = 1;
            while (file_exists($this->options['uploadDir'] . $string)) {
                $file['title'] = $title . " ({$i})";
				$conf[0] = $type == "auto" || $type == "name" || strpos($string, "{random}") !== false ? $type : $type  . " ({$i})";
                $string = $this->generateFileName($conf, $file, true);
                $i++;
            }
        }

		return $this->filterFilename($string);
	}
    
    /**
     * mime_content_type method
     * Get the mime_content_type of a file
     *
	 * @public
     * @static
	 * @param $file {String} File location
     * @return {String}
     */
    public static function mime_content_type($file) {
        if (function_exists('mime_content_type')) {
            return mime_content_type($file);
        } else {
            $mime_types = array(
                'txt' => 'text/plain',
                'htm' => 'text/html',
                'html' => 'text/html',
                'php' => 'text/html',
                'css' => 'text/css',
                'js' => 'application/javascript',
                'json' => 'application/json',
                'xml' => 'application/xml',
                'swf' => 'application/x-shockwave-flash',
                'flv' => 'video/x-flv',

                // images
                'png' => 'image/png',
                'jpe' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpeg',
                'gif' => 'image/gif',
                'bmp' => 'image/bmp',
                'ico' => 'image/vnd.microsoft.icon',
                'tiff' => 'image/tiff',
                'tif' => 'image/tiff',
                'svg' => 'image/svg+xml',
                'svgz' => 'image/svg+xml',

                // archives
                'zip' => 'application/zip',
                'rar' => 'application/x-rar-compressed',
                'exe' => 'application/x-msdownload',
                'msi' => 'application/x-msdownload',
                'cab' => 'application/vnd.ms-cab-compressed',

                // audio/video
                'mp3' => 'audio/mpeg',
                'qt' => 'video/quicktime',
                'mov' => 'video/quicktime',

                // adobe
                'pdf' => 'application/pdf',
                'psd' => 'image/vnd.adobe.photoshop',
                'ai' => 'application/postscript',
                'eps' => 'application/postscript',
                'ps' => 'application/postscript',

                // ms office
                'doc' => 'application/msword',
                'rtf' => 'application/rtf',
                'xls' => 'application/vnd.ms-excel',
                'ppt' => 'application/vnd.ms-powerpoint',

                // open office
                'odt' => 'application/vnd.oasis.opendocument.text',
                'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
            );
            $ext = strtolower(array_pop(explode('.', $file)));
            
            if (array_key_exists($ext, $mime_types)) {
                return $mime_types[$ext];
            } elseif (function_exists('finfo_open')) {
                $finfo = finfo_open(FILEINFO_MIME);
                $mimetype = finfo_file($finfo, $file);
                finfo_close($finfo);
                return $mimetype;
            } else {
                return 'application/octet-stream';
            }
        }
    }
}