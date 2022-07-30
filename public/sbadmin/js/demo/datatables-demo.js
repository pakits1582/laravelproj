// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#schoolTable').DataTable({
    'columnDefs': [
      {
          "targets": [0,4,5], // your case first column
          "className": "text-center"
     }]
  });
});
