<?php
class cConnect
{
    private $dbHost;
    private $dbUser;
    private $dbPass;
    private $dbPort;
    private $dbName;

    public function __construct()
    {
        $this->dbHost = getenv("DB_HOST") ?: "localhost";
        $this->dbUser = getenv("DB_USERNAME") ?: "root";
        $this->dbPass = getenv("DB_PASSWORD") ?: "";
        $this->dbName = getenv("DB_DATABASE") ?: "gkj_dayu";
        $this->dbPort = getenv("DB_PORT") ?: "3306";
    }

    function goConnect()
    {
        $conn = mysqli_connect(
            $this->dbHost,
            $this->dbUser,
            $this->dbPass,
            $this->dbName,
            (int) $this->dbPort,
        );

        $GLOBALS["conn"] = $conn;

        return $conn;
    }
}
