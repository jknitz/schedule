//xxx time clock xxx//
function secondClock() {
  var now = new Date();
  var date = now.toDateString();
  var mins = now.getMinutes();
  if (mins < 10) mins = '0' + mins.toString();
  var secs = now.getSeconds();
  if (secs < 10) secs = '0' + secs.toString();
  var time = now.getHours() + ':' + mins + ':' + secs;
  $('div#header #date').html(date);
  $('div#header #time').html(time);
}

//xxx handler etc for windows resize xxx//
var resizeTimer;
var windowsize = {
  width :  0 ,
  maxwidth : 990 ,
  leftwidth : 200 ,
  wrapperpadding : 5 ,
} // close var windowsize
function myresize() {
  windowsize.width = screenutils.pageWidth();
  $right = $('#wrapper #right');
  var wrapperwidth = $('#wrapper').css('width');
  $('#header').css('width', wrapperwidth);
  var headerheight = $('#header').css('height');
  var lefttop = parseInt(headerheight) + parseInt($('#wrapper').css('margin-top') ) + 2*windowsize.wrapperpadding;
  $('#left').css('top', lefttop + 'px');
  
  if (windowsize.width < windowsize.maxwidth) {
    //$('#overlay').css('margin-left', '0px');
    $('#left').css('width', wrapperwidth);
    var rightwidth = parseInt(wrapperwidth) - 9;
    $right.css('width', rightwidth + 'px').css('margin-left', '0px');
    var righttop = parseInt($('#header').css('height')) + parseInt($('#left').css('height')) + 2*windowsize.wrapperpadding;
    $right.css('margin-top', righttop + 'px');
  } else {
    //$('#overlay').css('margin-left', windowsize.wrapperpadding);
    $('#left').css('width', windowsize.leftwidth);
    var righttop = parseInt(headerheight) + windowsize.wrapperpadding;
    $right.css('margin-top', righttop + 'px');
    var rightwidth = parseInt(wrapperwidth) - windowsize.leftwidth -14;
    $right.css('width', rightwidth + 'px').css('margin-left', windowsize.leftwidth + windowsize.wrapperpadding + 'px');
  }
}
function doresize() {
  window.clearTimeout(resizeTimer);
  resizeTimer = setTimeout(myresize, 100);
}

//xxx row object specs and handlers xxx//
var rowobj = {
  handlerowclick : function($thistr) {
    rowobj.id     = $thistr.data('id');
    rowobj.line   = $thistr.data('line'); // todo is line reqquired
    rowobj.dayymd = $thistr.data('dayymd');
    rowobj.date   = $thistr.find('td:nth-child(1)').text();
    rowobj.day    = $thistr.find('td:nth-child(2)').text();
    rowobj.person = $thistr.find('td:nth-child(3)').text();
    rowobj.time   = $thistr.find('td:nth-child(4)').text();
    rowobj.event  = $thistr.find('td:nth-child(5)').text();
  } ,
  id : '' ,
  line : '' ,
  dayymd : '' ,
  date : '' ,
  day: '' ,
  person : '' ,
  time : '' ,
  event : '' ,
  radio : 'unchecked' ,
  editevent : function() {
    $('#overlay').show();
    var headertext = rowobj.day + ' ' + rowobj.date;
    $('#overlay #overlayheader').text(headertext);
    if (rowobj.id >= '0') {
      var data = { 'id':'editevent', rowid : rowobj.id };
      var jsondata = JSON.stringify(data);
      Ajax.read('overlaycontent', jsondata);
    } else {
      rowobj.newevent();
    }
  } ,
  newevent : function() {
    $('#overlay').show();
    var headertext = rowobj.day + ' ' + rowobj.date;
    $('#overlay #overlayheader').text(headertext);
    var data = { 'id':'createevent', 'dayymd':rowobj.dayymd, 'type':'overlaycontent' };
    var jsondata = JSON.stringify(data);
    Ajax.create('overlaycontent', jsondata);
  } ,
  getFormValues() {
    //xxx insure person is checked
    rowobj.radio = 'unchecked';
    $('#overlay input:radio').each(function(i,elem) {
      if (elem.checked) rowobj.radio = 'checked';
    });
    if(rowobj.radio == 'unchecked') {
      alert('Please select a person');
      return 0;
    }
    //xxx gather values
    var dayymd = rowobj.dayymd;
    var person = $('input[name=person]:checked').val();
    var time   = $('input[name=time]').val();
    var event  = $('input[name=event]').val();
    if (event.trim() == '') {
      alert('Event must have an entry');
      return;
    }
    var rowid  = $('input[name=rowid]').val();
    var data = {'rowid' : rowid, 'dayymd':dayymd, 'person' : person, 'time' : time, 'event' : event};
    return data;
  } ,
  reset : function() {
    $('#right tr').css('background', 'none');
    rowobj.id = '-1';
    $('div.leftdiv').css('background', '#ddf');
  } ,

} // close rowobj

