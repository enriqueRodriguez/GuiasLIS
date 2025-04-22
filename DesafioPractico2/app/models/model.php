<?php
require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

abstract class Model
{
    private $host;
    private $user;
    private $password;
    private $db_name;
    protected $conn;

    public function __construct()
    {
        $this->host = $_ENV['HOST'];
        $this->user = $_ENV['USERNAME'];
        $this->password = $_ENV['PASSWORD'];
        $this->db_name = $_ENV['DBNAME'];
    }

    //crear conexion
    protected function open_db()
    {
        try {
            $this->conn = new PDO("mysql:host=$this->host; dbname=$this->db_name; charset=utf8", $this->user, $this->password);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    //metodo para cerrar conexion
    protected function close_db()
    {
        $this->conn = null;
    }


    protected function get_query($query, $params = array())
    {
        try {
            $rows = [];
            $this->open_db();
            $stm = $this->conn->prepare($query);
            $stm->execute($params);
            while ($rows[] = $stm->fetch(PDO::FETCH_ASSOC));

            $this->close_db();
            array_pop($rows); //quitando el ultimo elemento

            return $rows;
        } catch (Exception $e) {
            $this->close_db();
            return [];
        }
    }

    protected function set_query($query, $params = array())
    {
        try {

            $this->open_db();
            $stm = $this->conn->prepare($query);
            $stm->execute($params);
            $affectedRows = $stm->rowCount();
            $this->close_db();
            return $affectedRows;
        } catch (Exception $e) {

            $this->close_db();

            return 0;
        }
    }
}
