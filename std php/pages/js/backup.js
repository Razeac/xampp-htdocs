$( document ).ready(function() {
    var ajax_link = "lib/class/pod.class.php";
    var remarks="";
    var slip="";

    $('.student_id').focus();
      
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

    function focus() {
        $('.student_id').focus();
        setTimeout( focus, 1 );
    }
    focus();

    function pingserver() {
        $.Ping("http://192.168.2.235" /*, optional timeout */).done(function (success, url, time, on) {
            data="Server Connection Success";
            $(".connection_status").removeClass("alert-danger").addClass( "alert-success" );
            $(".connection_status").html(data);
        }).fail(function (failure, url, time, on) {
            data="Server Connection Failed";
            $(".connection_status").removeClass("alert-success").addClass( "alert-danger" );
            $(".connection_status").html(data);
        });
        setTimeout( pingserver, 10000 );
    }
    pingserver();
    
    function checkclient() {
        var ip = $(".client_ip").text();
        $.ajax({
            type: "POST",
            url: ajax_link,
            data: 'ip='+ ip + '&request_type=' + 'checkclient',
            dataType: 'json',
            success: function(info) {
                if(info.ab == 0) {
                    data="Client Not Connected to the server";
                    $(".student_id").prop( "disabled", true );
                    $(".connection_status").removeClass("alert-success").addClass( "alert-danger" );
                    $(".connection_status").html(data);
                }
            }
        });
        setTimeout( checkclient, 2000 );
    }
    checkclient();

    function checkrefresh() {
        var ip = $(".client_ip").text();
        $.ajax({
            type: "POST",
            url: ajax_link,
            data: 'ip='+ ip + '&request_type=' + 'checkrefresh',
            dataType: 'json',
            success: function(info) {
                if(info.ab == 1) {
                    stoprefresh();
                    location.reload();
                }
            }
        });
        setTimeout( checkrefresh, 10000 );
    }
    checkrefresh();

    function stoprefresh() {
        var ip = $(".client_ip").text();
        $.ajax({
            type: "POST",
            url: ajax_link,
            data: 'ip='+ ip + '&request_type=' + 'stoprefresh',
            dataType: 'json',
            success: function(info) {
                return true;
            }
        });
    }

    $(".client_connect").click(function() {
        client_ip();
    });

    function client_ip() {
        var name = $(".client_name").text();
        var ip = $(".client_ip").text();
        console.log(ip);
        $.ajax({
            type: "POST",
            url: ajax_link,
            data: 'name='+ name + '&ip='+ ip + '&request_type=' + 'client_ip',
            dataType: 'json',
            success: function(info) {
            }
        });
    }

    function auto_script() {
        var hours = $('.hours').text(); 
        var minutes = $('.minutes').text(); 
        var seconds = $('.seconds').text(); 
        var median = $('.median').text(); 

        var time = hours + ":" + minutes + ":" + seconds + " " + median;
        console.log(time);
        if (regex_get24Hr(time) >= regex_get24Hr("05:00:00 AM") && regex_get24Hr(time) <= regex_get24Hr("07:30:00 AM")) {
            remarks="Present AM";
            slip="none";
            $(".student_id").prop( "disabled", false );
        } else if (regex_get24Hr(time) >= regex_get24Hr("07:30:01 AM") && regex_get24Hr(time) <= regex_get24Hr("08:00:00 AM")) {
            remarks="Late AM";
            slip="late";
            $(".student_id").prop( "disabled", false );
        } else if (regex_get24Hr(time) >= regex_get24Hr("08:00:01 AM") && regex_get24Hr(time) <= regex_get24Hr("12:00:00 PM")) {
            remarks="Absent AM";
            slip="absent";
            $(".student_id").prop( "disabled", true );
            $(".student_id").val('');
            console.log("am");
        } else if (regex_get24Hr(time) >= regex_get24Hr("12:00:01 PM") && regex_get24Hr(time) <= regex_get24Hr("01:30:00 PM")) {
            remarks="Present PM";
            slip="none";
            $(".student_id").prop( "disabled", false );
        } else if (regex_get24Hr(time) >= regex_get24Hr("01:30:01 PM") && regex_get24Hr(time) <= regex_get24Hr("02:00:00 PM")) {
            remarks="Late PM";
            slip="late";
            $(".student_id").prop( "disabled", false );
        } else {
            remarks="Absent PM";
            slip="absent";
            $(".student_id").prop( "disabled", true );
            $(".student_id").val('');
            console.log("pm");
        }
        setTimeout( auto_script, 1000 );
    }
    auto_script();

    $( '.student_id' ).keypress(function (e) {
        var key = e.which;
        if(key == 13) {
            console.log($(this).val());
     
            var $this = $(this);
            $this.select();

            $this.mouseup(function() {
                $this.unbind("mouseup");
                return false;
            });
        }
        
        var student_id = $(this).val();
        
        var hours = $('.hours').text(); 
        var minutes = $('.minutes').text(); 
        var seconds = $('.seconds').text(); 
        var median = $('.median').text(); 

        var time = hours + ":" + minutes + ":" + seconds + " " + median;

        var image_cnt = $(".student_picture");

        $.ajax({ 
            type: "POST", 
            url: ajax_link, 
            data: 'student_id='+ student_id + '&remarks='+ remarks + '&slip='+ slip  + '&median='+ median + '&request_type=' + 'student_id',
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
                    $(".time_in").text(time);
                }
            }
        });
        return false;
    });
    
    function regex_get24Hr(time){
        var hours = Number(time.match(/^(\d+)/)[1]);
        var minutes = Number(time.match(/:(\d+)/)[1]);
        var seconds = Number(time.match(/:(\d+):(\d+)/)[2]);

        var AMPM = time.match(/\s(.*)$/)[1];
        if(AMPM == "PM" && hours<12) hours = hours+12;
        if(AMPM == "AM" && hours==12) hours = hours-12;
        
  
        hours = hours*100+minutes+seconds;
        return hours;
    }

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
        return latest_time = sHours + ":" + sMinutes + ":" + seconds;
    }

});
  
  
  
  