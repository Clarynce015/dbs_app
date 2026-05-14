<?php
require_once('../classes/database.php');
$con = new database();

$allbooks = $con->viewBooks();
$allauthors = $con->viewauthors();
$allgenre = $con->viewgenre();





$bookCreateStatus = null;
$bookCreateMessage = '';

$bookCopyCreateStatus = null;
$bookCopyCreateMessage = '';

$authorAssignStatus = null;
$authorAssignMessage = '';

$genreAssignStatus = null;
$genreAssignMessage = '';

$bookDeleteStatus = null;
$bookDeleteMessage = '';



if (isset($_POST['save_book'])) {
  $title = $_POST['book_title'];
  $isbn = $_POST['book_isbn'];
  $publication_year = $_POST['book_publication_year'];
  $edition = $_POST['book_edition'];
  $publisher = $_POST['book_publisher'];


  
  try {
    $con->insertBook($title, $isbn, $publication_year, $edition, $publisher);

    $bookCreateStatus = 'success';
    $bookCreateMessage = 'Book saved successfully';
  } catch (Exception $e) {
    $bookCreateStatus = 'error';
    $bookCreateMessage = 'Error saving book: ' ;
  }
}

if (isset($_POST['add_copy'])) {
    $bc_status = $_POST['status'];
    $book_id = $_POST['book_id'];
    
  
  try {
    $allbooks = $con->insertBookCopy($bc_status, $book_id);

    $bookCopyCreateStatus = 'success';
    $bookCopyCreateMessage = 'BookCopy saved successfully';
  } catch (Exception $e) {
    $bookCopyCreateStatus = 'error';
    $bookCopyCreateMessage = 'Error saving bookcopy: ';
  }
}

if (isset($_POST['assign_author'])) {
    $book_id = $_POST['book_id'];
    $author_id = $_POST['author_id'];

    try {
        $con->insertBookAuthor($book_id, $author_id);

        $authorAssignStatus = 'success';
        $authorAssignMessage = 'Author assigned to book successfully';
    } catch (Exception $e) {
        $authorAssignStatus = 'error';
        $authorAssignMessage = 'Error assigning author to book: ' . $e->getMessage();
    }
}

if (isset($_POST['assign_genre'])) {
    $book_id = $_POST['book_id'];
    $genre_id = $_POST['genre_id'];

    try {
        $con->insertBookGenre($book_id, $genre_id);

        $genreAssignStatus = 'success';
        $genreAssignMessage = 'Genre assigned to book successfully';
    } catch (Exception $e) {
        $genreAssignStatus = 'error';
        $genreAssignMessage = 'Error assigning genre to book: ' . $e->getMessage();
    }
}


if (isset($_POST['update_book'])) {
    $book_id = $_POST['edit_book_id'];
    $title = $_POST['edit_book_title'];
    $isbn = $_POST['edit_book_isbn'];
    $publication_year = $_POST['edit_book_publication_year'];
    $publisher = $_POST['edit_book_publisher'];

    try {
        $con->updateBook($book_id, $title, $isbn, $publication_year, $publisher);

        $updaateBookStatus = 'success';
        $updateBookMessage = 'Book updated successfully';
        
    } catch (Exception $e) {
        $updaateBookStatus = 'error'; 
        $updateBookMessage = 'Error updating book: ' . $e->getMessage();
    }
}

     if(isset($_POST['delete_book'])){
    
    
    $book_id = $_POST['book_id'];
    $book_title = $_POST['book_title'];
    $_SESSION['book_title'] = $book_title;


    try {

    
    $con->deletebooks($book_id);
    $_SESSION['success_message'] = $book_title . ' has been deleted in the database. ';
    header('Location: books.php');
    exit();
    
    


    } catch (Exception $e){
    $error_message = "Cannot delete this book. It may have active loans or active copies in use";
      


    }

}

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Books — Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.css">
  <link rel="stylesheet" href="../sweetalert/dist/sweetalert2.css">


