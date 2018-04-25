<?php
    /**
     *  CodeIgniter Model Auto Generating Script
     *  Created by Ning Luo
     *  Any errors and suggestions please send me an email to ning.loric@gmail.com, thanks!
     *  28/10/2013
     */

    /*Database Setting Start*/
	$host = "localhost";
	$port = "3306";
	$user = "cmappdba";
	$pass = "cmappdba";
    	$db_name = "collegemobileapp";
    /*Database Setting End*/

    /*Extracting Folder Start*/
    $extract_folder = "e:/Projects/CollegeMobileApp/gen_code";
    if (!file_exists($extract_folder)) {
        mkdir($extract_folder, 0777, true);
    }
    /*Extracting Folder End*/

    /*Database Connection Start*/
    $conn = mysqli_connect($host, $user, $pass);
    mysqli_select_db($conn, $db_name);

    $sql = "select table_name from information_schema.tables where table_schema='$db_name'";
    $result = mysqli_query($conn, $sql);
    /*Database Connection End*/


    /*Generating Model Code Start*/
    while(($row = mysqli_fetch_assoc($result)))
    {
        $tb_name = $row['table_name'];
        $sql = "select column_name, column_key, extra from information_schema.columns where table_schema='$db_name' and table_name='$tb_name'";
        $result_column = mysqli_query($conn, $sql);

        $total = mysqli_query($conn, "SELECT * FROM $tb_name");
        $total_num = mysqli_num_fields($total);

        $ftable = fopen($extract_folder.'/'.strtolower($tb_name) . "_model.php", "w");
        $str = "<?php\n";
        $str .= "class " . ucfirst($tb_name) . "_model extends CI_Model\n{\n\n\t";
        $str .= "function __construct()\n\t{\n\t\t";
        $str .= "parent::__construct();\n\t}\n\n\t";
        fwrite($ftable, $str);

        $str_create = "function create(\$item)\n\t{\n\t\t";
        $str_update = "function update(\$id, \$item)\n\t{\n\t\t";
        $str_read = "function get_by_id(\$id)\n\t{\n\t\t";
        $str_readAll = "function get_all()\n\t{\n\t\t";
        $str_delete = "function delete(\$id)\n\t{\n\t\t";

        $str_create .= "\$data = array(\n\t\t\t";
        $str_update .= "\$data = array(\n\t\t\t";
        $str_update_col = "";
        
        $index=1;
        while($row_column = mysqli_fetch_assoc($result_column))
        {
            if($row_column["extra"] != "auto_increment")
            {
                if($index!=$total_num){
                    $str_create .= "'" . $row_column['column_name'] . "' => \$item['" . $row_column['column_name'] . "'],\n\t\t\t";
                }
                else{
                    $str_create .= "'" . $row_column['column_name'] . "' => \$item['" . $row_column['column_name'] . "']\n\t\t\t";
                }
            }
            if($row_column["column_key"] != "PRI")
            {
                if($index!=$total_num){
                    $str_update .= "'" . $row_column['column_name'] . "' => \$item['" . $row_column['column_name'] . "'],\n\t\t\t";
                }
                else{
                    $str_update .= "'" . $row_column['column_name'] . "' => \$item['" . $row_column['column_name'] . "']\n\t\t\t";
                }
            }
            else
            {
                $str_read .= "\$this->db->select('*');\n\t\t";
                $str_read .= "\$this->db->from('" . strtolower($tb_name) . "');\n\t\t";
                $str_read .= "\$this->db->where('" . $row_column['column_name'] . "', \$id);\n\t\t";
                $str_delete .= "\$this->db->where('" . $row_column['column_name'] . "', \$id);\n\t\t";
                $str_update_col = $row_column['column_name'];
            }
            $index++;
        }

        $str_create .= " ) \n\n\t\t";
        $str_create .= "\$this->db->insert('" . strtolower($tb_name) . "', \$data);\n\t";
        $str_create .= "}\n\n\t";
        $str_update .= " ) \n\n\t\t";
        $str_update .= "\$this->db->where('" . $str_update_col . "', \$id);\n\t\t";
        $str_update .= "\$this->db->update('" . strtolower($tb_name) . "', \$data);\n\t";
        $str_update .= "}\n\n\t";
        $str_delete .= "\$this->db->delete('" . strtolower($tb_name) . "');\n\t";
        $str_delete .= "}\n";
        $str_read .= "\$query = \$this->db->get();\n\n\t\t";
        $str_read .= "if(\$query->num_rows()<1){\n\t\t\t";
        $str_read .= "return null;\n\t\t";
        $str_read .= "}\n\t\t";
        $str_read .= "else{\n\t\t\t";
        $str_read .= "return \$query->row();\n\t\t";
        $str_read .= "}\n\t";
        $str_read .= "}\n\n\t";
        $str_readAll .= "\$this->db->select('*');\n\t\t";
        $str_readAll .= "\$this->db->from('" . strtolower($tb_name) . "');\n\t\t";
        $str_readAll .= "\$query = \$this->db->get();\n\n\t\t";
        $str_readAll .= "if(\$query->num_rows()<1){\n\t\t\t";
        $str_readAll .= "return null;\n\t\t";
        $str_readAll .= "}\n\t\t";
        $str_readAll .= "else{\n\t\t\t";
        $str_readAll .= "return \$query->result_array();\n\t\t";
        $str_readAll .= "}\n\t";
        $str_readAll .= "}\n\n\t";

        fwrite($ftable, $str_create);
        fwrite($ftable, $str_read);
        fwrite($ftable, $str_readAll);
        fwrite($ftable, $str_update);
        fwrite($ftable, $str_delete);
        fwrite($ftable, "}");
        fclose($ftable);
        /*Generating Model Code Start*/
    }
?>

