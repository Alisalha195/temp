<?php

class Category {

	// DB stuff
    private $conn;
    private $table = 'categories';

    // Category Properties
    public $id;
    public $name;
    public $created_at;

    public function __construct($db) {
    	$this->conn = $db;
    }

    public function read() {
    	$query = 'SELECT 
    	    id ,
    	    name,
    	    created_at

    	    FROM ' . $this->table .'
    	    ORDER BY
    	       created_at DESC';
        // Prepare statement
    	$stmt = $this->conn->prepare($query);

    	$stmt->execute();

    	return $stmt;
    }

    // Get Single Category
  public function read_single(){
    // Create query
    $query = 'SELECT
          id ,
          name
        FROM
          ' . $this->table . '
      WHERE id = ?
      LIMIT 0,1';

      //Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind ID
      $stmt->bindParam(1, $this->id);

      // Execute query
      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      // set properties
      // $this->id = $row['id'];
      $this->name = $row['name'];
  }

  public function create() {
    $query = 'INSERT INTO ' . $this->table .' SET name = :name';

    // echo $query;
    $stmt = $this->conn->prepare($query);

    $this->name = htmlspecialchars(strip_tags($this->name));
    $stmt->bindParam(':name' , $this->name);

    // Dont create category allready created
    $query_2 = 'SELECT name FROM ' . $this->table . ' WHERE name = :name ';

    $stmt_2 = $this->conn->prepare($query_2);

    $stmt_2->bindParam(':name' , $this->name);

    if($stmt_2->execute()) {
        if($stmt_2->rowCount() > 0)
        return false ;
    }

    if($stmt->execute()) {
        return true;
    } else {
        return false;
    }
  }

  public function update() {
    $query = 'UPDATE ' .
      $this->table . '
    SET
      name = :name
      WHERE
      id = :id';

      $stmt = $this->conn->prepare($query);
        $this->name = htmlspecialchars(strip_tags($this->name));

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':name' , $this->name);
        $stmt->bindParam(':id' , $this->id);

        // Dont create category allready created
        $query_2 = 'SELECT name FROM ' . $this->table . ' WHERE name = :name ';

        $stmt_2 = $this->conn->prepare($query_2);

        $stmt_2->bindParam(':name' , $this->name);

        if($stmt_2->execute()) {
            if($stmt_2->rowCount() > 0)
            return false ;
        }

        if($stmt->execute()) {
            return true; 
        } 

        printf("Error: $s.\n" , $stmt->error);
        return false;
  }

  public function delete() {
    $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

    // echo $query;
    $stmt = $this->conn->prepare($query);
    $this->id = htmlspecialchars(strip_tags($this->id));
    $stmt->bindParam(':id' , $this->id);

    $query_2 = 'SELECT id FROM ' . $this->table . ' WHERE id = :id ';

    $stmt_2 = $this->conn->prepare($query_2);

    $stmt_2->bindParam(':id' , $this->id);

    if($stmt_2->execute()) {
        if($stmt_2->rowCount() < 1)
        return false ;
    }

    if($stmt->execute()) {
        return true;
    }
    printf("Error: $s.\n", $stmt->error);
    return false;
  }
}

