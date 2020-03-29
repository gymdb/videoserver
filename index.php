<?php
include("database.php");
include("dropDownData.php");

?>

<html>
<head>
  <title>Video Server</title>

  <link rel="stylesheet" href="libs/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="libs/css/jquery.bootgrid.css"/>
  <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
  <script src="libs/jquery.min.js"></script>
  <script src="libs/bootstrap.min.js"></script>
  <script src="libs/jquery.bootgrid.js"></script>

  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #f1f1f1;
    }

    .box {
      width: 100%;
      padding: 10px;
      background-color: #fff;
      border: 1px solid #ccc;
      border-radius: 5px;
      #margin-top: 25px;
    }

    .container {
      max-width: 100%;
    }

    .glyphicon-refresh {
      line-height: 1.42857;
    }

    .search-field {
      width: 200px
    }

    tr {
      height: 65px;
    }
  </style>
</head>
<body>
<div class="container box">
  <div class="table-responsive">
    <table id="video_data" class="table table-condensed     table-bordered table-striped">
      <thead>
      <tr>
        <th data-column-id="title" class="col-sm-3" data-width="400px" data-height="65px">Titel</th>
        <th data-column-id="description" data-formatter="descFormatter">Beschreibung</th>
        <th data-column-id="form">Klasse</th>
        <th data-column-id="subject">Fach</th>

        <th data-column-id="duration">Dauer</th>
        <th data-column-id="uploadedOn">online seit</th>
        <th data-column-id="commands" data-formatter="commands" data-sortable="false">Aktion</th>
      </tr>
      </thead>
    </table>
  </div>
</body>
</html>
<script type="text/javascript" language="javascript">
  $(document).ready(function ()
  {
    count = 0;

    var videoTable = $('#video_data').bootgrid({
      ajax: true,
      rowSelect: true,
      post: function ()
      {
        return {
          id: "b0df282a-0d67-40e5-8558-c9e93b7befed"
        };
      },
      url: "Controller.php",
      formatters: {
        "commands": function (column, row)
        {
          return "<button data-row-id='" + row.id + "' class='fa fa-trash-o delete align-middle'></button> &nbsp;" +
            "<button  data-row-id='" + row.id + "'class='fa fa-pencil update align-middle'></button> &nbsp;" +
            "<button  data-row-id='" + row.id + "'onclick=javascript:window.open('video.html?name=" + row.fileName + "&path=" + row.fileLocation + "')><span class='fa fa-play align-middle'></span></button> &nbsp;"

        },
        "descFormatter": function (column, row)
        {
          column.width = "700px";
          row.height = "63px";
          column.height = "63px";
          row.description;
          return "<span title=\"" + row.description.trim() + "\">" + row.description.substr(0, 150) + (row.description.length > 150 ? "..." : "") + "</span>";
        }
      },
      labels: {
        search: "Suchen",
        infos: "Zeige {{ctx.start}} bis {{ctx.end}} von {{ctx.total}} Einträgen"
      }
    });

    $(document).on('submit', '#video_form', function (event)
    {
      event.preventDefault();
      var video_name = $('#video_name').val();
      var video_duration = $('#video_duration').val();
      var video_class = $('#video_class').val();
      var video_subject_name = $('#video_subject').val();
      var video_description = $('#video_description').val();
      var video_file = $('#video_file').val();
      var isHidden = ($("#fileDiv").is(":visible") == false);
      var form = $('#video_form').val();
      var form_data = $(this).serialize();
      if (video_name != '' && video_duration != '' && video_class != '' && video_subject != '' && video_description != '' && (video_file != '' || isHidden))
      {
        $.ajax({
          url: "Controller.php",
          method: "POST",
          //data:form_data,
          data: new FormData(this),
          processData: false,
          contentType: false,
          success: function (data)
          {
            $('#video_form')[0].reset();
            $('#videoModal').modal('hide');
            $('#video_data').bootgrid('reload');
          }
        });
      }
      else
      {
        alert("Bitte alle Felder ausfüllen");
      }
    });

    $(document).on("loaded.rs.jquery.bootgrid", function ()
    {

      if (count <= 0)
      {
        $('div[class="actions btn-group"').last().after("&nbsp;<button type=\"button\" id=\"add_button\" data-toggle=\"modal\" data-target=\"#videoModal\" class=\"btn btn-default \">Hinzufügen</button>");
      }
      count++;
      $('.bootgrid-header .search').width("400px");
      videoTable.find(".update").on("click", function (event)
      {

        var operation = "Edit";
        var video_id = $(this).data("row-id");
        $.ajax({
          url: "Controller.php",
          method: "POST",
          data: {video_id: video_id, operation: operation},
          dataType: "json",
          success: function (data)
          {
            $('#videoModal').modal('show');
            $('#video_name').val(data.title);
            $('#video_description').val(data.description);
            $('#video_duration').val(data.duration);
            $('#video_class').val(data.form);
            $('#video_subject').val(data.subject);
            $('#fileDiv').hide();


            $('.modal-title').text("Videodaten ändern");
            $('#video_id').val(video_id);
            $('#action').val("Aktualisieren");
            $('#operation').val("Update");
          }
        });
      });
    });

    $(document).on("loaded.rs.jquery.bootgrid", function ()
    {
      $('#add_button').click(function ()
      {
        $('#video_form')[0].reset();
        $('.modal-title').text("Video hinzufügen");
        $('#action').val("Hinzufügen");
        $('#fileDiv').show();
        $('#operation').val("Add");
      });

      videoTable.find(".delete").on("click", function (event)
      {
        var video_id = $(this).data("row-id")
        if (confirm("Are you sure you want to delete this?"))
        {
          var operation = "Delete";
          $.ajax({
            url: "Controller.php",
            method: "POST",
            data: {video_id: video_id, operation: operation},
            success: function (data)
            {
              $('#video_data').bootgrid('reload');
            }
          })
        }
        else
        {
          return false;
        }
      });


    });
  });
</script>
<div id="videoModal" class="modal fade">
  <div class="modal-dialog">
    <form method="post" enctype="multipart/form-data" id="video_form">

      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Video hinzufügen</h4>


        </div>
        <div class="modal-body">
          <label>Titel</label>
          <input type="text" name="video_name" id="video_name" class="form-control"/>
          <label>Dauer</label>
          <input type="text" name="video_duration" id="video_duration" class="form-control"/>
          <label>Klasse</label>

          <select name="video_class" id="video_class" class="form-control">
            <option value="">Klasse wählen</option>
            <?php echo $forms; ?>
          </select>
          <label>Fach</label>
          <select name="video_subject" id="video_subject" class="form-control">
            <option value="">Fach wählen</option>
            <?php echo $subjects; ?>
          </select>

          <label>Beschreibung</label><br>
          <textarea class="form-control" id="video_description" name="video_description" rows="6"></textarea>
          <br>
          <div class="custom-file" id="fileDiv">
            <label class="custom-file-label" for="customFileLangHTML" data-browse="Bestand kiezen">Datei
              hochladen</label>

            <input type="file" class="fcustom-file-label" name="video_file" id="video_file">
          </div>


        </div>
        <div class="modal-footer">
          <input type="hidden" name="video_id" id="video_id"/>
          <input type="hidden" name="operation" id="operation"/>
          <input type="submit" name="action" id="action" class="btn btn-success" value="Hinzufügen"/>
        </div>
      </div>
    </form>
  </div>
</div>


