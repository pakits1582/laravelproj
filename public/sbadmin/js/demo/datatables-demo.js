// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#schoolTable').DataTable({
    'columnDefs': [
      {
          "targets": [0,4], // your case first column
          "className": "text-center"
     }]
  });

  $('#userTable').DataTable({
    'columnDefs': [
      {
          "targets": [1,3,4], // your case first column
          "className": "text-center"
     }]
  });
});