</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="admin-dashboard.html">Library Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBooks">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navBooks" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto gap-lg-1">
        <li class="nav-item"><a class="nav-link" href="admin-dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="books.php">Books</a></li>
        <li class="nav-item"><a class="nav-link active" href="authors-genres.php">Authors &amp; Genres</a></li>
        <li class="nav-item"><a class="nav-link" href="borrowers.php">Borrowers</a></li>
        <li class="nav-item"><a class="nav-link" href="checkout.html">Checkout</a></li>
        <li class="nav-item"><a class="nav-link" href="return.html">Return</a></li>
        <li class="nav-item"><a class="nav-link" href="catalog.html">Catalog</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <span class="badge badge-soft">Role: ADMIN</span>
        <a class="btn btn-sm btn-outline-secondary" href="login.html">Logout</a>
      </div>
    </div>
  </div>
</nav>

<main class="container py-4">



  <div class="row g-3">
    <div class="col-12 col-lg-4">
      <div class="card p-4">
        <h5 class="mb-1">Add Book</h5>
        <p class="small-muted mb-3">Creates a row in <b>Books</b>.</p>

        <!-- Later in PHP: action="../php/books/create.php" method="POST" -->
        <form action="#" method="POST">
          <div class="mb-3">
            <label class="form-label">Title</label>
            <input class="form-control" name="book_title" required>
          </div>
          <div class="mb-3">
            <label class="form-label">ISBN</label>
            <input class="form-control" name="book_isbn" placeholder="optional">
          </div>
          <div class="mb-3">
            <label class="form-label">Publication Year</label>
            <input class="form-control" name="book_publication_year" type="number" min="1500" max="2100" placeholder="optional">
          </div>
          <div class="mb-3">
            <label class="form-label">Edition</label>
            <input class="form-control" name="book_edition" placeholder="optional">
          </div>
          <div class="mb-3">
            <label class="form-label">Publisher</label>
            <input class="form-control" name="book_publisher" placeholder="optional">
          </div>
          <button name="save_book" class="btn btn-primary w-100" type="submit">Save Book</button>
        </form>
      </div>

      <div class="card p-4 mt-3">
        <h6 class="mb-2">Add Copy</h6>
        <p class="small-muted mb-3">Creates a row in <b>BookCopy</b>.</p>
        <!-- Later in PHP: action="../php/copies/create.php" method="POST" -->
        <form action="#" method="POST">
          <div class="mb-3">
            <label class="form-label">Book</label>
            <select class="form-select" name="book_id" required>
               <?php
              foreach($allbooks as $books) {
                  echo '<option value="'.$books['book_id'] .'">'.$books['book_title']. '</option>';
                  }
              
              ?>
            
            </select>
          </div>
          <div class="mb-3">
            <label  class="form-label">Status</label>
            <select class="form-select" name="status" required>
              <option value="AVAILABLE">AVAILABLE</option>
              <option value="ON_LOAN">ON_LOAN</option>
              <option value="LOST">LOST</option>
              <option value="DAMAGED">DAMAGED</option>
              <option value="REPAIR">REPAIR</option>
            </select>
          </div>
          <button name="add_copy" class="btn btn-outline-primary w-100" type="submit">Add Copy</button>
        </form>
      </div>
    </div>

    <div class="col-12 col-lg-8">
      <div class="card p-4">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-end mb-3">
          <div>
            <h5 class="mb-1">Books List</h5>
            <div class="small-muted">Placeholder rows. Replace with PHP + MySQL output.</div>
          </div>
          <div class="d-flex gap-2">
            <input class="form-control" style="max-width: 260px;" placeholder="Search title / ISBN...">
            <button class="btn btn-outline-secondary">Search</button>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>Book ID</th>
                <th>Title</th>
                <th>ISBN</th>
                <th>Year</th>
                <th>Publisher</th>
                <th>Copies</th>
                <th>Available</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>

              <?php    
              $viewcopies = $con->viewCopies();
              foreach($viewcopies as $vw) {
              
              


             echo' <tr>';
             echo '<td>'.$vw['book_id']. '</td>';
             echo  '<td>'.$vw['book_title']. '</td>';
             echo  '<td>'.$vw['book_isbn']. '</td>';
             echo  '<td>'.$vw['book_publication_year']. '</td>';
             echo  '<td>'.$vw['book_publisher']. '</td>';
             echo  '<td>'.$vw['Copies']. '</td>';
             echo  '<td>'.$vw['Available_copies']. '</td>';
             echo   '<td class="text-end">';
            echo   '<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBookModal"

            data-book-id="'.$vw['book_id'].'"
            data-book-title="'.$vw['book_title'].'"
            data-book-isbn="'.$vw['book_isbn'].'"
            data-book-publication-year="'.$vw['book_publication_year'].'" 
            data-book-publisher="'.$vw['book_publisher'].'"   
            >Edit</button>';

            
            echo '<button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteBookModal"
                data-book-id="'.$vw['book_id'].'"
                data-book-title="'.htmlspecialchars($vw['book_title']).'">Delete</button>';
                          }

              ?>




            </tbody>
          </table>
        </div>

        <hr class="my-4">

        <div class="row g-3">
          <div class="col-12 col-lg-6">
            <div class="border rounded p-3">
              <h6 class="mb-2">Assign Author to Book</h6>
              <p class="small-muted mb-3">Creates a row in <b>BookAuthors</b>.</p>
              <!-- Later in PHP: action="../php/bookauthors/create.php" method="POST" -->
              <form action="#" method="POST" class="row g-2">
                <div class="col-12 col-md-6">
                  <select class="form-select" name="book_id" required>
                    <?php
                  foreach($allbooks as $books) {
                  echo '<option value="'.$books['book_id'] .'">'.$books['book_title']. '</option>';
                  }
              
                    ?>
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <select class="form-select" name="author_id" required>
                     <?php
                    foreach($allauthors as $authors) {
                    echo '<option value="'.$authors['author_id'] .'">'.$authors['author_firstname']. ' '.$authors['author_lastname']. '</option>';
                    }
              
                      ?>
                  </select>
                </div>
                <div class="col-12">
                  <button name="assign_author" class="btn btn-outline-primary w-100" type="submit">Assign</button>
                </div>
              </form>
              <div class="small-muted mt-2">Unique constraint prevents duplicate (book_id, author_id).</div>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="border rounded p-3">
              <h6 class="mb-2">Assign Genre to Book</h6>
              <p class="small-muted mb-3">Creates a row in <b>BookGenre</b>.</p>
              <!-- Later in PHP: action="../php/bookgenre/create.php" method="POST" -->
              <form action="#" method="POST" class="row g-2">
                <div class="col-12 col-md-6">
                  <select class="form-select" name="book_id" required>
                    <?php
                  foreach($allbooks as $books) {
                  echo '<option value="'.$books['book_id'] .'">'.$books['book_title']. '</option>';
                  }
              
                    ?>
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <select class="form-select" name="genre_id" required>
                      <?php
                      foreach($allgenre as $genre) {
                      echo '<option value="'.$genre['genre_id'] .'">'.$genre['genre_name']. '</option>';
                      }
                
                        ?>
                  </select>
                </div>
                <div class="col-12">
                  <button name="assign_genre" class="btn btn-outline-primary w-100" type="submit">Assign</button>
                </div>
              </form>
              <div class="small-muted mt-2">Unique constraint prevents duplicate (genre_id, book_id).</div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</main>

