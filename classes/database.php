<?php
class database{

    function opencon(): PDO{
        return new PDO(
    'mysql:host=localhost;
          dbname=library_management',
          username: 'root',
          password: '');    


    }

    function insertUser($email, $password_hash, $is_active){
        $con = $this->opencon();

        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO Users (username,user_password_hash,is_active) VALUES (?,?,?)');
            $stmt->execute([$email, $password_hash, $is_active]);
            $user_id = $con->lastInsertId();
            $con->commit();
            return $user_id;

        }catch(PDOException $e){
            if($con->inTransaction()){
                $con->rollBack();
        } 

    }

}

    function insertBorrowers($firstname, $lastname, $email, $phone, $member_since, $is_active) {
    $con = $this->opencon();

    try{
        $con->beginTransaction();
        $stmt = $con->prepare('INSERT INTO borrowers (borrower_firstname, borrower_lastname, borrower_email, borrower_phone_number, borrower_member_since, is_active) VALUES (?,?,?,?,?,?)');
        $stmt->execute([$firstname, $lastname, $email, $phone, $member_since, $is_active]);
        $borrower_id = $con->lastInsertId();
        $con->commit();
        return $borrower_id;


        }catch(PDOException $e){
            if($con->inTransaction()){
                $con->rollBack();
        }

    }
}

     function insertBorrowerUser($user_id, $borrower_id) {

     $con = $this->opencon();
     try{
        $con->beginTransaction();
        $stmt = $con->prepare('INSERT INTO borrower_user (user_id, borrower_id) VALUES (?, ?)');
        $stmt->execute([$user_id, $borrower_id]);
        $bu_id = $con->lastInsertId();
        $con->commit();

        return true;

        }catch(PDOException $e){
            if($con->inTransaction()){  
                $con->rollBack();
            }
        }

    }


    function viewBorrowersUser() {
        $con = $this->opencon();
        return $con->query("SELECT * from Borrowers")->fetchAll();


    }


        function insertBorrowerAddress($borrower_id, $house_number, $street, $barangay, $city, $province, $postal_code, $is_primary) {
        $con = $this->opencon();
            
        try{
            $con->beginTransaction();
            $stmt = $con->prepare("INSERT INTO borrower_address (borrower_id, ba_house_number, ba_street, ba_barangay, ba_city, ba_province, ba_postal_code, is_primary) VALUES(?,?,?,?,?,?,?,?)");
            $stmt->execute([$borrower_id, $house_number, $street, $barangay, $city, $province, $postal_code, $is_primary]);
            $ba_id = $con->lastInsertId();
            $con->commit();

            return true;
        }catch(PDOException $e){
            if($con->inTransaction()){
                    $con->rollBack();


            }


        }
    }




        function insertBook($title, $isbn, $publication_year, $edition, $publisher) {  
    try {
        $con = $this->opencon();
        $con->beginTransaction(); // Added transaction handling
        $stmt = $con->prepare("INSERT INTO books (book_title, book_isbn, book_publication_year, book_edition, book_publisher) VALUES(?,?,?,?,?)");
        $stmt->execute([$title, $isbn, $publication_year, $edition, $publisher]);
        $book_id = $con->lastInsertId();
        $con->commit();

        return true;
    } catch (PDOException $e) {
        if ($con->inTransaction()) {
            $con->rollBack();
            }
         // Re-throw the exception for higher-level handling
        }   
    }

        
        function viewBooks() {
                $con = $this->opencon();
                return $con->query("SELECT * from Books")->fetchAll();


        }
        function insertBookCopy($book_id, $bc_status) {
        $con = $this->opencon();
            
        try{
            $con->beginTransaction();
            $stmt = $con->prepare("INSERT INTO book_copy (bc_status, book_id) VALUES(?,?)");
            $stmt->execute([$book_id, $bc_status, ]);
            $Copy_id = $con->lastInsertId();
            $con->commit();

            return true;
        }catch(PDOException $e){
            if($con->inTransaction()){
                    $con->rollBack();


            }


        }
    }
    function viewCopies(){
        $con = $this->opencon();
        return $con->query("SELECT 
        books.book_id, 
        books.book_title, 
        books.book_isbn, 
        books.book_publication_year, 
        books.book_publisher, 
        COUNT(book_copy.copy_id) as Copies, 
        SUM(book_copy.bc_status = 'AVAILABLE') as Available_copies 
        FROM books 
        JOIN book_copy ON book_copy.book_id = books.book_id 
        Group by 1;
        
        ")->fetchAll();
    }


    function viewauthors() {
        $con = $this->opencon();
        return $con->query("SELECT * from Author")->fetchAll();

    }
    function insertBookAuthor($book_id, $author_id) {
        $con = $this->opencon();

        try {
            $con->beginTransaction();
            $stmt = $con->prepare("INSERT INTO book_authors (book_id, author_id) VALUES (?, ?)");
            $stmt->execute([$book_id, $author_id]);
            $baba_id = $con->lastInsertId();
            $con->commit();

            return true;
        } catch (PDOException $e) {
            if ($con->inTransaction()) {
                $con->rollBack();
            }
            
            return false;
        }
    }

        function viewgenre() {
        $con = $this->opencon();
        return $con->query("SELECT * from Genre")->fetchAll();

    }

        function insertBookGenre($book_id, $genre_id) {
            $con = $this->opencon();

            try {
                $con->beginTransaction();
                $stmt = $con->prepare("INSERT INTO book_genre (book_id, genre_id) VALUES (?, ?)");
                $stmt->execute([$book_id, $genre_id]);
                $gb_id = $con->lastInsertId();
                $con->commit();

                return true;
            } catch (PDOException $e) {
                if ($con->inTransaction()) {
                    $con->rollBack();
                }
                
                return false;
            }
    }
}

?>