//xxx nav click handlers xxx//
function handlenavclick(id) {
  if (id == "schedule" || id == "futureevents" || id == "john" 
   || id == "marcia" || id == "pastevents" || id == "search") {
    //alert('read');
    var data = { "id":id};
    jsondata = JSON.stringify(data);
    var params = {'id':id};
    Ajax.read('right', jsondata, params);
    rowobj.reset();
  } // close if read
  else if (id == 'editevent') {
    if (rowobj.id < 0) {
      alert('Please select a row');
      return;
    }
    if (rowobj.id == 0) {
      rowobj.newevent();
    } else rowobj.editevent();
  }
  else if (id == 'newevent') {
    if (rowobj.id < 0) {
      alert('Please select a row');
      return;
    }
    rowobj.newevent();
  }
  else if (id == 'delete') {
    if (rowobj.id < 1) {
      alert('Please select an event');
      return;
    }
    var data = {'rowid':rowobj.id };
    jsondata = JSON.stringify(data);
    var params = {'id':id};
    Ajax.del('right', jsondata, params);
  }
  else if (id == 'addsixmonths') {
    var data = {'type':'addsixmonths'};
    var jsondata = JSON.stringify(data); 
    Ajax.update('right', jsondata);
  }
  else if (id == 'modifydatecolumn') {
    var data = {'type':'modifydatecolumn'};
    var jsondata = JSON.stringify(data); 
    Ajax.update('right', jsondata);
  }
  else alert(id);
} // close handlenavclick(id)


$( document ).ready( function() {
//xxx set date and time every second xxx//
secondClock();
setInterval(function(){ secondClock();}, 1000); // start second clock

//xxx bind the resize event, trigger on load xxx//
$(window).bind("resize", doresize);
$(document).trigger('resize');

//xxx attach nav click handlers xxx//
$('div.leftdiv').click(function() {
  $('div.leftdiv').css('background', 'none');
  $(this).css('background', '#cff');
  handlenavclick($(this).attr('id')); 
});
$('#left #schedule').trigger('click');

//xxx attach table row click handler xxx//
$('#right').on('click', 'table tr', function() {
  $('table tr').css('background', 'none');
  $rowobjtr = $(this);
  $rowobjtr.css('background', '#ffc');
  rowobj.handlerowclick($rowobjtr);
});

//xxx attach overlay update submit button click handlers xxx//
$('#overlay #overlaycontent').on('click', '#submitbtnupdate', function() {
  var data = rowobj.getFormValues();
  data.type = 'updateschedule';
  var jsondata = JSON.stringify(data); 
  Ajax.update('right', jsondata);
  $('#overlay').hide();
});

//xxx attach overlay create submit button click handlers xxx//
$('#overlay #overlaycontent').on('click', '#submitbtncreate', function() {  //alert('in submi create');
  var data = rowobj.getFormValues();
  if (data == 0) return;
  data['type'] = 'createrow';
  var jsondata = JSON.stringify(data);
  Ajax.create('right', jsondata);
  $('#overlay').hide();
});

//xxx attach search 'enter' handler xxx//
$('#header #search').keydown(function (e){
    if(e.keyCode == 13){
      var searchtext = $('#header #search').val();
      var data = {'id':'search', 'text':searchtext};
      var jsondata = JSON.stringify(data);
      var params = {'id':'search' };
      Ajax.read('right', jsondata, params);
    }
})

//xxx overlay concel button; click handler xxx//
$('#overlay #overlaycontent').on('click', '#cancelbtn', function() {
  $('table tr').css('background', 'none');
  $('#overlay').hide();
});


}) // close $( document ).ready(