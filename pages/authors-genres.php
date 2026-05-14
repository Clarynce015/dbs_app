<?php
require_once('../classes/database.php');
$con = new database();

$allauthors = $con->viewauthors();
$allgenre = $con->viewgenre();

$authorCreateStatus  = null;
$authorCreateMessage = '';
$genreCreateStatus   = null;
$genreCreateMessage  = '';
$authorDeleteStatus  = null;
$authorDeleteMessage = '';
$genreDeleteStatus   = null;
$genreDeleteMessage  = '';
$authorUpdateStatus  = null;
$authorUpdateMessage = '';
$genreUpdateStatus   = null;
$genreUpdateMessage  = '';


if (isset($_POST['add_author'])) {
    $firstname   = $_POST['author_firstname'];
    $lastname    = $_POST['author_lastname'];
    $birth_year  = $_POST['author_birth_year'] ?? null;
    $nationality = $_POST['author_nationality'] ?? null;

    try {
        $con->insertAuthor($firstname, $lastname, $birth_year, $nationality);
        $authorCreateStatus  = 'success';
        $authorCreateMessage = 'Author added successfully.';
    } catch (Exception $e) {
        $authorCreateStatus  = 'error';
        $authorCreateMessage = 'Error adding author. Please try again.';
    }
}

if (isset($_POST['add_genre'])) {
    $genre_name = $_POST['genre_name'];

    try {
        $con->insertGenre($genre_name);
        $genreCreateStatus  = 'success';
        $genreCreateMessage = 'Genre added successfully.';
    } catch (Exception $e) {
        $genreCreateStatus  = 'error';
        if (strpos($e->getMessage(), 'Duplicate') !== false || $e->getCode() == 23000) {
            $genreCreateMessage = 'This genre already exists in the database.';
        } else {
            $genreCreateMessage = 'An error occurred while adding the genre. Please try again.';
        }
    }
}

if (isset($_POST['delete_author'])) {
    $author_id   = $_POST['author_id'];
    $author_name = $_POST['author_name'];

    try {
        $con->deleteAuthor($author_id);
        $authorDeleteStatus  = 'success';
        $authorDeleteMessage = '"' . $author_name . '" has been deleted successfully.';
    } catch (Exception $e) {
        $authorDeleteStatus  = 'error';
        $authorDeleteMessage = 'Cannot delete "' . $author_name . '". They may be assigned to existing books.';
    }
}

if (isset($_POST['delete_genre'])) {
    $genre_id   = $_POST['genre_id'];
    $genre_name = $_POST['genre_name'];

    try {
        $con->deleteGenre($genre_id);
        $genreDeleteStatus  = 'success';
        $genreDeleteMessage = '"' . $genre_name . '" has been deleted successfully.';
    } catch (Exception $e) {
        $genreDeleteStatus  = 'error';
        $genreDeleteMessage = 'Cannot delete "' . $genre_name . '". It may be assigned to existing books.';
    }
}

if (isset($_POST['update_author'])) {
    $author_id   = $_POST['author_id'];
    $firstname   = $_POST['author_firstname'];
    $lastname    = $_POST['author_lastname'];
    $birth_year  = $_POST['author_birth_year'] ?? null;
    $nationality = $_POST['author_nationality'] ?? null;

    try {
        $con->updateAuthor($author_id, $firstname, $lastname, $birth_year, $nationality);
        $authorUpdateStatus  = 'success';
        $authorUpdateMessage = 'Author updated successfully.';
    } catch (Exception $e) {
        $authorUpdateStatus  = 'error';
        $authorUpdateMessage = 'Error updating author. Please try again.';
    }
}

if (isset($_POST['update_genre'])) {
    $genre_id   = $_POST['genre_id'];
    $genre_name = $_POST['genre_name'];

    try {
        $con->updateGenre($genre_id, $genre_name);
        $genreUpdateStatus  = 'success';
        $genreUpdateMessage = 'Genre updated successfully.';
    } catch (Exception $e) {
        $genreUpdateStatus  = 'error';
        $genreUpdateMessage = 'Error updating genre. Please try again.';
    }
}


