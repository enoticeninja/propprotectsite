var Slots = function(data){
    var $this = this;
    $this.windowHeight= $(window).height();
    $this.windowWidth = $(window).width();
    var today = new Date(),currentHour = today.getHours();
    var totalTimeSlots = 0;
    this.totalHours = 24;
    this.visibleWidth;
    this.totalVisibleSlots;
    this.slotTime = 60;///////In Minutes
    this.channelLogoWidth = 80;
    this.realtimeChannelsWidth = 120;
    this.slotWidth = 350;
    if($this.windowWidth < 1000){
        this.channelLogoWidth = 50;
        this.realtimeChannelsWidth = 70;
        this.slotWidth = 250;
    }
    this.currentSlot = 0;
    //if(currentHour == 0)currentHour = '00';
    this.currentHour = currentHour;

    $this.visibleWidth = $('.realtime-timer').width();
    $this.totalVisibleSlots = Math.floor($this.visibleWidth/$this.slotWidth);
    /* console.log($this.visibleWidth);
    console.log($this.totalVisibleSlots); */

    $this.pixelsPerMinute = $this.slotWidth/$this.slotTime;
    var mouseX = 0;
    var mouseY = 0;

    // mouse position at last click
    var lastX = 0;
    var lastY = 0;
    var mouseDown = false;

   $this.construct = function(data){
        $this.appendCss();
        $this.createRealtimeSlots(data);
        $this.initSlider(data);
        console.log($this.currentHour);
        $this.moveTo($this.currentHour);
        $this.appendPrograms();
        window.onresize = function(){ this.location.reload(); }
        setTimeout(function() { 
            $this.currentHour = today.getHours();
            console.log($this.currentHour);
            if(currentHour != $this.currentHour){
                $this.moveTo($this.currentHour);
                currentHour = $this.currentHour;
            }
         }, 1*60*1000);
   }
   $this.reInit = function(data){
        $this.init();
        $this.construct(data);
   }

   $this.init = function(data){
        $this.windowHeight= $(window).height();
        $this.windowWidth = $(window).width();
        today = new Date(),currentHour = today.getHours();
        totalTimeSlots = 0;
        this.totalHours = 24;
        this.visibleWidth;
        this.totalVisibleSlots;
        this.slotTime = 60;///////In Minutes
        this.channelLogoWidth = 100;
        this.realtimeChannelsWidth = 120;
        this.slotWidth = 250;
        if($this.windowWidth < 1000){
            this.channelLogoWidth = 50;
            this.realtimeChannelsWidth = 70;
            this.slotWidth = 150;        
        }
        this.currentSlot = 0;
        this.currentHour = currentHour;
        $this.visibleWidth = $('.realtime-timer').width();
        $this.totalVisibleSlots = Math.floor($this.visibleWidth/$this.slotWidth);
        console.log($this.visibleWidth);
        console.log($this.totalVisibleSlots);

        $this.pixelsPerMinute = $this.slotWidth/$this.slotTime;
        mouseX = 0;
        mouseY = 0;

        // mouse position at last click
        lastX = 0;
        lastY = 0;
        mouseDown = false;
        $('.time-slot-boxes').html('');
        $('.time-slot-boxes-clone').html('');
        $('.time-slots').html('');
        $('.realtime-channels').html('');
   }

   function getOffset(elId) {
        var el = document.getElementById(elId);
        var rect = el.getBoundingClientRect();
        return {
        left: rect.left + document.body.scrollLeft,
        top: rect.top + document.body.scrollTop
        };
  }   
   $this.appendCss = function(hour){
        var sheet = document.createElement('style');
        sheet.innerHTML = '\
        .realtime-channel-logo{max-height:'+($this.channelLogoWidth-20)+'px;}\
        .time-slot-box{width:'+$this.channelLogoWidth+'px;height:'+$this.channelLogoWidth+'px;line-height:'+$this.channelLogoWidth+'px}\
        .realtime-channels{width:'+$this.realtimeChannelsWidth+'px;}\
        .realtime-timer{margin-left:'+($this.realtimeChannelsWidth )+'px;}\
        .abs-program-card{height:'+($this.channelLogoWidth-10 )+'px;line-height:'+($this.channelLogoWidth-10 )+'px;}\
        ';
        
        document.body.appendChild(sheet); // append in body
        document.head.appendChild(sheet); // append in head
   }

   $this.moveTo = function(hour){
       if(hour<0)return;
       if(hour<10)hour='0'+hour;
        var currentTimeSlotTime = $('#time-slots-time-'+hour+'');
        var slideNumberToMoveTo = currentTimeSlotTime.data('slide_number') - 1; ////// because $this.currentSlot starts with 0
        //console.log(slideNumberToMoveTo);
        if(slideNumberToMoveTo > 24-Math.floor($this.totalVisibleSlots/2))slideNumberToMoveTo = 24-Math.floor($this.totalVisibleSlots/2);
        var numberOfStepsToMove = slideNumberToMoveTo; ///// Because the for loop starts from -12
        //console.log($this.slotWidth);
        ////// we want this slide number to be in center if its not first or last
        //// Math.floor($this.totalVisibleSlots/2)
        //console.log('#time-slots-time-'+hour+'');
        //console.log(numberOfStepsToMove);
        //console.log(Math.floor($this.totalVisibleSlots/2));
        //console.log($this.currentSlot);

        if($this.totalVisibleSlots>2){
            if(numberOfStepsToMove >= Math.floor($this.totalVisibleSlots/2)){
                numberOfStepsToMove = numberOfStepsToMove - Math.floor($this.totalVisibleSlots/2);
            }
            else{
                numberOfStepsToMove = 0;
            }
        }

        $this.currentSlot = numberOfStepsToMove;
        $this.moveHtmlContent({currentSlot:$this.currentSlot});
      
   }

   $this.moveHtmlContent = function(data){
       //if(!data.currentSlot)return;
       //if(data.currentSlot >= 24-$this.totalVisibleSlots)return;
       $this.currentSlot = data.currentSlot;
       console.log($this.currentSlot);
        $('.time-slot-boxes').css('transform','translateX(-'+ ((data.currentSlot*$this.slotWidth)) +'px)');
        $('.time-slot-boxes').css('transition','all 0.5s');
        $('.time-slot-boxes-clone').css('transform','translateX(-'+ ((data.currentSlot*$this.slotWidth)) +'px)');
        $('.time-slot-boxes-clone').css('transition','all 0.5s');
        $('.time-slots').css('transform','translateX(-'+ ((data.currentSlot*$this.slotWidth)) +'px)');
        $('.time-slots').css('transition','all 0.5s');
   }

   $this.moveToNextTimeSlot = function(){

   }

   $this.initSlider = function(data){
        $('.time-slots-time').each(function(k,e){
            //console.log($(e).html());
            $(e).css('width',''+$this.slotWidth+'px');
            totalTimeSlots++;
        });
        $('.time-slots').css('width',''+ ($this.slotWidth*24) +'px');

        var totalTimeSlots = 0;
        $('.time-slot-box').each(function(k,e){
                //console.log($(e).html());
                $(e).css('width',''+$this.slotWidth+'px');
                totalTimeSlots++;
        });
        $('.time-slot-boxes').css('width',''+ ($this.slotWidth*24) +'px');

        ///// SET THE CLONE CSS PROPERTIES
        $('.time-slot-boxes-clone').css('width',''+ ($this.slotWidth*24) +'px');
        $('.time-slot-boxes-clone').css('height',$('.time-slot-boxes').height());
        var rectTemp = $('.time-slot-boxes')[0].getBoundingClientRect();
        var left = rectTemp.left + window.scrollX;
        var top = rectTemp.top + window.scrollY;
        //$('.time-slot-boxes-clone').css('top','0px');
        //$('.time-slot-boxes-clone').css('left','0px');


        /* $this.visibleWidth = $('.realtime-timer').width();
        $this.totalVisibleSlots = $this.visibleWidth/$this.slotWidth;
        console.log($this.visibleWidth);
        console.log($this.totalVisibleSlots); */

        $(document).on('click','.btn-fixed-left', function(e){
            $this.moveToPrevious();
        });
        $(document).on('click','.btn-fixed-right', function(e){
            $this.moveToNext();
        });


        $('.realtime-timer')[0].addEventListener('mousewheel', $this.onMouseWheel, false);
        $('.realtime-timer')[0].addEventListener('DOMMouseScroll', $this.onMouseWheel, false); // firefox
        //$('.realtime-timer')[0].addEventListener("drag", $this.onDrag, false);
        $('.realtime-timer')[0].addEventListener("mousedown", $this.mouseDown, false);
        document.addEventListener("mouseup", function(){mouseDown=false}, false);
        $('.realtime-timer')[0].addEventListener("mouseup", $this.mouseUp, false);
        $('.realtime-timer')[0].addEventListener("mouseover", $this.mouseOver, false);


        $(document).on('touchstart','.realtime-timer',function(event){
            //event.preventDefault();
            console.log('touchstart');
            mouseDown = true;
        });
        $(document).on('touchmove','.realtime-timer',function(event){
            mouseDown = true;
            console.log('touchmove');
            $this.mouseOver(event);
        });        
   }

   $this.moveToPrevious = function(){
        console.log($this.currentSlot);
        if($this.currentSlot<=0)return;
        $this.currentSlot--;
        $this.moveHtmlContent({currentSlot:$this.currentSlot});
   }

   $this.moveToNext = function(){
        console.log($this.currentSlot);
        if($this.currentSlot >= 25-$this.totalVisibleSlots)return;
        $this.currentSlot++;
        $this.moveHtmlContent({currentSlot:$this.currentSlot});
   }
   $this.createRealtimeSlots = function(data){
        var $ic = 1;
        var $htmlSideBar = '';
        var $htmlTimeSlotBoxes = '';
        var $timeSlotsHtml = '';
        $.each($selectedChannels, function($k,$channel){
            $programs = $channelPrograms[$channel['id']];
            //print_r($channel);
            $htmlSideBar += '<div class="channel-box" id="channel-box-'+$channel['id']+'"><img class="realtime-channel-logo" src="'+FRONTEND_SITE_PATH+$channel['image_path']+'200X200-'+$channel['image']+'"><p class="m-0 p-0">'+$channel['name']+'</p></div>';
            $ic++;
            $timeSlotsHtml = '';
            var $timeSlotBoxesTemp = '';
            var today = new Date(),h = today.getHours();
            for(var $i=1;$i<=24;$i++){
                var currentHour  = $i;
                if(currentHour < 10)currentHour = '0'+currentHour;
                if(currentHour == 24)currentHour = '00';
                var currentHour12 = currentHour;
                if(currentHour12 >= 12){
                    currentHour12 = currentHour12-12;
                }
                var amPm = (currentHour < 12)? 'AM': 'PM'
                $timeSlot = currentHour12 + ":00 "+amPm;
                $class = '';
                if($i == $this.currentHour){
                    $class = 'current';
                }
                if($i == 24 && $this.currentHour == 0){
                    $class = 'current';
                }
                $timeSlotsHtml += '<div class="time-slots-time '+$class+'" data-slide_number="'+$i+'" data-hour="'+currentHour+'" id="time-slots-time-'+currentHour+'"> <p class="m-0" style="font-size:16px;"><i class="flaticon-time-1"></i> '+$timeSlot+'</p><p class="m-0">'+currentHour+':30</p></div>';
                //'+$channel['name']+' '+$timeSlot+'
                programName = 'no programs';
                if($programs[currentHour]){
                    programName = $programs[currentHour]['name'];
                }
                /* $timeSlotBoxesTemp += '<div class="time-slot-box text-center '+$class+'" data-slide_number="'+$i+'">\
                <div class="time-slot-program" style=""> '+programName+'</div>\
                </div>'; */
                $timeSlotBoxesTemp += '<div class="time-slot-box text-center '+$class+'" id="time-slot-box-'+$channel['id']+'-'+currentHour+'" data-slide_number="'+$i+'">'+programName+'</div>';
            }
            //$htmlTimeSlotBoxes += $timeSlotBoxesTemp;
            $htmlTimeSlotBoxes += '<div class="wrap-time-boxes" >'+$timeSlotBoxesTemp+'</div>';

        });

        $('.time-slot-boxes').prepend($htmlTimeSlotBoxes);
        $('.time-slots').html($timeSlotsHtml);
        $('.realtime-channels').html($htmlSideBar);


        ///// Foreach program get start time and end time and channel id
        ///// Calculate the length of the program
        ///// Convert minutes to pixes, assume a scale for that : IDEA: calculate the width of 1 time slot and divide it by slots pixel length
        ////// According to length of the program 
        //var clonedTimeSlotBoxes = $('.time-slot-boxes').clone();

    }

    $this.appendPrograms = function(){
        $.each($channelPrograms2,function($k,$program){
            //$program = $channelPrograms2['2'];
            //console.log($program);
            var progLength = $program['length'];
            var classColor ='';
            if(progLength <= 30){
                classColor ='min-30';
            }
            else if(progLength <= 45){
                classColor ='min-45';

            }
            else if(progLength <= 60){
                classColor ='min-60';

            }
            else if(progLength <= 75){
                classColor ='min-75';

            }
            else if(progLength <= 90){
                classColor ='min-90';

            }
            else if(progLength <= 120){
                classColor ='min-120';

            }
            else{
                classColor ='min-60';
            }
            //console.log(progLength);
            $start_hour = $program['start_hour'];
            if($start_hour == $this.currentHour){
                classColor +=' current';
            }
            //console.log($start_hour);
            var $coord = getOffset('time-slot-box-'+$program['channel_id']+'-'+$start_hour+'');
            //var $xCoord = getOffset('time-slots-time-'+$start_hour+'');
            var slide_no = $('#time-slot-box-'+$program['channel_id']+'-'+$start_hour+'').data('slide_number');
            var minute_offset = $program['start_minute'] * $this.pixelsPerMinute;
            var xPos = $coord['left'] - ($this.slotWidth/2) + minute_offset;
            var xPos = ((slide_no-1)*$this.slotWidth) + minute_offset;
            //console.log($program['channel_id']);
            //console.log($coord['top']);
            var yPos = $coord['top'] - 40 - 10;
            //var yPos = 0;
            var progDiv = '<div class="abs-program-card text-center '+classColor+'" id="abs-program-'+$program['id']+'" style="width:'+(progLength*$this.pixelsPerMinute)+'px;left:'+xPos+'px;top:'+yPos+'px">'+$program['name']+'</div>';
            $('.time-slot-boxes-clone').append(progDiv);
            //$currentTime = date('H:i');
        });    
    }

    $this.onDrag = function(event){
        event.preventDefault();
        console.log(event);
    }

    $this.mouseUp = function(event){
        event.preventDefault();
        mouseDown = false;
        //console.log(event);
    }

    $this.mouseDown = function(event){
        event.preventDefault();
        mouseDown = true;
        lastX = mouseX;
        lastY = mouseY;
        mouseX = event.clientX;
        mouseY = event.clientY;
        //console.log(event);
    }
    $this.mouseOver = function(event){
        event.preventDefault();
        //console.log(event);

        if(mouseDown){
            lastX = mouseX;
            lastY = mouseY;
            mouseX = event.clientX;
            mouseY = event.clientY;

            if(lastX < mouseX){
                $this.moveToPrevious();
            }
            else if(lastX > mouseX){
                $this.moveToNext();
            }
        }
    }

    $this.onMouseWheel = function(event){
        event.preventDefault();
        var delta = 0;
        // WebKit / Opera / Explorer 9
        if (event.wheelDelta){ 
            delta = event.wheelDelta;
        }
        // Firefox
        else if (event.detail){
            delta = -event.detail;
        }

        //console.log(delta);
        if(delta > 0){
            $this.moveToNext();
        }
        else{
            $this.moveToPrevious()
        }
        //console.log($this.currentSlot);
    }


    function getEventCoordinates(e){
			
        //console.log(e);
        var x = null;
        var y = null;
        if(e.type == 'touchstart' || e.type == 'touchend' || e.type == 'touchcancel'){
            //var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
            var touch = (
                e.touches ? e.touches[0] : // if 
                e.originalEvent.changedTouches ? e.originalEvent.changedTouches[0] : // else if 
                e.originalEvent.touches ? e.originalEvent.touches[0] : // else if 
                null // else 
              );					
            x = touch.clientX;
            y = touch.clientY;
        } 
        else if(e.type == 'touchmove'){
            //var touch = (if(e.touches) e.touches[0]) || e.originalEvent.changedTouches[0];
            var touch = (
                e.touches ? e.touches[0] : // if 
                e.originalEvent.changedTouches ? e.originalEvent.changedTouches[0] : // else if 
                null // else 
              );				
            x = touch.clientX;
            y = touch.clientY;
        }
        else if (e.type == 'mousedown' || e.type == 'mouseup' || e.type == 'mousemove' || e.type == 'mouseover'|| e.type=='mouseout' || e.type=='mouseenter' || e.type=='mouseleave') {
            x = e.clientX;
            y = e.clientY;
        }
        var ret = {};
        ret['x'] = x;
        ret['y'] = y;
        return ret;
    }

    $this.construct(data);
}

