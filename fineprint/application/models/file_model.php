<?php
class File_model extends CI_Model {
    function bulk_rename($new_names, $original_names, $path)
    {
        // Convert the short path into the full file path
        $path = clean_file_path(FILE_DIR . "/$path");

        // Check no files exist with any of the new names (Except for those that are already being renamed
        foreach ($new_names as $new_name)
        {
            if (array_search($new_name, $original_names) === FALSE && file_exists(clean_file_path("$path/$new_name")))
            {
                throw new Exception('One or more of the file names entered is already in use.  Please pick another!');
            }
        }

        // In order to rename all the files in one go we rename them all to unique names first, then assign new names
        // that do not already exist and are not due to be set.  This allows names to be 'swapped' easily.

        // First we create two 'translation' arrays, one for setting temp names, and the other for setting the new names
        $temp_names_to_new = array();

        // Go and rename all the files to temporary names
        for ($i=0; $i < count($original_names); $i++)
        {
            $original_name = $original_names[$i];

            $path_to_file = clean_file_path("$path/$original_name");

            // Now we need to generate a unique name for the file so we start with a hash
            $temp_name = sha1(sha1_file($path_to_file) . $original_name);

            // Now we need to check that a file with this name does not already exist in the directory and the name is
            // not in the list of new names, this is a very unlikely case, but in the event that it does happen, the new
            // name should be changed until it no longer violates the requirements above.

            $counter = 0;
            $cleaned_temp_name = $temp_name;
            while (file_exists(clean_file_path("$path/$temp_name")) || array_search($temp_name, $new_names) !== FALSE)
            {
                $cleaned_temp_name = "$temp_name$counter";

                $counter++;
            }

            // Now rename the file to its temp name
            rename(clean_file_path("$path/$original_name"), clean_file_path("$path/$cleaned_temp_name"));

            // Now map the temp name to the new name
            $temp_names_to_new[$cleaned_temp_name] = $new_names[$i];
        }

        // Now all files have temp names, we then rename them back to their new names
        foreach ($temp_names_to_new as $temp_name=>$new_name)
        {
            rename(clean_file_path("$path/$temp_name"), clean_file_path("$path/$new_name"));
        }

        // Process is now complete
        return TRUE;
    }
}