$allauthors = $con->viewauthors();
$allgenre   = $con->viewgenre();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Authors and Genres - Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.css">
  <link rel="stylesheet" href="../sweetalert/dist/sweetalert2.css">
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="admin-dashboard.php">Library Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navAdminStatic">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navAdminStatic" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto gap-lg-1">
        <li class="nav-item"><a class="nav-link" href="admin-dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="books.php">Books</a></li>
        <li class="nav-item"><a class="nav-link active" href="authors-genres.php">Authors &amp; Genres</a></li>
        <li class="nav-item"><a class="nav-link" href="borrowers.php">Borrowers</a></li>
        <li class="nav-item"><a class="nav-link" href="checkout.php">Checkout</a></li>
        <li class="nav-item"><a class="nav-link" href="return.php">Return</a></li>
        <li class="nav-item"><a class="nav-link" href="catalog.php">Catalog</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <span class="badge badge-soft">Role: ADMIN</span>
        <a class="btn btn-sm btn-outline-secondary" href="login.php">Logout</a>
      </div>
    </div>
  </div>
</nav>

<main class="container py-4">
  <div class="row g-3">

   
    <div class="col-12 col-lg-6">
      <div class="card p-4 h-100">
        <h5 class="mb-1">Add Author</h5>
        <p class="small-muted mb-3">Sample form for the Authors table.</p>
        <form action="#" method="POST" class="row g-2">
          <div class="col-12 col-md-6">
            <label class="form-label">First Name</label>
            <input class="form-control" name="author_firstname" placeholder="e.g., Jose" required />
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label">Last Name</label>
            <input class="form-control" name="author_lastname" placeholder="e.g., Rizal" required />
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label">Birth Year</label>
            <input class="form-control" name="author_birth_year" type="number" min="1" max="2100" placeholder="optional" />
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label">Nationality</label>
            <input class="form-control" name="author_nationality" placeholder="optional" />
          </div>
          <div class="col-12">
            <button name="add_author" class="btn btn-primary w-100" type="submit">Save Author</button>
          </div>
        </form>
      </div>
    </div>

  
    <div class="col-12 col-lg-6">
      <div class="card p-4 h-100">
        <h5 class="mb-1">Add Genre</h5>
        <p class="small-muted mb-3">Sample form for the Genres table.</p>
        <form action="#" method="POST" class="row g-2">
          <div class="col-12">
            <label class="form-label">Genre Name</label>
            <input class="form-control" name="genre_name" placeholder="e.g., Classic" required />
          </div>
          <div class="col-12">
            <button name="add_genre" class="btn btn-outline-primary w-100" type="submit">Save Genre</button>
          </div>
        </form>
      </div>
    </div>

    
    <div class="col-12 col-lg-8">
      <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Authors List</h5>
          <span class="small-muted">Total: <?php echo count($allauthors); ?></span>
        </div>
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>Author ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Birth Year</th>
                <th>Nationality</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($allauthors as $author): ?>
                <tr>
                  <td><?php echo $author['author_id']; ?></td>
                  <td><?php echo htmlspecialchars($author['author_firstname']); ?></td>
                  <td><?php echo htmlspecialchars($author['author_lastname']); ?></td>
                  <td><?php echo $author['author_birth_year']; ?></td>
                  <td><?php echo htmlspecialchars($author['author_nationality']); ?></td>
                  <td class="text-end">
                    <button class="btn btn-sm btn-outline-primary me-1"
                      data-bs-toggle="modal"
                      data-bs-target="#editAuthorModal"
                      data-author-id="<?php echo $author['author_id']; ?>"
                      data-author-firstname="<?php echo htmlspecialchars($author['author_firstname']); ?>"
                      data-author-lastname="<?php echo htmlspecialchars($author['author_lastname']); ?>"
                      data-author-birth-year="<?php echo $author['author_birth_year']; ?>"
                      data-author-nationality="<?php echo htmlspecialchars($author['author_nationality']); ?>"
                    >Edit</button>
                    <button class="btn btn-sm btn-outline-danger"
                      data-bs-toggle="modal"
                      data-bs-target="#deleteAuthorModal"
                      data-author-id="<?php echo $author['author_id']; ?>"
                      data-author-name="<?php echo htmlspecialchars($author['author_firstname'] . ' ' . $author['author_lastname']); ?>"
                    >Delete</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>


    <div class="col-12 col-lg-4">
      <div class="card p-4 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Genres List</h5>
          <span class="small-muted">Total: <?php echo count($allgenre); ?></span>
        </div>
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>Genre ID</th>
                <th>Genre Name</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($allgenre as $genre): ?>
                <tr>
                  <td><?php echo $genre['genre_id']; ?></td>
                  <td><?php echo htmlspecialchars($genre['genre_name']); ?></td>
                  <td class="text-end">
                    <button class="btn btn-sm btn-outline-primary me-1"
                      data-bs-toggle="modal"
                      data-bs-target="#editGenreModal"
                      data-genre-id="<?php echo $genre['genre_id']; ?>"
                      data-genre-name="<?php echo htmlspecialchars($genre['genre_name']); ?>"
                    >Edit</button>
                    <button class="btn btn-sm btn-outline-danger"
                      data-bs-toggle="modal"
                      data-bs-target="#deleteGenreModal"
                      data-genre-id="<?php echo $genre['genre_id']; ?>"
                      data-genre-name="<?php echo htmlspecialchars($genre['genre_name']); ?>"
                    >Delete</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</main>


