<?php
require_once __DIR__ . "/../model/Ride.php";

class RideDAO
{
    private $dbh;

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    public function findById($id)
    {
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM ride WHERE id = :id");
            $stmt->execute([':id' => intval($id)]);
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Ride");
            $ride = $stmt->fetch();
            return $ride->getRideInfo();
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    public function getPage($page = 0, $numberPerPage = 10)
    {
        try {
            $stmt = $this->dbh->prepare("SELECT * FROM ride LIMIT :lim OFFSET :offset");
            $stmt->bindParam(':lim', intval($numberPerPage), PDO::PARAM_INT);
            $stmt->bindParam(':offset', intval($page * $numberPerPage), PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Ride");
            while ($ride = $stmt->fetch()) {
                $data[] = $ride->getRideInfo();
            }
            return $data;
        } catch (PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }

    public function create(
        $userID, $apptStart, $apptEnd, $pickupTime, $wheelchairVan,
        $pickupStreetAddress, $pickupCity, $apptStreetAddress, $apptCity
    ) {
        $stmt = $this->dbh->prepare("INSERT INTO `wasps`.`Ride` (`userID`, `apptStart`, `apptEnd`,  `pickupTime`, `wheelchairVan`, `pickupStreetAddress`, `pickupCity`, `apptStreetAddress`, `apptCity`) VALUES (:userID, :apptStart, :apptEnd, :pickupTime, :wheelchairVan, :pickupStreetAddress, :pickupCity, :apptStreetAddress, :apptCity)");
        $stmt->bindParam(':userID', intval($userID), PDO::PARAM_INT);
        $stmt->bindParam(':apptStart', $apptStart, PDO::PARAM_STR);
        $stmt->bindParam(':apptEnd', $apptEnd, PDO::PARAM_STR);
        $stmt->bindParam(':pickupTime', $pickupTime, PDO::PARAM_STR);
        $stmt->bindParam(':wheelchairVan', $wheelchairVan, PDO::PARAM_BOOL);
        $stmt->bindParam(':pickupStreetAddress', $pickupStreetAddress, PDO::PARAM_STR);
        $stmt->bindParam(':pickupCity', $pickupCity, PDO::PARAM_STR);
        $stmt->bindParam(':apptStreetAddress', $apptStreetAddress, PDO::PARAM_STR);
        $stmt->bindParam(':apptCity', $apptCity, PDO::PARAM_STR);
        $stmt->execute();      
    }
}
