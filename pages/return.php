<?php
require_once('../classes/database.php');
$con = new database();

$activeLoanItem = $con->getLoanItem();

$Status = NULL;
$Message = '';

if (isset($_POST['process_return'])) {
    $loan_item_id  = $_POST['loan_item_id'];
    $li_returned_at = $_POST['li_returned_at'];
    $condition_in = $_POST['condition_in'];

    try {
        $con->processLoanReturns($loan_item_id, $li_returned_at, $condition_in);

        $Status  = 'success';
        $Message = 'Book returned successfully.';
    } catch (Exception $e) {
        $Status  = 'error';
        $Message = 'Error failed to return. Please try again.';
    }
}







?>



<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Return — Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="admin-dashboard.php">Library Admin</a>
    <div class="ms-auto d-flex gap-2">
      <a class="btn btn-sm btn-outline-secondary" href="admin-dashboard.php">Back</a>
      <a class="btn btn-sm btn-outline-secondary" href="login.html">Logout</a>
    </div>
  </div>
</nav>

<?php if (isset($Status) && $Status): ?>
<div class="container py-3">

  <div class="alert alert-<?php echo $Status === 'success' ? 'success' : 'danger'; ?>">

    <strong>
      <?php echo $Status === 'success' ? 'Success!' : 'Error!'; ?>
    </strong>

    <?php echo $Message; ?>

  </div>

</div>
<?php endif; ?>

<main class="container py-4">
  <div class="card p-4">
    <h5 class="mb-1">Process Return</h5>
    <p class="small-muted mb-4">Update LoanItem.li_returned_at and condition_in; then update BookCopy.status.</p>

    <!-- Later in PHP: action="../php/loans/return.php" method="POST" -->
    <form action="#" method="POST" class="row g-3">
      <div class="col-12 col-md-4">
        <label class="form-label">Loan Item ID</label>
        <input class="form-control" name="loan_item_id" type="number" placeholder="e.g., 5006" required>
      </div>
      <div class="col-12 col-md-4">
        <label class="form-label">Returned At</label>
        <input class="form-control" name="li_returned_at" type="datetime-local" value="<?php echo date('Y-m-d\TH:i');?>">
      </div>
      <div class="col-12 col-md-4">
        <label class="form-label">Condition In</label>
        <select class="form-select" name="condition_in" required>
          <option value="GOOD">GOOD</option>
          <option value="DAMAGED">DAMAGED</option>
        </select>
      </div>




      <div class="col-12">
        <button name="process_return" class="btn btn-primary" type="submit">Confirm Return</button>
      </div>
    </form>
</div>


    
      <div class="col-13">
      <div class="card p-4 mb-3">
          <h6 class="mb-2">Checkout Rules Reminder</h6>
          <ul class="small-muted mb-0">
            <li>Loan must have a borrower_id.</li>
            <li>Loan must have processed_by_user_id (ADMIN).</li>
            <li><strong>Each copy can only be actively on loan once.</strong></li>
            <li>Loan requires at least one LoanItem.</li>
            <li>Copy status automatically changes to ON_LOAN.</li>
            <li>Loan status starts as OPEN and can be CLOSED or CANCELLED.</li>
          </ul>
      </div>
  
      <div class="card p-4">
      <h6 class="mb-3">Books for return</h6>
      <div class="table-responsive">
      <table class="table table-sm mb-0">
      <thead class="table-light">
      <tr>
      <th>Loan_Item_ID</th>
      <th>Book Title</th>
      <th>Due date</th>
      </tr>
        </thead>
          <tbody>

              <?php foreach ($activeLoanItem as $loanitem): ?>
              <tr>
              <td class="small"><?php echo htmlspecialchars($loanitem['loan_item_id']); ?></td>
              <td class="small"><?php echo htmlspecialchars($loanitem['book_title']); ?></td>
              <td class="small"><?php echo htmlspecialchars($loanitem['li_duedate']); ?></td>
              </tr>
              <?php endforeach; ?>
              <?php if (empty($activeLoanItem)): ?>
              <tr>
              <td colspan="2" class="text-center small-muted">No copies available</td>
              </tr>
              <?php endif; ?>
          </tbody>
      </table>
      </div>
      </div>
      </div>
        </div>


</main>






<script src="../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
<script src="../sweetalert/dist/sweetalert2.js"></script>
</body>
</html>