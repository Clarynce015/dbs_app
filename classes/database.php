<?php
class database{

function opencon(): PDO {
    $con = new PDO(
        'mysql:host=localhost;dbname=librarymanagement',
        'root',
        ''
    );
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $con;
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
        LEFT JOIN book_copy ON book_copy.book_id = books.book_id 
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
        function viewloans() {
        $con = $this->opencon();
        return $con->query("SELECT 
            loan.loan_id,
            CONCAT(borrowers.borrower_firstname, ' ', borrowers.borrower_lastname) AS Borrower_name,
            loan.loan_status,
            loan.loan_date,
            users.username AS processed_by
        FROM loan                                        
        JOIN borrowers ON loan.borrower_id = borrowers.borrower_id
        JOIN users ON loan.user_id = users.user_id
        ORDER BY loan.loan_date DESC
        LIMIT 10
        ")->fetchAll();
        }


        function updateBook($book_id, $title, $isbn, $year, $publisher)
    {
    $con = $this->opencon();
 
    try {
        $con->beginTransaction();
 
        $stmt = $con->prepare("
            UPDATE Books
            SET book_title = ?,
                book_isbn = ?,
                book_publication_year = ?,
                book_publisher = ?
            WHERE book_id = ?
        ");
 
        $stmt->execute([$title, $isbn, $year, $publisher, $book_id]);
 
        $con->commit();
        return true; // Successfully updated
 
    } catch (PDOException $e) {
        if ($con->inTransaction()) {
            $con->rollBack();
        }
        throw $e;
        }
    }

       function countBook() {
        $con = $this->opencon();
        return $con->query("SELECT COUNT(*) FROM books")->fetchColumn();
        }

        function countCopies() {
            $con = $this->opencon();
            return $con->query("SELECT COUNT(*) FROM book_copy")->fetchColumn();
        }

        function countOpenLoans() {
            $con = $this->opencon();
            return $con->query("SELECT COUNT(*) FROM loan WHERE loan_status = 'OPEN'")->fetchColumn();
        }

        function countOverdue() {
             $con = $this->opencon();
            return $con->query("SELECT COUNT(loan_status) AS Open_Loans FROM loan WHERE loan_status = 'OPEN'")->fetchColumn();
        }

        function countOverdueItem(){
            $con = $this->opencon();
            return $con->query("SELECT COUNT(
                CASE
                    WHEN loan_item.li_returned_at IS NOT NULL 
                    AND DATEDIFF(loan_item.li_returned_at, loan_item.li_duedate) > 0 THEN 1

                    WHEN loan_item.li_returned_at IS NULL 
                AND loan_item.li_duedate < CURRENT_DATE THEN 1
                END) AS Overdue
            FROM loan
            JOIN loan_item ON loan_item.loan_id = loan.loan_id;
            ")->fetchAll();
        }


        function insertAuthor($firstname, $lastname, $birth_year, $nationality) {
            $con = $this->opencon();
            
            try{
                $con->beginTransaction();
                $stmt = $con->prepare("INSERT INTO Author (author_firstname, author_lastname, author_birth_year, author_nationality) VALUES(?,?,?,?)");
                $stmt->execute([$firstname, $lastname, $birth_year, $nationality]); 
                $author_id = $con->lastInsertId();
                $con->commit();
                return true;

            }catch(PDOException $e){
                if($con->inTransaction()){  
                    $con->rollBack();
                    return false;
                }
            }
        }

            function insertGenre($genre_name) {
                $con = $this->opencon();

                try {
                    $con->beginTransaction();
                    $stmt = $con->prepare("INSERT INTO Genre (genre_name) VALUES(?)");
                    $stmt->execute([$genre_name]);
                    $genre_id = $con->lastInsertId();
                    $con->commit();
                    return true;

                } catch (PDOException $e) {
                    if ($con->inTransaction()) {
                        $con->rollBack();
                    }
                    throw $e; 
                    
                }
            }

            function deleteBooks($book_id) {
            $con = $this->opencon();

            try {
                $con->beginTransaction();

                $stmtCopies = $con->prepare("DELETE FROM book_copy WHERE book_id = ?");
                $stmtCopies->execute([$book_id]);

                $stmtBG = $con->prepare("DELETE FROM book_genre WHERE book_id = ?");
                $stmtBG->execute([$book_id]);

                $stmtBA = $con->prepare("DELETE FROM book_authors WHERE book_id = ?");
                $stmtBA->execute([$book_id]);

                $stmtBook = $con->prepare("DELETE FROM books WHERE book_id = ?");
                $stmtBook->execute([$book_id]);

                $con->commit();
                return true;

            } catch (PDOException $e) {
                if ($con->inTransaction()) {
                    $con->rollBack();
                }
                throw $e;
            }
        }

            function deleteAuthor($author_id) {
            $con = $this->opencon();

                try {
                    $con->beginTransaction();

                    $stmtBA = $con->prepare("DELETE FROM book_authors WHERE author_id = ?");
                    $stmtBA->execute([$author_id]);

                    $stmtAuthor = $con->prepare("DELETE FROM Author WHERE author_id = ?");
                    $stmtAuthor->execute([$author_id]);

                    $con->commit();
                    return true;

                } catch (PDOException $e) {
                    if ($con->inTransaction()) {
                        $con->rollBack();
                    }
                    throw $e;
                }
            }

            function deleteGenre($genre_id) {
                $con = $this->opencon();

                try {
                    $con->beginTransaction();

                    $stmtBG = $con->prepare("DELETE FROM book_genre WHERE genre_id = ?");
                    $stmtBG->execute([$genre_id]);

                    $stmtGenre = $con->prepare("DELETE FROM Genre WHERE genre_id = ?");
                    $stmtGenre->execute([$genre_id]);

                    $con->commit();
                    return true;

                } catch (PDOException $e) {
                    if ($con->inTransaction()) {
                        $con->rollBack();
                    }
                    throw $e;
                }
            }

            function updateAuthor($author_id, $firstname, $lastname, $birth_year, $nationality) {
                $con = $this->opencon();

                try {
                    $con->beginTransaction();

                    $stmt = $con->prepare("UPDATE Author SET author_firstname = ?, author_lastname = ?, 
                    author_birth_year = ?, author_nationality = ? WHERE author_id = ?");
                    $stmt->execute([$firstname, $lastname, $birth_year, $nationality, $author_id]);

                    $con->commit();
                    return true;

                } catch (PDOException $e) {
                    if ($con->inTransaction()) {
                        $con->rollBack();
                    }
                    throw $e;
                }
            }

             function updateGenre($genre_id, $genre_name) {
                $con = $this->opencon();

                try {
                    $con->beginTransaction();

                    $stmt = $con->prepare("UPDATE Genre SET genre_name = ? WHERE genre_id = ?");
                    $stmt->execute([$genre_name, $genre_id]);

                    $con->commit();
                    return true;

                } catch (PDOException $e) {
                    if ($con->inTransaction()) {
                        $con->rollBack();
                    }
                    throw $e;
                }
            }

            function getActiveBorrowers(){
                $con = $this->opencon();
                return $con->query("SELECT borrower_id, CONCAT(borrower_firstname,' ',borrower_lastname) AS borrower_name  FROM borrowers WHERE is_active = 1; ")->fetchAll();


            }

            function getAvailableCopies(){
               $con = $this->opencon();
               return $con->query("SELECT
               book_copy.copy_id,
               books.book_id,
               books.book_title
               FROM books
               JOIN book_copy on books.book_id = book_copy.book_id
               WHERE book_copy.bc_status = 'AVAILABLE'
               ORDER BY books.book_title")->fetchAll();

            }
            function createLoanWithItems($borrower_id, $user_id, $copy_id, $li_duedate, $condition_out){
            $con = $this->opencon();

            try {
            $con->beginTransaction();

            $insertLoanStmt = $con->prepare("INSERT INTO Loan (borrower_id, user_id, loan_status, loan_date) VALUES (?, ?, 'OPEN', NOW())");
                $insertLoanStmt->execute([$borrower_id, $user_id]);
                $loan_id = $con->lastInsertId();

                $checkCopyStmt = $con->prepare("SELECT bc_status FROM book_copy WHERE copy_id = ?");

                $activeLoanStmt = $con->prepare("SELECT COUNT(*) as active_count FROM loan_item
                    JOIN Loan ON loan_item.loan_id = Loan.loan_id
                    WHERE loan_item.copy_id = ?
                    AND loan_item.li_returned_at IS NULL
                    AND Loan.loan_status = 'OPEN' ");


                $insertLoanItemStmt = $con->prepare("INSERT INTO loan_item(loan_id, copy_id, li_duedate, condition_out) VALUES (?,?,?,?) ");

                $updateCopyStmt = $con->prepare("UPDATE book_copy SET bc_status ='ON_LOAN' WHERE copy_id = ? ");
                
                foreach ($copy_id as $copy_id) {

                $checkCopyStmt->execute([$copy_id]);
                $copyStatus = $checkCopyStmt->fetch();

                if (!$copyStatus) {
                    throw new Exception("Copy ID $copy_id does not exist.");
                }

                if ($copyStatus['bc_status'] !== 'AVAILABLE') {
                    throw new Exception("Copy ID $copy_id is not available.");
                }

                $activeLoanStmt->execute([$copy_id]);
                $activeLoan = $activeLoanStmt->fetch();

                if ($activeLoan['active_count'] > 0) {
                    throw new Exception("Copy already on active loan.");
                }

                $insertLoanItemStmt->execute([$loan_id, $copy_id, $li_duedate, $condition_out]);
                $updateCopyStmt->execute([$copy_id]);
            }
                $con->commit();
                return $loan_id;

                } catch (Exception $e) {
                    if ($con->inTransaction()) {
                        $con->rollBack();
                    }
                    throw $e;
                }
        }
    }





