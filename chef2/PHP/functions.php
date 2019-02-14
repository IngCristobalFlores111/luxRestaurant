<?php


function createMysqliConnection(){
    return new SQLConnection("localhost", "dbRestaurantUsr", "restUsr111@", "dbbbq");
}




class SQLConnection
{
	private $last_query = '';
	private $last_result ='';
	private $user = '';
	private $host = '';
	private $pswrd ='';
	private $bdName = '';
    private $lastId = '';
    private $errorLog = array();
    private $mysqli;

    public function ejecutarNoQuery($query){  // query que no devuelve resultados
        $results = $this->mysqli->multi_query($query);
        do {
            $this->mysqli->use_result();
        }while($this->mysqli->more_results() && $this->mysqli->next_result() );
        if($results){
            $this->lastId = $this->mysqli->insert_id;
            return true;
        }else{
            array_push($this->errorLog,$this->mysqli->error);

            return false;
        }


    }

    public function get_json_from_array($keys,$values){
      //toma 2 arrays con llaves y valores y los convierte
        // a una cadena simple de en json
        // la longitud de ambos arrays debe de ser igual
        // de lo contrario se retornara 0
        $j_string = '{';
        if(count($keys)!=count($values)){
            return 0;
        }else{
            $i = 0;
            foreach($keys as $k){
                $j_string = $j_string.'"'.$k.'":"'.$values[$i].'",';
            $i++;
            }
            $j_string = rtrim($j_string, ",");
            $j_string = $j_string."}";

            return $j_string;
        }

    }


	public function multi_update($jData,$table,$where)  // recibe un objeto json con los campos a actualizar
	{   // $table : tabla para realizar la actualizacion
		// $jData:  campo1:"nuevo valor1",campo2:"nuevo valor2",...
		// $where: sentencia where del update
		$query ="UPDATE $table SET ";
		foreach($jData as $key=>$value)
			{$query = $query.$key."='".$value."',";}
		$query =rtrim($query, ",");
		$query =$query." ".$where;
		$this->executeCommand($query);
	}
	public function get_table($query)
	{// de un conjunto de valores retornados en un resultset de $query lo transforma en elementos de una tabla
		// dentro de <tbody></tbody>, en el orden que vengan dentro de la consulta

		$result = $this->executeQuery($query);
		if(is_null($result))
		return 0;
		$out ='';
		foreach($result as $row)
		{ $out = $out."<tr>";
			foreach($row as $data)
				{$out =$out."<td>$data</td>";}
		$out = $out."</tr>";}
		return $out;
	}

	public function filter_json($json)
	{
		foreach($json as $key=>$value)
		{
			$json->$key = $this->filter_input($value);
		}
		return $json;
	}



    public function filter_array($arr)
    {
        $type = gettype($arr);
        if($type === "array"){
            foreach($arr as $value){
                $rowtype = gettype($value);
                if($rowtype === "array")
                    $this->filter_array($value);

                else if($rowtype === "string"){
                    $value = $this->filter_input($value);
                }
                else{
                }

            }
        }

        else if($type === "string"){
            $arr = $this->filter_input($arr);
            // print_r("|_________FILTRADO: " . $value."________|");
        }
        else{
            // print_r("|_____NO FILTRADO: " . $value."_______|");
        }
    }

    public function filter_stdObj($obj)
    {
        foreach($obj  as $key=>$value)
        {
            if(gettype($value)=="string")
            $obj[$key] = $this->filter_input($value);
        }
        return $obj;
    }

	public function filter_input($str)
	{
        $str = (string)$str;
		$str = str_replace("'",'',$str); //De comillas simples a nada
        $str = str_replace('"','',$str); //De comillas dobles a nada
		$str = htmlspecialchars($str,ENT_COMPAT);
        $str = trim($str);
		return $str;
	}


    public function get_bind_results($query,$params){
       // $mysqli = new mysqli($this->host, $this->user, $this->pswrd, $this->bdName);
        //$mysqli->set_charset("utf8");

        $statement = $this->mysqli->prepare($query);

        $tmp = array();
       foreach($params as $key => $value) $tmp[$key] = &$params[$key];

      call_user_func_array(array(&$statement,'bind_param'),$tmp);
      if ($statement->execute()){
          $result = $statement->get_result();

          $a = $result->fetch_all(MYSQLI_ASSOC);

          $statement->close();
          return $a;
      } else{

          array_push($this->errorLog,$statement->error);
          $statement->close();
          return 0;

      }
      //$mysqli->close();

    }
    public function getLastId(){

        return $this->lastId;
    }

    public function execQueryBinders($query,$params){
       // $mysqli = new mysqli($this->host, $this->user, $this->pswrd, $this->bdName);
        //$mysqli->set_charset("utf8");
        $statement = $this->mysqli->prepare($query);
        $tmp = array();
        foreach($params as $key => $value) $tmp[$key] = &$params[$key];
        call_user_func_array(array(&$statement,'bind_param'),$tmp);
        if(!$statement->execute()){
            array_push($this->errorLog,$statement->error);
        }else{
            $this->lastId = $this->mysqli->insert_id;
        }

        $statement->free_result();
        $statement->close();
        //$mysqli->close();


    }