<div class="modal fade" id="editAuthorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Author</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="#" method="POST">
          <div class="mb-3">
            <label class="form-label">Author ID</label>
            <input class="form-control" id="edit_author_id" name="author_id" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">First Name</label>
            <input class="form-control" id="edit_author_firstname" name="author_firstname" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Last Name</label>
            <input class="form-control" id="edit_author_lastname" name="author_lastname" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Birth Year</label>
            <input class="form-control" type="number" min="1" max="2100" id="edit_author_birth_year" name="author_birth_year">
          </div>
          <div class="mb-3">
            <label class="form-label">Nationality</label>
            <input class="form-control" id="edit_author_nationality" name="author_nationality">
          </div>
          <button name="update_author" class="btn btn-primary w-100" type="submit">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="deleteAuthorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Author</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete <strong id="delete_author_name_display"></strong>?</p>
        <p class="text-danger small">This action cannot be undone. The author will also be removed from any assigned books.</p>
        <form action="#" method="POST">
          <input type="hidden" name="author_id"   id="delete_author_id">
          <input type="hidden" name="author_name" id="delete_author_name">
          <div class="d-flex gap-2 justify-content-end">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger" name="delete_author">Delete</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="editGenreModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Genre</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="#" method="POST">
          <div class="mb-3">
            <label class="form-label">Genre ID</label>
            <input class="form-control" id="edit_genre_id" name="genre_id" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Genre Name</label>
            <input class="form-control" id="edit_genre_name" name="genre_name" required>
          </div>
          <button name="update_genre" class="btn btn-primary w-100" type="submit">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="deleteGenreModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Genre</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete <strong id="delete_genre_name_display"></strong>?</p>
        <p class="text-danger small">This action cannot be undone. The genre will also be removed from any assigned books.</p>
        <form action="#" method="POST">
          <input type="hidden" name="genre_id"   id="delete_genre_id">
          <input type="hidden" name="genre_name" id="delete_genre_name">
          <div class="d-flex gap-2 justify-content-end">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger" name="delete_genre">Delete</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
<script src="../sweetalert/dist/sweetalert2.js"></script>