<!-- Edit Book Modal (UI only) -->
<div class="modal fade" id="editBookModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Book</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>


      <div class="modal-body">
        <!-- Later in PHP: load existing values -->
        <form action="#" method="POST">

          <div class="mb-3">
            <label class="form-label">Book ID</label>
            <input class="form-control" value="book_id" id="edit_book_id" name="edit_book_id" readonly>
          </div>


          <div class="mb-3">
            <label class="form-label">Title</label>
            <input class="form-control" value="book_title" id="edit_book_title" name="edit_book_title">
          </div>

          <div class="mb-3">
            <label class="form-label">ISBN</label>
            <input class="form-control" value="9789710810736" id="edit_book_isbn" name="edit_book_isbn">
          </div>

          <div class="mb-3">
            <label class="form-label">Publication Year</label>
            <input class="form-control" min="1500" max="2100" id="edit_book_publication_year" name="edit_book_publication_year">
          </div>

          <div class="mb-3">
            <label class="form-label">Publisher</label>
            <input class="form-control" value="National Book Store" id="edit_book_publisher" name="edit_book_publisher">
          </div>
          <button name="update_book" class="btn btn-primary w-100" type="submit">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteBookModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Book</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete <strong id="delete_book_title_display"></strong>?</p>
        <p class="text-danger small">This action cannot be undone.</p>
        <form action="#" method="POST">
          <input type="hidden" name="book_id" id="delete_book_id">
          <input type="hidden" name="book_title" id="delete_book_title">
          <div class="d-flex gap-2 justify-content-end">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger" name="delete_book">Delete</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