	private function getSQLResultSet($commando){


	//	$mysqli = new mysqli($this->host, $this->user, $this->pswrd, $this->bdName);

//		$mysqli->set_charset("utf8");

		/* check connection */
		if ($this->mysqli->connect_errno) {
			printf("Connect failed: %s\n", $mysqli->connect_error);
			exit();
		}

		if ( $this->mysqli->multi_query($commando)) {
			return $this->mysqli->store_result();




		}


		//$mysqli->close();
	}



  public function change_db($newDBName)
    {
	$this->bdName = $newDBName;
	}
	private function get_parameters()
	{
		return array($this->user,$this->host,$this->pswrd,$this->bdName);
	}


	public function __construct($host,$usr,$pass,$bd)
	{
		$this->user = $usr; $this->host = $host; $this->pswrd = $pass;$this->bdName = $bd;
        $this->mysqli = new mysqli($this->host, $this->user, $this->pswrd, $this->bdName);
        $this->mysqli->set_charset("utf8");
	}
	public function __destruct()
	{
		$this->user = '';
		$this->host = '';
		$this->pswrd = '';
		$this->bdName = '';
        $this->mysqli->close();
	}
	public function change_params($host,$usr,$pass,$bd)
	{
		$this->user = $usr;
		$this->host = $host;
		$this->pswrd = $pass;
		$this->bdName = $bd;
        $this->mysqli = new mysqli($this->host, $this->user, $this->pswrd, $this->bdName);
        $this->mysqli->set_charset("utf8");

	}
	public function multi_query($querys)
	{  // recibe array de querys y devuelve un array de jsons con las respuestas
		$out = '{';$i = 0;$len = count($querys);
		foreach($querys as $query)
		{   if(trim($query)!='')
			{	$tmp_obj = $this->getJSONFromSql($query);
				if(trim($tmp_obj)=='')$tmp_obj = '"Error,Check your query"';
				if($i!=($len-1)) {$out = $out.'"result'.$i.'":'.$tmp_obj.',';}else{
				$out = $out.'"result'.$i.'":'.$tmp_obj;}
	}$i++; } $out = $out.'}';  return $out;}

	private function ejecutarSQLCommand($commando){

//		$mysqli = new mysqli($this->host, $this->user, $this->pswrd, $this->bdName);
	//	$mysqli->set_charset("utf8");
		/* check connection */
		if ($this->mysqli->connect_errno) {
			printf("Connect failed: %s\n", $this->mysqli->connect_error);
			exit();
		}

		if ( $this->mysqli->multi_query($commando)) {
			if ($resultset = $this->mysqli->store_result()) {
				while ($row = $resultset->fetch_array(MYSQLI_BOTH)) {

				}
				$resultset->free();
			}


		}else{
            array_push($this->errorLog,$this->mysqli->error);
        }
		//$mysqli->close();
        $this->lastId = $this->mysqli->insert_id;
	}

	private function getJSONResultSQL($query)
	{
		$var = null;
		if ($resultset =$this->getSQLResultSet($query)) {

			while($obj = mysqli_fetch_object($resultset)) {

				$var[] = $obj;

			}

			return '{"result":'.json_encode($var).'}';

		}

	}

	private function getJSONFromSql($query)
	{
		$var = null;
		if ($resultset = $this->getSQLResultSet($query)) {

			while($obj = mysqli_fetch_object($resultset)) {

				$var[] = $obj;

			}

			return json_encode($var,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS |JSON_HEX_QUOT |JSON_NUMERIC_CHECK);

		}


	}

	private function execResultSet($query)
	{
		//$var[] = null;
		$var = array();
		if ($resultset = $this->getSQLResultSet($query)) {
            $var = $resultset->fetch_all(MYSQLI_ASSOC);
			return $var;
		}else{
            return null;
        }

	}
	public function executeQuery ($query)
	{
		$this->last_query = $query;
		$result= $this->execResultSet($query);
		$this->last_result = $result;
		return $result;
	}
	public function executeQueryJSON($query)
	{	$this->last_query = $query;
		$result= $this->getJSONResultSQL($query);
		$this->last_result = $result;
		return $result;
	}
	public function executeCommand($query)
	{

		$this->last_query = $query;
		 $this->last_result = '';
		$result = $this->ejecutarSQLCommand($query);
		return $result;

	}
	public function get_last_query()
	{
		return $this->last_query;
	}
	public function get_last_result()
	{
		return $this->last_result;
	}
    public function getErrorLog(){
        return $this->errorLog;
    }

    public function render_select($query,$name,$value){

        $out = '<option value="0">---</option>';  // opcion por defaults
      $result =  $this->executeQuery($query);
      foreach($result as $res){

          $out = $out.'<option value="'.$res[$value].'">'.$res[$name].'</option>';
      }


      return $out;


    }


}






?>