<?php
    class FormValidate {
        
        private $fields;
        private $file_upload;

        public function __construct(array $fields, $file_upload='')
            {
                $this->fields = $fields;
                $this->file_upload = $file_upload;
            }

        // Helper function for validate().
        // Needs to be built out more to be more secure.
        // No longer in use.
        private function _handle_file_upload($lastname, $file_upload) 
        {
            if (!empty($_FILES[$file_upload]['name'])) {
                $filename = $_FILES[$file_upload]['name'];
                $filesize = $_FILES[$file_upload]['size'];
                $orig_path = $_FILES[$file_upload]['tmp_name'];
                $file_type = $_FILES[$file_upload]['type'];
                // Add the applicants last name to their filename.
                $new_name = $lastname . "_" . $filename;
                $path = "/opt/lampp/htdocs/test/media/";
                $new_path = $path . $new_name;

                $allowed_mimes = array('application/pdf', 
                                        'application/msword', 
                                        'text/plain', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                        );
                $max_size = 2500000;

                // If the file isn't one of the allowed MIME types or is too large, delete the file.
                if(!in_array($file_type, $allowed_mimes) || $filesize > $max_size) {
                    unlink($orig_path);
                    return false;
                } else {
                    // Valid file. Copy it to the new path (the media folder).
                    if(move_uploaded_file($orig_path, $new_path)) {
                        // Return the new file name (lastname_filename.filetype).
                        return $new_name;
                    }
                }
            } else {
                return false;
            }
        }

        public function positions($page)
        {
            include "positions.php";
            if (in_array($page, $positions)) {
                return true;
            } else {
                return false;
            }
        }
        // Returns a new associative array of $field => $value pairs.
        public function validate()
        {
            $form = array();
            // Iterate through the array of form fields.
            foreach($this->fields as $field) {
                if (!empty($_POST[$field])) {
                    // If the field is filled, add it to the $form array/dict as ($field => $value).
                    $i = $_POST[$field];
                    // Remove any HTML tags.
                    $i = strip_tags($i);
                    $form[$field] = $i;
                } else {
                    // Error!
                    $field = str_replace('_', ' ', $field);
                    echo "$field is required.</br>";                    
                }
            }
            // Check if all the fields are accounted for, then check for a file upload.
            if (count($this->fields) === count($form)) {   
                if ($this->file_upload) {
                    $lastname = $_POST['Last_Name'];
                    $file = $this->_handle_file_upload($lastname, $this->file_upload);

                    if($file) {
                        $form['File_Path'] = $file;

                        return $form;
                    } else {
                        echo "Submission failed -- Invalid File";
                    }

                } else {
                    // No attached file; return the $form array.
                    return $form;
                }
            }
        }

        public function get_form_data() 
        {
            if ($_SERVER['REQUEST_METHOD'] === "POST") {                                                                                                                                                               
                $completed_form = $this->validate();  

                return $completed_form;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
            }
        }
    }
?>  