function showPopover(data){
    console.log(data);
    popover_template =
    '\
        <div class="popover " role="tooltip"  style="box-shadow: 0px 20px 20px 0px rgba(14, 14, 14, 0.5);">\
            <div class="arrow"></div>\
            <div class="popover-body"  style="max-height:200px;">\
            \
            </div>\
        </div>\
    ';
    var start_date = new Date(data['start_time']);
    console.log(start_date);
    var start_time = start_date.toLocaleTimeString('en-US');
    var start_time_minutes = (start_date.getHours() * 60) + start_date.getMinutes();
    var start_time_minutes_only = start_date.getMinutes();

    if(!data['brand']['image_path']){
        data['brand']['image_path'] = 'core_theme/';
        data['brand']['image'] = 'images/noimageavailable.png';
    }
    var popoverContent = '\
    <div class="row" style="">\
    <a href="javascript:;" onClick="$(\'#'+data['start_time']+'\').popover(\'hide\');" style="position:absolute;top:5px;right:5px">X</a>\
    <div class="col-4 text-center" style="">\
        <img src="'+FRONTEND_SITE_PATH+''+data['brand']['image_path']+data['brand']['image']+'" style="max-height:70px;">\
    </div>\
    <div class="col-7 text-center" style="">\
            <span class="span-value" > '+data['brand']['name']+'</span><br>\
            <span class="span-value" > '+start_time+'</span></br>\
            <span class="span-label">Spot:</span>\
            <span class="span-value" > '+data['desired_spot']+'</span></br>\
            <span class="span-label">Break:</span>\
            <span class="span-value" > '+data['break_number']+'</span></br>\
    </div>\
    </div>\
    ';
    //var temp_id = $('#' + clickData + '').attr('id');
    var tempEl = '<span class="realtime-dot" id="'+data['start_time']+'" style="top:20px;left:'+(classSlots.pixelsPerMinute*start_time_minutes_only)+'px">\
    <img src="'+FRONTEND_SITE_PATH+''+data['brand']['image_path']+data['brand']['image']+'" style="max-height:20px;">\
    </span>';
    
    $('#abs-program-'+data['program_id']+'').append(tempEl);
    $element = $('#'+data['start_time']+'');
    placementPop = 'bottom';
    $element.popover({
        title: 'You Ad',
        content: popoverContent,
        trigger: 'click hover',
        html: true,
        container: '.time-slot-boxes-clone',
        placement: placementPop,
        template: popover_template
    });
    classSlots.moveTo(classSlots.currentHour);
    $element.popover('show');
    setTimeout(function() { $element.popover('hide'); }, 10000);
}  