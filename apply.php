<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
$grades = getGrades();
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Apply - Greenfield Academy</title>
<link href="assets/css/style.css" rel="stylesheet">
</head><body>
<nav class="navbar"><div class="container"><a class="navbar-brand" href="index.php">Greenfield Academy</a></div></nav>
<div class="container form-wrap">
  <h2>Application Form</h2>
  <form id="applicationForm" action="includes/application_process.php" method="POST" enctype="multipart/form-data">
    <h3>Applicant Information</h3>
    <div class="row">
      <div class="col"><input name="full_name" placeholder="First name" required></div>
      <div class="col"><input name="surname" placeholder="Surname" required></div>
    </div>
    <div class="row">
      <div class="col"><input name="id_number" placeholder="ID Number"></div>
      <div class="col"><input type="date" name="dob" placeholder="Date of Birth"></div>
    </div>
    <div class="row">
      <div class="col"><input name="nationality" placeholder="Nationality"></div>
      <div class="col"><input name="race" placeholder="Race"></div>
    </div>
    <div class="row">
      <div class="col"><input name="phone" placeholder="Cell phone"></div>
      <div class="col"><input type="email" name="email" placeholder="Email" required></div>
    </div>
    <div class="row"><div class="col"><textarea name="parent_address" placeholder="Physical address"></textarea></div></div>

    <h3>Academic</h3>
    <div class="row">
      <div class="col">
        <select id="gradeSelect" name="grade" required>
          <option value="">Select Grade</option>
          <?php foreach($grades as $g): ?>
            <option value="<?php echo $g['id']; ?>"><?php echo htmlspecialchars($g['grade_name']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col" id="subjectsContainer"></div>
    </div>

    <h3>Next of Kin</h3>
    <div class="row">
      <div class="col"><input name="parent_name" placeholder="Parent/Guardian name"></div>
      <div class="col"><input name="parent_surname" placeholder="Parent/Guardian surname"></div>
    </div>
    <div class="row">
      <div class="col"><input name="parent_id_number" placeholder="Parent ID number"></div>
      <div class="col"><input name="parent_phone" placeholder="Parent phone"></div>
    </div>
    

    <h3>Documents</h3>
    <div class="row">
      <div class="col"><label>Applicant ID (PDF/JPG/PNG)</label><input type="file" name="applicant_id_file" required></div>
      <div class="col"><label>Parent ID</label><input type="file" name="parent_id_file"></div>
    </div>
    <div class="row"><div class="col"><label>Previous year report</label><input type="file" name="school_report_file"></div></div>

    <div class="row"><div class="col"><button class="btn btn-primary" type="submit">Submit Application</button></div></div>
  </form>
  <div id="successMsg" class="success-msg" style="display:none;">✅ Application successfully submitted!</div>
</div>

<script>
document.getElementById('gradeSelect').addEventListener('change', function(){
  var gradeId = this.value;
  var container = document.getElementById('subjectsContainer');
  container.innerHTML = 'Loading subjects...';
  if(!gradeId){ container.innerHTML = ''; return; }
  fetch('includes/fetch_subjects.php?grade_id=' + encodeURIComponent(gradeId))
    .then(r => r.json())
    .then(data => {
      if(data.length === 0){ container.innerHTML = '<p>No subjects.</p>'; return; }
      var html = '<label>Choose subjects (Ctrl/Cmd to select multiple)</label><select name=\"subjects[]\" multiple class=\"subjects-select\">';
      data.forEach(function(s){ html += '<option value=\"'+s.id+'\">'+s.subject_name+'</option>'; });
      html += '</select>';
      container.innerHTML = html;
    }).catch(e=>{ container.innerHTML = 'Error loading subjects'; });
});

if (window.location.search.indexOf('success=applied') !== -1) {
  document.getElementById('successMsg').style.display = 'block';
  window.scrollTo({top:0, behavior:'smooth'});
}
</script>
<?php include 'includes/footer.php'; ?>
</body></html>
