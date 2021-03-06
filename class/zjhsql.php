<?php
   class ZjhSql{

      private $pdo;
      // private $stmt;
      private $queryString;
      private $pagebar;
      private $pagenum = 15;//默认每页信息条数
      private static $zjhobj;

      private function __construct($servername,$dbname,$username,$password){
         try{
            $this->pdo = new PDO("mysql:host=".$servername.";dbname=".$dbname,$username,$password,array(PDO::ATTR_PERSISTENT => true,PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8';"));
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
         }catch(Exception $e){
            trigger_error("database connecting error!please check class zjhsql construct params!");
            exit;
         }
      }
      function datatype($val) {
         if (is_bool($val)){
            return PDO::PARAM_BOOL;
         } elseif (is_int($val)) {
            return PDO::PARAM_INT;
         } elseif(is_null($val)) {
            return PDO::PARAM_NULL;
         } elseif(is_float($val)){
            return PDO::PRAM_FLOAT;
         }else {
            return PDO::PARAM_STR;
         }
      }
      static function getInstance($servername,$dbname,$username,$password){
         if(!self::$zjhobj){
            self::$zjhobj = new ZjhSql($servername,$dbname,$username,$password);
         }
         return self::$zjhobj;
      }

      public function prepare($sql,$params,$page=null){

         if(!is_array($params)){
            $params = array($params);
         }
         if(!empty($page)){
            $count_sql = 'select count(*) from ('.$sql.") abcxyz";
            $total = $this->prepare($count_sql,$params)->fetchColumn();//返回查询出来的行数
            $sql = $sql." limit ".(($page[0]-1)*$page[2]).",".$page[2];
            $this->pagination($total,$page);
         }
         
         // $this->stmt = $this->pdo->prepare($sql);
         $stmt = $this->pdo->prepare($sql);

         if(preg_match("/:/",$sql)){

            foreach($params as $key => $value){
               $k = preg_replace("/(^[^:]+)/i",':$1', trim($key));
               $stmt->bindParam($k,$params[$key],$this->datatype($params[$key]));
               //这里动态绑定不能直接用$value;
            }
         }else if(preg_match('/\?/',$sql)){
            foreach ($params as $key => $value) {
               $stmt->bindParam($key+1,$params[$key],$this->datatype($params[$key]));
            }
         }
         try{
            $stmt->execute();
            $this->queryString = $stmt->queryString;
            return $stmt;
         }catch(PDOException  $e){
            trigger_error($e);
            exit;
         }
      } 
      function queryOne($sql,$params=null){
         if(!preg_match("/\s*select/i", $sql)){
            trigger_error('you are not executing select statement , please call exec function!');
            return;
         }

         if(empty($params)){

            return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

         }else{
            $stmt = $this->prepare($sql,$params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
         }
      }
      function getPage(){
         return $this->pagebar;
      }
      /*
      当前页面
      url
      每页数据条数 默认$pagenum = 15
      */
      function pagination($total,$page){
         $url = $page[1];
         if($total == 0) return null;
         if(!is_array($page)) $page = array($page);
         $num = empty($page[2]) ? $this->pagenum : $page[2];
         $page_num = ceil($total/$num);
         $page[0] = (int)$page[0];
         $current = $page[0] <= 0 ? 1 : $page[0] > $page_num ? $page_num : $page[0];

         $page_arr = array();//装页码的数组
         $pagebar = '';//最终页码的html

         for($i = $current-2,$len = $current+2;$i<$len;$i++){
            if($i <= 0 || $i > $page_num) continue;
            $page_arr[] = $i;
            if($i == $current){
               $pagebar = $pagebar.'<a class="active" href="'.$url.'&p='.$i.'">'.$i."</a>";
            }else{
               $pagebar = $pagebar.'<a href="'.$url.'&p='.$i.'">'.$i."</a>";
            }
         }
         if($page_arr[0] > 1){
            $pagebar = '<a href="'.$url.'&p='.($current-1).'">上一页</a>'.$pagebar;
         }
         if($page_arr[count($page_arr)-1] < $page_num){
            $pagebar = $pagebar.'<a href="'.$url.'&p='.($current+1).'">下一页</a>';
         }
         $pagebar = $pagebar.'<a href="javascript:;">共'.$total."</a>";
         $this->pagebar = '<div class="pagination">'.$pagebar."</div>";
         // $this->pagebar = $total;
      }
      /*
      *@params $sql   查询的sql语句
      *@params $param 参数，类型是：数字，字符串，数组
      *@params $limit 分页参数
      *               若是int，表示当前请求页
      *               若是数组，数组参数分别表示当前页,url,每页数据
      */
      function query($sql,$params=null,$page=null){
         if(!preg_match("/\s*select/i", $sql)){
            trigger_error('you are not executing select statement , please call exec function!');
            return;
         }
         $stmt = $this->prepare($sql,$params,$page);
         $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
         return $data;
      }
      function exec($sql,$params=null){
         if(empty($params)){
            return $this->pdo->exec($sql);//返回执行条数
         }else{
            $stmt = $this->prepare($sql,$params);
            return $stmt->rowCount();
         }
      }
      function queryString(){
         return $this->queryString;
      }
      function getClass($sql,$params,$class){
         $stmt = $this->prepare($sql,$params);
         $stmt->setFetchMode(PDO::FETCH_CLASS, $class);
         return $stmt->fetch();
      }
      //开始事务
      function begin(){
         $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT,false);
         $this->pdo->beginTransaction();
      }
      //提交事务
      function commit(){
         $this->pdo->commit();
         $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT,true);
      }
      //事务回滚
      function rollback(){
         $this->pdo->rollback();
         $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT,true);
      }

   }
?>


