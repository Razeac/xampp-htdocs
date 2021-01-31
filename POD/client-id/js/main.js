$( document ).ready(function() {
    // var server_ip = "http://192.168.2.252";
    var ajax_link = "lib/class/pod.class.php";
    var remarks="";
    var slip="";
    
    var connected="";

    var title_ip = $(".client_ip").text();
    $(document).find("title").text("PODams--Client::"+title_ip);
    $('.student_id').focus();
    $('.faculty_id').focus();

    function showdate() {
        var currentdate = new Date(); 
    var datetime = currentdate.getFullYear() + "-"
                + (currentdate.getMonth()+1)  + "-" 
                + currentdate.getDate();
            $(".system_date").html(datetime);
    }
    showdate();
      
    function showTime() {
        var date = new Date();
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var seconds = date.getSeconds();
        var session = "AM"; 
  
        if ( hours == 0 ) {
            hours = 12;
        } 
  
        if( hours >= 12 ){
            session = "PM";
        }
  
        if ( hours > 12 ){
            hours = hours - 12;
        }
  
        hours = ( hours < 10 ) ? hours = "0" + hours : hours;
        minutes = ( minutes < 10 ) ? minutes = "0" + minutes : minutes;
        seconds = ( seconds < 10 ) ? seconds = "0" + seconds : seconds;
  
        var realtime = hours + ":" + minutes + ":" + seconds + " " + session;
        //console.log(realtime);
 
        $('.hours').text(hours); 
        $('.minutes').text(minutes); 
        $('.seconds').text(seconds); 
        $('.median').text(session); 
  
        setTimeout( showTime, 1000 );
    }
    showTime();

    
    function get24Hr(time) {
        var hours = Number(time.match(/^(\d+)/)[1]);
        var minutes = Number(time.match(/:(\d+)/)[1]);
        var seconds = Number(time.match(/:(\d+):(\d+)/)[2]);
        var AMPM = time.match(/\s(.*)$/)[1];
        if(AMPM == "PM" && hours<12) hours = hours+12;
        if(AMPM == "AM" && hours==12) hours = hours-12;
        var sHours = hours.toString();
        var sMinutes = minutes.toString();
        if(hours<10) sHours = "0" + sHours;
        if(minutes<10) sMinutes = "0" + sMinutes;
        return latest_time = sHours + ":" + sMinutes + ":";
    }

    function auto_script() {
        var format = 'hh:mm:ss';
        var hours = $('.hours').text(); 
        var minutes = $('.minutes').text(); 
        var seconds = $('.seconds').text(); 
        var median = $('.median').text(); 

        var proxy_time = hours + ":" + minutes + ":" + seconds + " " + median;
        var obj_24 = get24Hr(proxy_time)+seconds;

        var time = moment(obj_24,format);

        if (time.isBetween(moment('05:00:00', format), moment('07:30:00', format))) {
            remarks="Present AM";
            slip="none";
            $(".student_id").prop('disabled', false);
            console.log('present am');
        } else if (time.isBetween(moment('07:29:00', format), moment('08:00:00', format))) {
            remarks="Present AM";
            slip="none";
            $(".student_id").prop('disabled', false);
            console.log('present am');
        } else if (time.isBetween(moment('07:59:00', format), moment('12:15:00', format))) {
            remarks="Absent AM";
            slip="absent";
            $(".student_id").prop('disabled', true);
            console.log('absent am');
        } else if (time.isBetween(moment('12:14:00', format), moment('13:30:00', format))) {
            remarks="Present PM";
            slip="none";
            $(".student_id").prop('disabled', false);
            console.log('present pm');
        } else if (time.isBetween(moment('13:29:00', format), moment('14:00:00', format))) {
            remarks="Present PM";
            slip="none";
            $(".student_id").prop('disabled', false);
            console.log('present pm');
        } else if (time.isBetween(moment('13:59:59', format), moment('18:00:00', format))) {
            remarks="Absent PM";
            slip="absent";
            $(".student_id").prop('disabled', true);
            console.log('absent pm');
        } else {
            remarks="idle";
            slip="idle";
            $(".student_id").prop('disabled', true);
            console.log('idle');
        }
        setTimeout( auto_script, 1000 );
    }
    auto_script();

    function focus() {
        $('.student_id').focus();
        setTimeout( focus, 1 );
    }
    focus();

    $( '.student_id' ).keypress(function (e) {
        var key = e.which;
        if(key == 13) {
            console.log($(this).val());
            time_in();
            var $this = $(this);
            $this.select();

            $this.mouseup(function() {
                $this.unbind("mouseup");
                return false;
            });
        }
    });

    $( '.faculty_id' ).keypress(function (e) {
        var key = e.which;
        if(key == 13) {
            console.log($(this).val());
            time_fac_in();
            var $this = $(this);
            $this.select();

            $this.mouseup(function() {
                $this.unbind("mouseup");
                return false;
            });
        }
    });

    function time_fac_in() {
        var faculty_id = $('.faculty_id').val();
        var hours = $('.hours').text(); 
        var minutes = $('.minutes').text(); 
        var seconds = $('.seconds').text(); 
        var median = $('.median').text(); 
        var date = $('.system_date').text(); 

        var time = hours + ":" + minutes + ":" + seconds + " " + median;

        var image_cnt = $(".faculty_picture");

        $.ajax({ 
            type: "POST", 
            url: ajax_link, 
            data: 'faculty_id='+ faculty_id + '&hours='+ hours + '&minutes='+ minutes + '&seconds='+ seconds + '&median='+ median +  '&date='+ date +  '&request_type=' + 'faculty_id',
            dataType: 'json',
            success: function(info) { 
                console.log(info);
                if(info.check == "0") {
                    image_cnt.attr("src", info.img);
                    $(".name").text(info.name);
                    $(".am_time_in").text(info.am_in);
                    $(".am_time_out").text(info.am_out);
                    $(".pm_time_in").text(info.pm_in);
                    $(".pm_time_out").text(info.pm_out);
                    $(".grade_section").text(info.role);
                } else {
                    image_cnt.attr("src", info.img);
                    $(".name").text(info.name);
                    $(".am_time_in").text(info.am_in);
                    $(".am_time_out").text(info.am_out);
                    $(".pm_time_in").text(info.pm_in);
                    $(".pm_time_out").text(info.pm_out);
                    $(".grade_section").text(info.role);
                }
            }
        });
        return false;
    }
    
    function time_in() {
        var student_id = $('.student_id').val();
        
        var hours = $('.hours').text(); 
        var minutes = $('.minutes').text(); 
        var seconds = $('.seconds').text(); 
        var median = $('.median').text(); 
        var date = $('.system_date').text(); 

        var time = hours + ":" + minutes + ":" + seconds;

        var image_cnt = $(".student_picture");

        $.ajax({
            type: "POST", 
            url: ajax_link, 
            data: 'student_id='+ student_id + '&remarks='+ remarks + '&slip='+ slip  + '&median='+ median + '&time='+ time + '&date='+ date + '&request_type=' + 'student_id',
            dataType: 'json',
            success: function(info) { 
                console.log(info);
                if(info.ef == "0") {
                    image_cnt.attr("src", info.cd);
                    $(".name").text(info.bc);
                    $(".grade_section").text(info.bc);
                    $(".remarks").text(info.ab);
                    $(".time_in").text(info.ab);
                } else {
                    image_cnt.attr("src", info.cd);
                    $(".name").text(info.ab);
                    $(".grade_section").text(info.bc);
                    $(".remarks").text(info.de);
                    $(".time_in").text(info.ef);
                }
            }
        });
        return false;
    }
});
  
  
  
  