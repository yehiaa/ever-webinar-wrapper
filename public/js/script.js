function populateSchedulField($selector, data) {
    var tzStr = moment.tz.guess();
    for (var i = 0; i < data.schedules.length; i++) {
        $selector.append($("<option></option>")
              .attr("value", data.schedules[i].schedule_id)
              .text(
                tzStr + " " +
                moment(
                  data.schedules[i].isoUTC).format("dddd, MMMM Do YYYY, h:mm:ss a") +
                ((data.schedules[i].repetition.length > 0) ? " - " + data.schedules[i].repetition : "")
        ));
    }//end for
    $selector.selectpicker('refresh');
}

function countDownTimer($selector, momentObj) {
  var currentTimeStampInSeconds = (new moment()).format("X");
  var FutureTimeStampInSeconds = momentObj.format("X");
  var timeDiffInSeconds = FutureTimeStampInSeconds - currentTimeStampInSeconds;
  var duration = moment.duration(timeDiffInSeconds*1000, 'milliseconds');
  var interval = 1000;

  setInterval(function(){
    duration = moment.duration(duration - interval , 'milliseconds');
      var html = "The webinar starts in ";
      // html += moment.duration(duration.hours(), "hours").humanize() + " ";
      // html += moment.duration(duration.minutes(), "minutes").humanize() + " ";
      // html += moment.duration(duration.seconds(), "seconds").humanize();
      html += duration.humanize() ;
      $selector.html(html);
  }, interval);
}

function getData(url) {
    $('.modal').show();
    var xhr = $.getJSON("/api/webinars/"+ webinar_id);
    xhr.done(function (data) {
      $schedule_idSelect = $('#schedule_id');
        populateSchedulField($schedule_idSelect, data);
        if (data.schedules) {
          if (data.schedules[0]) {
            countDownTimer($("#count-down"), new moment(data.schedules[0].isoUTC) );
          }
        }
        $('.modal').hide();
    });

    xhr.fail(function (err) {
        $('.modal').hide();
        console.error('something wrong happen', err.responseText)
    })
}