<script src="../sweetalert/dist/sweetalert2.js"></script>

<script> 

  


  const editBookModal = document.getElementById('editBookModal');

  editBookModal.addEventListener('show.bs.modal', function (event) {
  const btn = event.relatedTarget;
  if (!btn) return; 
  document.getElementById('edit_book_id').value = btn.getAttribute('data-book-id') || '';
  document.getElementById('edit_book_title').value = btn.getAttribute('data-book-title') || '';
  document.getElementById('edit_book_isbn').value = btn.getAttribute('data-book-isbn') || '';
  document.getElementById('edit_book_publication_year').value = btn.getAttribute('data-book-publication-year') || '';
  document.getElementById('edit_book_publisher').value = btn.getAttribute('data-book-publisher') || '';

});

  const deleteBookModal = document.getElementById('deleteBookModal');

  deleteBookModal.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    if (!btn) return;
    document.getElementById('delete_book_id').value = btn.getAttribute('data-book-id') || '';
    document.getElementById('delete_book_title').value = btn.getAttribute('data-book-title') || '';
    document.getElementById('delete_book_title_display').textContent = btn.getAttribute('data-book-title') || '';
  });



  const bookStatus = <?php echo json_encode($bookCreateStatus); ?>;
  const bookMessage = <?php echo json_encode($bookCreateMessage); ?>;

  const bookcopyStatus = <?php echo json_encode($bookCopyCreateStatus); ?>;
  const bookcopyMessage = <?php echo json_encode($bookCopyCreateMessage); ?>;

  const authorAssignStatus = <?php echo json_encode($authorAssignStatus); ?>;
  const authorAssignMessage = <?php echo json_encode($authorAssignMessage); ?>;

  const genreAssignStatus = <?php echo json_encode($genreAssignStatus); ?>;
  const genreAssignMessage = <?php echo json_encode($genreAssignMessage); ?>;

 


  if (bookStatus === 'success') {
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: bookMessage,
      confirmButtonText: 'OK'
    });
  } else if (bookStatus === 'error') {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: bookMessage,
      confirmButtonText: 'OK'
    });
  }else if (bookcopyStatus === 'success') {
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: bookcopyMessage,
      confirmButtonText: 'OK'
    });
  } else if (bookcopyStatus === 'error') {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: bookcopyMessage,
      confirmButtonText: 'OK'
    });
  } else if (authorAssignStatus === 'success') {
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: authorAssignMessage,
      confirmButtonText: 'OK'
    });
  } else if (authorAssignStatus === 'error') {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: authorAssignMessage,
      confirmButtonText: 'OK'
    });
  } else if (genreAssignStatus === 'success') {
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: genreAssignMessage,
      confirmButtonText: 'OK'
    });
  } else if (genreAssignStatus === 'error') {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: genreAssignMessage,
      confirmButtonText: 'OK'
    });
  }

</script>
<script>
 const bookUpdateStatus = <?php echo json_encode($updaateBookStatus); ?>;
const bookUpdateMessage = <?php echo json_encode($updateBookMessage); ?>;

  if (bookUpdateStatus === 'success') {
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: bookUpdateMessage,
      confirmButtonText: 'OK'
    });
  } else if (bookUpdateStatus === 'error') {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: bookUpdateMessage,
      confirmButtonText: 'OK'
    });
  }

  </script>

</body>
</html>