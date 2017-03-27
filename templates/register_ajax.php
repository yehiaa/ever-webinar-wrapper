<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
<script>
  var webinar_id = "<?php echo $webinar_id?>";
</script>

<style type="text/css">
 .modal{
        position: fixed;
        z-index: 999;
        height: 100%;
        width: 100%;
        top: 0;
        left: 0;
        background-color: Black;
        filter: alpha(opacity=60);
        opacity: 0.6;
        -moz-opacity: 0.8;
    }

     .center{
        z-index: 1000;
        margin: 100px auto;
        padding: 10px;
        width: 130px;
        border-radius: 10px;
        filter: alpha(opacity=100);
        opacity: 1;
        -moz-opacity: 1;
    }
</style>
</head>
  <body>
      <div class="container-fluid" id="container">
       <div class="modal" style="display: none">
          <div class="center">
              <img alt="" src="/images/ajax-loader2.gif" />
          </div>
      </div>
          <div class="row">
            <div class="col-md-12">
              <div class="count-down"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
                <form method="post" action="<?php echo $url ?>" id="register-form" data-toggle="validator">
                  <div class="form-group">
                    <label for="schedule_id">Schedule</label>
                    <select required data-width="100%" class="selectpicker form-control" id="schedule_id" name="schedule_id">
                    </select>
                    <input type="hidden" name="webinar_id" value="<?php echo $webinar_id?>">
                  </div>
                  <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" required class="form-control input-lg" id="name" name="name" placeholder="Your Name" data-minlength="4">
                    <div class="help-block with-errors"></div>
                  </div>

                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" required class="form-control input-lg" id="email" placeholder="Your Email" name="email">
                    <div class="help-block with-errors"></div>
                  </div>
                  <button type="submit" class="btn btn-default btn-lg btn-block">Register</button>
                </form>
            </div>
          </div>
      </div>
    <script src="http://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdn.rawgit.com/mckamey/countdownjs/master/countdown.min.js"></script>

    <script>
    (function () {

      function populateSchedulField($selector, data) {
          for (var i = 0; i < data.schedules.length; i++) {
              $selector.append($("<option></option>")
                    .attr("value", data.schedules[i].schedule_id)
                    .text(
                      moment(data.schedules[i].isoUTC).format("dddd, MMMM Do YYYY, h:mm:ss a") + 
                      ((data.schedules[i].repetition.length > 0) ? "  - " + data.schedules[i].repetition : "")
              ));
          }//end for
          $selector.selectpicker('refresh');
      }

      function getData(url) {
          $('.modal').show();
          $.getJSON("http://localhost:8585/api/webinars/"+ webinar_id, function (data) {
              $schedule_idSelect = $('#schedule_id');
              populateSchedulField($schedule_idSelect, data);
              $('.modal').hide();
          }, function () {
              $('.modal').hide();
          });
      }


      // var a = moment("2017-03-27T01:00:00+00:00");
      // alert(moment().isBefore(a) )
      // alert(a.diff(moment(), 'minutes')) // 1

      getData();

    })()
    </script>
  </body>
</div>
</html>