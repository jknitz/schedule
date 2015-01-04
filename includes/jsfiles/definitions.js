/*** class definition for drop menus ***/

/*******************/
/*** module Ajax ***/
/*******************/
Ajax = (function() {
  doajax = function (params, data, before, done, fail, always) {
    beforestd(); 
    $.ajaxQueue({ type:"post", url:"ajax/ajaxprocess.php", dataType:'json', data:data, done:done, fail:fail, always:always}, 'Ajax');
    //alert('doajax ' + params['targetid']);
  }; // close doajax
  
  failstd = function(jqXHR, textStatus, errorThrown) { //alert('in fail');
    var errmsg = 'ajxerr: ' + jqXHR.responseText + ' txtstat: ' + textStatus + ' errthro: ' + errorThrown;
    Report.err(errmsg);
  }; // close failstd
  
  alwaysstd = function(myreturn, status, jqXHR) {
    var now = $.now() - SysConfig.ajaxtime;
    //Report.msg(' SysConfig.ajaxtime: ' + now);
    var $content = params['$myview'].find('[data-role=content]');
    $content.css('background-color', params['background'] );
    params['$myview'].data('shrunk','false');
    $content.show();
  }; // close alwaysstd
  
  alwaysstub = function(){};

  beforestub = function(){};
  
  beforestd = function(){};

  doneupdate = function(myreturn, status, jqXHR) { //alert('in doneupdate');
    if (params['targetid'] == 'right') loadright(myreturn);
    else {
      rowobj.id = '';
      var $target = $('#' + params['targetid']);
      $target.html(myreturn.html);
      $('html, body').animate({ scrollTop: $target.offset().top - 200 }, '2000');
    }
  } // close doneupdate


  donedelete = function(myreturn, status, jqXHR) {
    if (params['targetid'] == 'right') loadright(myreturn);
    else {
      rowobj.id = '';
      var $target = $('#' + params['targetid']);
      $target.html(myreturn.html);
      $('html, body').animate({ scrollTop: $target.offset().top - 200 }, '2000');
    }
  };
  
  donecreate = function(myreturn, status, jqXHR) {
    if (params['targetid'] == 'right') loadright(myreturn);
    else {
      rowobj.id = '';
      var $target = $('#' + params['targetid']);
      $target.html(myreturn.html);
      $('html, body').animate({ scrollTop: $target.offset().top - 200 }, '2000');
    }
  };
  
  doneread = function(myreturn, status, jqXHR) { //alert('donread: ' + params['targetid']);
    if (params['targetid'] == 'right') loadright(myreturn);
    else {
      rowobj.id = '';
      var $target = $('#' + params['targetid']);
      $target.html(myreturn.html);
      $('html, body').animate({ scrollTop: $target.offset().top - 200 }, '2000');
    }
  };
  
  loadright = function(myreturn) {
    var $target = $('#right');
    $target.html(myreturn.html);
    $('html, body').animate({ scrollTop: $target.offset().top - 200 }, '2000');
    rowobj.id = '-1';
    $('table tr').css('background', 'none');
    $('#left div.leftdiv').css('background', 'none');
    if (params['id'] != 'search') $('#left #' + params['id']).css('background', '#cff');
  }


  doajxdelete = function(targetid, jsondata, inparams, callback) {
    var callback = callback || '';
    if (typeof inparams === 'undefined') params = {};
    else params = inparams;
    params['targetid'] = targetid;
    params['callback'] = callback;
    data = { process:'delete', 'jsondata':jsondata };
    doajax(params, data, beforestub, donedelete, failstd, alwaysstd);
  }; // close doajxdelete
  
  doajxcreate = function(targetid, jsondata, inparams, callback) {  
    var callback = callback || '';
    if (typeof inparams === 'undefined') params = {};
    else params = inparams;
    params['targetid'] = targetid;
    params['callback'] = callback;
    data   = { process:'create', 'jsondata':jsondata };
    doajax(params, data, beforestub, donecreate, failstd, alwaysstub);
  }; // close doajxcreate
    
  doajxupdate = function(targetid, jsondata, inparams, callback) {
    var callback = callback || '';
    if (typeof inparams === 'undefined') params = {};
    else params = inparams;
    params['targetid'] = targetid;
    params['callback'] = callback;
    data = { process:'update', 'jsondata':jsondata};//alert('in doajxupdate');
    doajax(params, data, beforestub, doneupdate, failstd, alwaysstub);
  }; // close doajxupdate
  
  doajxread = function(targetid, jsondata, inparams, callback) {
    var callback = callback || '';
    if (typeof inparams === 'undefined') params = {};
    else params = inparams;
    params['targetid'] = targetid;
    params['callback'] = callback;
    data = { process:'read', 'jsondata':jsondata};
    doajax(params, data, beforestub, doneread, failstd, alwaysstub);
  }; // close doajxread
  
  return {
    del       : doajxdelete ,
    update    : doajxupdate ,
    create    : doajxcreate ,
    read      : doajxread ,
  };
})(); // close Ajax
/*** End module Ajax ***/