<script>

  const editAuthorModal = document.getElementById('editAuthorModal');
  editAuthorModal.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    if (!btn) return;
    document.getElementById('edit_author_id').value          = btn.getAttribute('data-author-id')          || '';
    document.getElementById('edit_author_firstname').value   = btn.getAttribute('data-author-firstname')   || '';
    document.getElementById('edit_author_lastname').value    = btn.getAttribute('data-author-lastname')    || '';
    document.getElementById('edit_author_birth_year').value  = btn.getAttribute('data-author-birth-year')  || '';
    document.getElementById('edit_author_nationality').value = btn.getAttribute('data-author-nationality') || '';
  });

  
  const deleteAuthorModal = document.getElementById('deleteAuthorModal');
  deleteAuthorModal.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    if (!btn) return;
    document.getElementById('delete_author_id').value                  = btn.getAttribute('data-author-id')   || '';
    document.getElementById('delete_author_name').value                = btn.getAttribute('data-author-name') || '';
    document.getElementById('delete_author_name_display').textContent  = btn.getAttribute('data-author-name') || '';
  });


  const editGenreModal = document.getElementById('editGenreModal');
  editGenreModal.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    if (!btn) return;
    document.getElementById('edit_genre_id').value   = btn.getAttribute('data-genre-id')   || '';
    document.getElementById('edit_genre_name').value = btn.getAttribute('data-genre-name') || '';
  });


  const deleteGenreModal = document.getElementById('deleteGenreModal');
  deleteGenreModal.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    if (!btn) return;
    document.getElementById('delete_genre_id').value                  = btn.getAttribute('data-genre-id')   || '';
    document.getElementById('delete_genre_name').value                = btn.getAttribute('data-genre-name') || '';
    document.getElementById('delete_genre_name_display').textContent  = btn.getAttribute('data-genre-name') || '';
  });


  const authorCreateStatus  = <?php echo json_encode($authorCreateStatus ?? null); ?>;
  const authorCreateMessage = <?php echo json_encode($authorCreateMessage ?? null); ?>;
  if (authorCreateStatus === 'success') {
    Swal.fire({ icon: 'success', title: 'Success', text: authorCreateMessage, confirmButtonText: 'OK' });
  } else if (authorCreateStatus === 'error') {
    Swal.fire({ icon: 'error',   title: 'Error',   text: authorCreateMessage, confirmButtonText: 'OK' });
  }

  
  const genreCreateStatus  = <?php echo json_encode($genreCreateStatus ?? null); ?>;
  const genreCreateMessage = <?php echo json_encode($genreCreateMessage ?? null); ?>;
  if (genreCreateStatus === 'success') {
    Swal.fire({ icon: 'success', title: 'Success', text: genreCreateMessage, confirmButtonText: 'OK' });
  } else if (genreCreateStatus === 'error') {
    Swal.fire({ icon: 'error',   title: 'Error',   text: genreCreateMessage, confirmButtonText: 'OK' });
  }

  
  const authorDeleteStatus  = <?php echo json_encode($authorDeleteStatus ?? null); ?>;
  const authorDeleteMessage = <?php echo json_encode($authorDeleteMessage ?? null); ?>;
  if (authorDeleteStatus === 'success') {
    Swal.fire({ icon: 'success', title: 'Deleted!',      text: authorDeleteMessage, confirmButtonText: 'OK' });
  } else if (authorDeleteStatus === 'error') {
    Swal.fire({ icon: 'error',   title: 'Cannot Delete', text: authorDeleteMessage, confirmButtonText: 'OK' });
  }

  const genreDeleteStatus  = <?php echo json_encode($genreDeleteStatus ?? null); ?>;
  const genreDeleteMessage = <?php echo json_encode($genreDeleteMessage ?? null); ?>;
  if (genreDeleteStatus === 'success') {
    Swal.fire({ icon: 'success', title: 'Deleted!',      text: genreDeleteMessage, confirmButtonText: 'OK' });
  } else if (genreDeleteStatus === 'error') {
    Swal.fire({ icon: 'error',   title: 'Cannot Delete', text: genreDeleteMessage, confirmButtonText: 'OK' });
  }

 
  const authorUpdateStatus  = <?php echo json_encode($authorUpdateStatus ?? null); ?>;
  const authorUpdateMessage = <?php echo json_encode($authorUpdateMessage ?? null); ?>;
  if (authorUpdateStatus === 'success') {
    Swal.fire({ icon: 'success', title: 'Updated!', text: authorUpdateMessage, confirmButtonText: 'OK' });
  } else if (authorUpdateStatus === 'error') {
    Swal.fire({ icon: 'error',   title: 'Error',    text: authorUpdateMessage, confirmButtonText: 'OK' });
  }

  const genreUpdateStatus  = <?php echo json_encode($genreUpdateStatus ?? null); ?>;
  const genreUpdateMessage = <?php echo json_encode($genreUpdateMessage ?? null); ?>;
  if (genreUpdateStatus === 'success') {
    Swal.fire({ icon: 'success', title: 'Updated!', text: genreUpdateMessage, confirmButtonText: 'OK' });
  } else if (genreUpdateStatus === 'error') {
    Swal.fire({ icon: 'error',   title: 'Error',    text: genreUpdateMessage, confirmButtonText: 'OK' });
  }
</script>

</body>
</html>
